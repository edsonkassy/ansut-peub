<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;

class LogoService
{
    protected $imageManager;
    protected $config;
    protected $disk = 'public';

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
        $this->config = config('image.logos');
    }

    /**
     * Process and store a new logo
     */
    public function processAndStoreLogo(UploadedFile $file): array
    {
        try {
            // Validate file
            $this->validateLogo($file);

            // Load image
            $image = $this->imageManager->read($file->path());

            // Validate dimensions
            $this->validateDimensions($image);

            // Process image
            $image = $this->processImage($image);

            // Generate filename and store
            $filename = $this->generateFilename($file);
            $path = $this->config['storage_path'] . '/' . $filename;

            // Encode and store
            $encodedImage = $this->encodeImage($image, $file->getClientOriginalExtension());
            Storage::disk($this->disk)->put($path, $encodedImage);

            return [
                'success' => true,
                'path' => $path,
                'disk' => $this->disk
            ];
        } catch (\Exception $e) {
            Log::error('Logo processing failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete an existing logo
     */
    public function deleteLogo(?string $path): bool
    {
        if (!$path) {
            return true;
        }

        try {
            return Storage::disk($this->disk)->delete($path);
        } catch (\Exception $e) {
            Log::error('Logo deletion failed', [
                'error' => $e->getMessage(),
                'path' => $path
            ]);
            return false;
        }
    }

    /**
     * Validate logo file
     */
    protected function validateLogo(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $this->config['allowed_formats'])) {
            throw new \Exception('Format de fichier non autorisé. Formats acceptés : ' . implode(', ', $this->config['allowed_formats']));
        }

        if ($file->getSize() > $this->config['max_size'] * 1024) {
            throw new \Exception('Le fichier est trop volumineux. Taille maximale : ' . $this->config['max_size'] . 'KB');
        }
    }

    /**
     * Validate image dimensions
     */
    protected function validateDimensions($image): void
    {
        $width = $image->width();
        $height = $image->height();

        if ($width < $this->config['min_width'] || $height < $this->config['min_height']) {
            throw new \Exception("L'image est trop petite. Dimensions minimales : {$this->config['min_width']}x{$this->config['min_height']} pixels");
        }
    }

    /**
     * Process the image
     */
    protected function processImage($image)
    {
        // Resize if needed
        if ($image->width() > $this->config['max_width'] || $image->height() > $this->config['max_height']) {
            $image = $image->scaleDown($this->config['max_width'], $this->config['max_height']);
        }

        return $image;
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return uniqid('logo_') . '_' . time() . '.' . $extension;
    }

    /**
     * Encode image based on format
     */
    protected function encodeImage($image, string $format)
    {
        $format = strtolower($format);
        
        return match($format) {
            'png' => $image->encode(new PngEncoder($this->config['quality'])),
            'webp' => $image->encode(new WebpEncoder($this->config['quality'])),
            default => $image->encode(new JpegEncoder($this->config['quality']))
        };
    }
} 
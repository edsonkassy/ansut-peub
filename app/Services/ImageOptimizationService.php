<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ImageOptimizationService
{
    protected $manager;
    protected $maxWidth = 1200;
    protected $maxHeight = 1200;
    protected $quality = 85;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Optimise et stocke une image
     */
    public function optimizeAndStore($uploadedFile, $directory)
    {
        // Vérifier si le fichier est une image valide
        if (!$uploadedFile->isValid() || !$this->isImage($uploadedFile)) {
            throw new \Exception('Le fichier n\'est pas une image valide.');
        }

        // Obtenir le format d'origine
        $format = $this->getImageFormat($uploadedFile);

        // Générer un nom de fichier unique
        $filename = uniqid() . '.' . $format;
        $path = $directory . '/' . $filename;

        // Optimiser l'image
        $optimizedImage = $this->optimizeImage($uploadedFile, $format);

        // Stocker l'image optimisée
        Storage::put($path, $optimizedImage);

        return $path;
    }

    /**
     * Vérifie si le fichier est une image
     */
    protected function isImage($file)
    {
        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ];

        return in_array($file->getMimeType(), $allowedMimes);
    }

    /**
     * Obtient le format de l'image
     */
    protected function getImageFormat($file)
    {
        $mime = $file->getMimeType();
        switch ($mime) {
            case 'image/jpeg':
                return 'jpg';
            case 'image/png':
                return 'png';
            case 'image/gif':
                return 'gif';
            case 'image/webp':
                return 'webp';
            case 'image/svg+xml':
                return 'svg';
            default:
                throw new \Exception('Format d\'image non supporté.');
        }
    }

    /**
     * Optimise l'image
     */
    protected function optimizeImage($file, $format)
    {
        // Si c'est un SVG, le retourner tel quel
        if ($format === 'svg') {
            return file_get_contents($file->path());
        }

        // Charger l'image
        $image = $this->manager->read($file->path());

        // Redimensionner si nécessaire
        if ($image->width() > $this->maxWidth || $image->height() > $this->maxHeight) {
            $image = $image->scaleDown($this->maxWidth, $this->maxHeight);
        }

        // Optimiser la qualité et retourner l'image encodée
        switch ($format) {
            case 'png':
                return $image->encode(new PngEncoder($this->quality));
            case 'gif':
                return $image->encode(new GifEncoder());
            case 'webp':
                return $image->encode(new WebpEncoder($this->quality));
            case 'jpg':
            default:
                return $image->encode(new JpegEncoder($this->quality));
        }
    }

    /**
     * Optimise une image et la stocke
     */
    public function optimizeAndStoreOriginal(UploadedFile $file, string $path, string $disk = 'public'): string
    {
        try {
            // Vérifier si c'est une image
            if (!$this->isImage($file)) {
                // Si ce n'est pas une image, stocker directement
                return $file->store($path, $disk);
            }

            // Générer un nom de fichier unique
            $filename = $this->generateUniqueFilename($file);
            $fullPath = $path . '/' . $filename;

            // Optimiser l'image
            $optimizedImage = $this->optimizeImage($file, $this->getImageFormat($file));

            // Stocker l'image optimisée
            Storage::disk($disk)->put($fullPath, $optimizedImage);

            Log::info('Image optimisée et stockée', [
                'original_size' => $file->getSize(),
                'optimized_path' => $fullPath,
                'original_name' => $file->getClientOriginalName()
            ]);

            return $fullPath;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'optimisation d\'image', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);

            // En cas d'erreur, stocker le fichier original
            return $file->store($path, $disk);
        }
    }

    /**
     * Génère un nom de fichier unique
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::random(40);
        return $name . '.' . $extension;
    }

    /**
     * Optimise et stocke plusieurs images
     */
    public function optimizeAndStoreMultiple(array $files, string $path, string $disk = 'public'): array
    {
        $paths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->optimizeAndStore($file, $path, $disk);
            }
        }

        return $paths;
    }

    /**
     * Optimise une image existante et la remplace
     */
    public function optimizeExistingImage(string $filePath, string $disk = 'public'): bool
    {
        try {
            if (!Storage::disk($disk)->exists($filePath)) {
                return false;
            }

            $fileContent = Storage::disk($disk)->get($filePath);
            $tempPath = tempnam(sys_get_temp_dir(), 'optimize_');
            file_put_contents($tempPath, $fileContent);

            // Créer un UploadedFile temporaire
            $uploadedFile = new UploadedFile(
                $tempPath,
                basename($filePath),
                Storage::disk($disk)->mimeType($filePath),
                null,
                true
            );

            // Optimiser l'image
            $optimizedContent = $this->optimizeImage($uploadedFile, $this->getImageFormat($uploadedFile));

            // Remplacer le fichier original
            Storage::disk($disk)->put($filePath, $optimizedContent);

            // Nettoyer le fichier temporaire
            unlink($tempPath);

            Log::info('Image existante optimisée', [
                'file_path' => $filePath
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'optimisation d\'image existante', [
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Obtient les statistiques d'optimisation
     */
    public function getOptimizationStats(string $filePath, string $disk = 'public'): array
    {
        if (!Storage::disk($disk)->exists($filePath)) {
            return [];
        }

        $fileSize = Storage::disk($disk)->size($filePath);
        $mimeType = Storage::disk($disk)->mimeType($filePath);

        return [
            'size' => $fileSize,
            'size_formatted' => $this->formatBytes($fileSize),
            'mime_type' => $mimeType,
            'is_image' => strpos($mimeType, 'image/') === 0
        ];
    }

    /**
     * Formate les bytes en format lisible
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
} 
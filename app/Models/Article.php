<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'contenu',
        'resume',
        'image_principale',
        'categorie',
        'tags',
        'auteur_id',
        'status',
        'date_publication',
        'meta_description',
        'temps_lecture',
        'vues',
        'featured',
        'ordre_affichage'
    ];

    protected $casts = [
        'tags' => 'array',
        'date_publication' => 'datetime',
        'featured' => 'boolean',
        'vues' => 'integer',
        'temps_lecture' => 'integer'
    ];

    /**
     * Relations
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('date_publication', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('categorie', $category);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('date_publication', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('vues', 'desc');
    }

    /**
     * Mutators
     */
    public function setTitreAttribute($value)
    {
        $this->attributes['titre'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Accessors
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getExcerptAttribute()
    {
        return $this->resume ?: Str::limit(strip_tags($this->contenu), 150);
    }

    public function getReadingTimeAttribute()
    {
        if ($this->temps_lecture) {
            return $this->temps_lecture;
        }
        
        $words = str_word_count(strip_tags($this->contenu));
        return max(1, ceil($words / 200)); // Assuming 200 words per minute
    }

    /**
     * Methods
     */
    public function incrementViews()
    {
        $this->increment('vues');
    }

    public function isPublished()
    {
        return $this->status === 'published' && $this->date_publication <= now();
    }

    public function canBeViewed()
    {
        return $this->isPublished();
    }

    /**
     * Categories disponibles
     */
    public static function getCategories()
    {
        return [
            'annonce' => 'Annonce Importante',
            'success' => 'Histoire de Succès',
            'evenement' => 'Événements',
            'partenariat' => 'Partenariats',
            'formation' => 'Formations',
            'conseil' => 'Conseils',
            'interview' => 'Interviews',
            'actualite' => 'Actualités Générales'
        ];
    }

    /**
     * Status disponibles
     */
    public static function getStatuses()
    {
        return [
            'draft' => 'Brouillon',
            'published' => 'Publié',
            'archived' => 'Archivé'
        ];
    }
} 
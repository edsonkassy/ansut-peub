<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoGibberishText implements ValidationRule
{
    protected $minWords;
    protected $maxRepeatingChars;

    public function __construct(int $minWords = 5, int $maxRepeatingChars = 5)
    {
        $this->minWords = $minWords;
        $this->maxRepeatingChars = $maxRepeatingChars;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        // Nettoyer le texte
        $text = trim($value);
        
        // 1. Vérifier si le texte contient trop de caractères répétitifs
        if ($this->hasExcessiveRepeatingChars($text)) {
            $fail("Le champ :attribute contient trop de caractères répétitifs.");
            return;
        }

        // 2. Vérifier si le texte contient suffisamment de mots valides
        if (!$this->hasMinimumValidWords($text)) {
            $fail("Le champ :attribute doit contenir au moins {$this->minWords} mots valides.");
            return;
        }

        // 3. Vérifier le ratio de consonnes/voyelles (gibberish a souvent un ratio anormal)
        if (!$this->hasValidConsonantVowelRatio($text)) {
            $fail("Le contenu du champ :attribute semble invalide.");
            return;
        }

        // 4. Vérifier la présence d'espaces (texte valide doit avoir des espaces)
        if (!$this->hasProperSpacing($text)) {
            $fail("Le champ :attribute doit contenir des phrases correctement espacées.");
            return;
        }
    }

    /**
     * Vérifier s'il y a trop de caractères répétitifs
     */
    private function hasExcessiveRepeatingChars(string $text): bool
    {
        // Compter les séquences de caractères identiques
        $repeatingPattern = '/(.)\1{' . ($this->maxRepeatingChars - 1) . ',}/';
        return preg_match($repeatingPattern, $text);
    }

    /**
     * Vérifier s'il y a suffisamment de mots valides
     */
    private function hasMinimumValidWords(string $text): bool
    {
        // Extraire les mots (lettres uniquement, minimum 2 caractères)
        preg_match_all('/[a-zA-ZÀ-ÿ]{2,}/', $text, $matches);
        $words = $matches[0];

        if (count($words) < $this->minWords) {
            return false;
        }

        // Vérifier que les mots ne sont pas juste des répétitions
        $validWords = 0;
        foreach ($words as $word) {
            if (!$this->isRepeatingPattern($word)) {
                $validWords++;
            }
        }

        return $validWords >= $this->minWords;
    }

    /**
     * Vérifier si un mot est juste une répétition de caractères
     */
    private function isRepeatingPattern(string $word): bool
    {
        $length = strlen($word);
        if ($length < 3) {
            return false;
        }

        // Vérifier si le mot est composé principalement du même caractère
        $charCounts = array_count_values(str_split(strtolower($word)));
        $maxCount = max($charCounts);
        
        return ($maxCount / $length) > 0.7; // Plus de 70% du même caractère
    }

    /**
     * Vérifier le ratio consonnes/voyelles
     */
    private function hasValidConsonantVowelRatio(string $text): bool
    {
        $text = strtolower($text);
        $letters = preg_replace('/[^a-zA-ZÀ-ÿ]/', '', $text);
        
        if (strlen($letters) < 10) {
            return true; // Texte trop court pour être analysé
        }

        $vowels = preg_match_all('/[aeiouyàáâãäåæèéêëìíîïòóôõöøùúûüÿ]/', $letters);
        $totalLetters = strlen($letters);
        
        if ($totalLetters === 0) {
            return false;
        }

        $vowelRatio = $vowels / $totalLetters;
        
        // Ratio normal entre 0.2 et 0.6 pour le français
        return $vowelRatio >= 0.1 && $vowelRatio <= 0.7;
    }

    /**
     * Vérifier l'espacement approprié
     */
    private function hasProperSpacing(string $text): bool
    {
        $textLength = strlen($text);
        if ($textLength < 20) {
            return true; // Texte trop court
        }

        $spaceCount = substr_count($text, ' ');
        $spaceRatio = $spaceCount / $textLength;

        // Un texte normal doit avoir entre 10% et 20% d'espaces
        return $spaceRatio >= 0.08 && $spaceRatio <= 0.25;
    }
}
<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\Opportunite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompatibilityController extends Controller
{
    /**
     * Calculer le score de compatibilité entre un bachelier et une opportunité
     */
    public function calculateScore(Request $request, Opportunite $opportunite)
    {
        $request->validate([
            'opportunite_id' => 'required|exists:opportunites,id'
        ]);

        $bachelier = Auth::user()->bachelier;
        
        if (!$bachelier) {
            return response()->json([
                'error' => 'Profil bachelier non trouvé'
            ], 404);
        }

        $score = $this->calculateCompatibilityScore($bachelier, $opportunite);
        
        return response()->json([
            'score' => $score['total'],
            'details' => $score['details'],
            'explanation' => $score['explanation'],
            'recommendations' => $score['recommendations']
        ]);
    }

    /**
     * Calculer un score de compatibilité détaillé
     */
    private function calculateCompatibilityScore($bachelier, $opportunite)
    {
        $score = 0;
        $details = [];
        $recommendations = [];

        // 1. Série BAC (20 points max)
        if ($opportunite->series_acceptees && $bachelier->serie_bac) {
            $seriesAcceptees = is_array($opportunite->series_acceptees) 
                ? $opportunite->series_acceptees 
                : explode(',', $opportunite->series_acceptees);
                
            if (in_array($bachelier->serie_bac, $seriesAcceptees)) {
                $score += 20;
                $details['serie_bac'] = [
                    'points' => 20,
                    'status' => 'excellent',
                    'message' => 'Votre série BAC correspond parfaitement'
                ];
            } else {
                $details['serie_bac'] = [
                    'points' => 0,
                    'status' => 'incompatible',
                    'message' => 'Votre série BAC ne correspond pas aux critères'
                ];
                $recommendations[] = 'Cette opportunité est réservée aux séries: ' . implode(', ', $seriesAcceptees);
            }
        }

        // 2. Moyenne BAC (20 points max)
        if ($opportunite->moyenne_minimum && $bachelier->note_bac) {
            if ($bachelier->note_bac >= $opportunite->moyenne_minimum) {
                $ecart = $bachelier->note_bac - $opportunite->moyenne_minimum;
                $points = min(20, 10 + $ecart); // 10 points de base + bonus pour dépassement
                $score += $points;
                $details['moyenne_bac'] = [
                    'points' => $points,
                    'status' => $ecart > 2 ? 'excellent' : 'bon',
                    'message' => "Votre moyenne ({$bachelier->note_bac}) dépasse le minimum requis ({$opportunite->moyenne_minimum})"
                ];
            } else {
                $details['moyenne_bac'] = [
                    'points' => 0,
                    'status' => 'insuffisant',
                    'message' => "Votre moyenne ({$bachelier->note_bac}) est inférieure au minimum requis ({$opportunite->moyenne_minimum})"
                ];
                $recommendations[] = "Une moyenne minimum de {$opportunite->moyenne_minimum} est requise";
            }
        }

        // 3. Localisation (15 points max)
        if ($opportunite->regions_ciblees && $bachelier->region) {
            $regionsCiblees = is_array($opportunite->regions_ciblees) 
                ? $opportunite->regions_ciblees 
                : explode(',', $opportunite->regions_ciblees);
                
            if (in_array($bachelier->region, $regionsCiblees) || in_array('Toutes les régions', $regionsCiblees)) {
                $score += 15;
                $details['localisation'] = [
                    'points' => 15,
                    'status' => 'excellent',
                    'message' => 'Votre région est ciblée par cette opportunité'
                ];
            } else {
                $score += 5; // Points partiels
                $details['localisation'] = [
                    'points' => 5,
                    'status' => 'partiel',
                    'message' => 'Votre région n\'est pas prioritaire mais candidature possible'
                ];
            }
        }

        // 4. Compétences (25 points max)
        if ($opportunite->competences_requises && $bachelier->competences) {
            $competencesRequises = is_array($opportunite->competences_requises) 
                ? $opportunite->competences_requises 
                : explode(',', $opportunite->competences_requises);
            $competencesBachelier = is_array($bachelier->competences) 
                ? $bachelier->competences 
                : explode(',', $bachelier->competences);

            $competencesRequises = array_map('trim', array_map('strtolower', $competencesRequises));
            $competencesBachelier = array_map('trim', array_map('strtolower', $competencesBachelier));
            
            $competencesCorrespondantes = array_intersect($competencesRequises, $competencesBachelier);
            $tauxCorrespondance = count($competencesCorrespondantes) / count($competencesRequises);
            $pointsCompetences = min(25, round($tauxCorrespondance * 25));
            
            $score += $pointsCompetences;
            $details['competences'] = [
                'points' => $pointsCompetences,
                'status' => $tauxCorrespondance > 0.7 ? 'excellent' : ($tauxCorrespondance > 0.4 ? 'bon' : 'partiel'),
                'message' => count($competencesCorrespondantes) . '/' . count($competencesRequises) . ' compétences correspondent',
                'matching' => $competencesCorrespondantes,
                'missing' => array_diff($competencesRequises, $competencesBachelier)
            ];

            if (count($details['competences']['missing']) > 0) {
                $recommendations[] = 'Compétences manquantes: ' . implode(', ', $details['competences']['missing']);
            }
        }

        // 5. Niveau d'étude (10 points max)
        if ($opportunite->niveau_etude_requis && $bachelier->niveau_etude_actuel) {
            $niveauxHierarchie = [
                'Baccalauréat' => 1,
                'BTS/DUT' => 2,
                'Licence' => 3,
                'Master' => 4,
                'Doctorat' => 5
            ];
            
            $niveauRequis = $niveauxHierarchie[$opportunite->niveau_etude_requis] ?? 0;
            $niveauBachelier = $niveauxHierarchie[$bachelier->niveau_etude_actuel] ?? 0;
            
            if ($niveauBachelier >= $niveauRequis) {
                $score += 10;
                $details['niveau_etude'] = [
                    'points' => 10,
                    'status' => 'excellent',
                    'message' => 'Votre niveau d\'étude correspond'
                ];
            } else {
                $details['niveau_etude'] = [
                    'points' => 0,
                    'status' => 'insuffisant',
                    'message' => 'Niveau d\'étude insuffisant'
                ];
                $recommendations[] = "Niveau requis: {$opportunite->niveau_etude_requis}";
            }
        }

        // 6. Bonus motivation et profil (10 points max)
        $bonusScore = 0;
        if ($bachelier->note_bac >= 14) $bonusScore += 3;
        if ($bachelier->langues) {
            $langues = is_array($bachelier->langues) ? $bachelier->langues : explode(',', $bachelier->langues);
            if (count($langues) > 1) $bonusScore += 3;
        }
        if ($bachelier->experiences_professionnelles) $bonusScore += 4;
        
        $score += $bonusScore;
        if ($bonusScore > 0) {
            $details['bonus'] = [
                'points' => $bonusScore,
                'status' => 'bon',
                'message' => 'Points bonus pour votre profil'
            ];
        }

        // Score final et explication
        $finalScore = min(100, $score);
        
        $explanation = '';
        if ($finalScore >= 80) {
            $explanation = 'Excellent match ! Votre profil correspond parfaitement à cette opportunité.';
        } elseif ($finalScore >= 65) {
            $explanation = 'Bon match. Votre profil présente de nombreux points de compatibilité.';
        } elseif ($finalScore >= 50) {
            $explanation = 'Match correct. Quelques améliorations peuvent renforcer votre candidature.';
        } else {
            $explanation = 'Match partiel. Mettez en avant vos atouts dans votre lettre de motivation.';
        }

        return [
            'total' => $finalScore,
            'details' => $details,
            'explanation' => $explanation,
            'recommendations' => $recommendations
        ];
    }
}
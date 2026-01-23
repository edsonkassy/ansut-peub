<?php

namespace App\Helpers;

use App\Models\Bachelier;
use Illuminate\Support\Facades\Log;

class PeubScoringHelper
{
    /**
     * Points par mention au BAC
     */
    private const MENTION_POINTS = [
        'tres_bien' => 40,
        'bien' => 30,
        'assez_bien' => 20,
        'passable' => 10,
    ];

    /**
     * Points par série BAC
     */
    private const SERIE_POINTS = [
        'C' => 10, // Scientifique (Maths, Physique)
        'E' => 9,  // Technique (Maths, Technologie)
        'D' => 7,  // Scientifique (SVT, Maths)
        'A1' => 6, // Littéraire (Maths + Langues)
        'A2' => 4, // Littéraire (Langues, Histoire, Géo)
        'F1' => 8, // Techniques industrielles
        'F2' => 8,
        'F3' => 8,
        'F4' => 6, // Techniques tertiaires / génie civil
        'F5' => 6,
        'F6' => 6,
        'F7' => 6,
        'F8' => 6,
        'G1' => 4, // Gestion, comptabilité, secrétariat
        'G2' => 4,
        'G3' => 4,
        'BT' => 7, // Brevets techniques
        'BP' => 7,
    ];

    /**
     * Points par type d'établissement
     */
    private const ETABLISSEMENT_POINTS = [
        'public' => 20,
        'prive_non_homologue' => 15,
        'prive_homologue' => 5,
    ];

    /**
     * Points par profession des parents
     */
    private const PROFESSION_POINTS = [
        'cadres_professions_intellectuelles' => 0,
        'administration_services' => 2,
        'employes_bureau' => 3,
        'ouvriers_qualifies_artisans' => 5,
        'travailleurs_agricoles_pecheurs' => 6,
        'travailleurs_non_qualifies' => 8,
        'sans_emploi_informel' => 10,
    ];

    /**
     * Points par région (basé sur le classement du document)
     */
    private const REGION_POINTS = [
        'Bafing' => 88,
        'Folon' => 85,
        'Béré' => 83,
        'Bounkani' => 81,
        'Kabadougou' => 79,
        'Bagoué' => 77,
        'Tchologo' => 76,
        'Worodougou' => 75,
        'Poro' => 74,
        'Gontougo' => 73,
        'Hambol' => 70,
        'Cavally' => 69,
        'Guémon' => 68,
        'Tonkpi' => 67,
        'Marahoué' => 66,
        'Nawa' => 64,
        'Iffou' => 64,
        'Moronou' => 63,
        'Nzi' => 61,
        'Lôh-Djiboua' => 59,
        'Sud-Comoé' => 55,
        'San-Pédro' => 53,
        'Gbôklé' => 53,
        'Grands-Ponts' => 51,
        'Gôh' => 51,
        'Haut-Sassandra' => 50,
        'Bélier' => 49,
        'Agnéby-Tiassa' => 43,
        'La Mé' => 43,
        'Gbêkê' => 42,
        'Indénié-Djuablin' => 42,
        'Yamoussoukro' => 32,
        'Abidjan' => 14,
    ];

    /**
     * Calcule le score académique (30% du score final)
     */
    public static function calculateAcademicScore(Bachelier $bachelier): array
    {
        $score = 0;
        $details = [];

        // Points pour la mention
        if ($bachelier->mention && isset(self::MENTION_POINTS[$bachelier->mention])) {
            $mentionPoints = self::MENTION_POINTS[$bachelier->mention];
            $score += $mentionPoints;
            $details['mention'] = [
                'mention' => $bachelier->mention,
                'points' => $mentionPoints
            ];
        }

        // Points pour la série BAC
        if ($bachelier->serie_bac && isset(self::SERIE_POINTS[$bachelier->serie_bac])) {
            $seriePoints = self::SERIE_POINTS[$bachelier->serie_bac];
            $score += $seriePoints;
            $details['serie'] = [
                'serie' => $bachelier->serie_bac,
                'points' => $seriePoints
            ];
        }

        // Points pour le type d'établissement
        if ($bachelier->etablissement_type && isset(self::ETABLISSEMENT_POINTS[$bachelier->etablissement_type])) {
            $etablissementPoints = self::ETABLISSEMENT_POINTS[$bachelier->etablissement_type];
            $score += $etablissementPoints;
            $details['etablissement'] = [
                'type' => $bachelier->etablissement_type,
                'points' => $etablissementPoints
            ];
        }

        return [
            'score' => $score,
            'details' => $details
        ];
    }

    /**
     * Calcule le score géographique (30% du score final)
     */
    public static function calculateGeographicScore(Bachelier $bachelier): array
    {
        $score = 0;
        $details = [];

        // Points pour la région
        if ($bachelier->region && isset(self::REGION_POINTS[$bachelier->region])) {
            $regionPoints = self::REGION_POINTS[$bachelier->region];
            $score += $regionPoints;
            $details['region'] = [
                'region' => $bachelier->region,
                'points' => $regionPoints
            ];
        }

        return [
            'score' => $score,
            'details' => $details
        ];
    }

    /**
     * Calcule le score socio-économique (30% du score final)
     */
    public static function calculateSocioEconomicScore(Bachelier $bachelier): array
    {
        $score = 0;
        $details = [];

        // Points pour la bourse scolaire
        if (!$bachelier->bourse_scolaire_lycee) {
            $score += 10;
            $details['bourse_scolaire'] = [
                'boursier' => false,
                'points' => 10
            ];
        }

        // Points pour l'internat
        if (!$bachelier->pensionnaire_internat) {
            $score += 10;
            $details['internat'] = [
                'pensionnaire' => false,
                'points' => 10
            ];
        }

        // Points pour la profession du père
        if ($bachelier->profession_pere && isset(self::PROFESSION_POINTS[$bachelier->profession_pere])) {
            $professionPoints = self::PROFESSION_POINTS[$bachelier->profession_pere];
            $score += $professionPoints;
            $details['profession_pere'] = [
                'profession' => $bachelier->profession_pere,
                'points' => $professionPoints
            ];
        }

        // Points pour la profession de la mère
        if ($bachelier->profession_mere && isset(self::PROFESSION_POINTS[$bachelier->profession_mere])) {
            $professionPoints = self::PROFESSION_POINTS[$bachelier->profession_mere];
            $score += $professionPoints;
            $details['profession_mere'] = [
                'profession' => $bachelier->profession_mere,
                'points' => $professionPoints
            ];
        }

        // Bonus pour situations particulières
        if ($bachelier->situations_particulieres) {
            $situations = $bachelier->situations_particulieres;
            if (in_array('handicap', $situations)) {
                $score += 5;
                $details['handicap'] = ['points' => 5];
            }
            if (in_array('orphelin', $situations)) {
                $score += 5;
                $details['orphelin'] = ['points' => 5];
            }
        }

        return [
            'score' => $score,
            'details' => $details
        ];
    }

    /**
     * Calcule le score motivations & ambitions (10% du score final)
     */
    public static function calculateMotivationsScore(Bachelier $bachelier): array
    {
        $score = 0;
        $details = [];

        if ($bachelier->motivation) {
            // Analyse basique de la lettre de motivation
            $motivation = strtolower($bachelier->motivation);
            
            // Alignement avec la vision PEUB (4 points)
            $peubKeywords = ['peub', 'excellence', 'développement', 'national', 'élite', 'académique'];
            $peubCount = 0;
            foreach ($peubKeywords as $keyword) {
                if (str_contains($motivation, $keyword)) {
                    $peubCount++;
                }
            }
            if ($peubCount >= 2) {
                $score += 4;
                $details['alignement_peub'] = ['points' => 4];
            }

            // Clarté des motivations personnelles (2 points)
            $personalKeywords = ['je', 'moi', 'mon', 'ma', 'mes', 'ambition', 'objectif', 'rêve'];
            $personalCount = 0;
            foreach ($personalKeywords as $keyword) {
                if (str_contains($motivation, $keyword)) {
                    $personalCount++;
                }
            }
            if ($personalCount >= 3) {
                $score += 2;
                $details['motivations_personnelles'] = ['points' => 2];
            }

            // Impact social ou collectif (2 points)
            $socialKeywords = ['communauté', 'société', 'pays', 'côte d\'ivoire', 'développer', 'aider', 'contribuer'];
            $socialCount = 0;
            foreach ($socialKeywords as $keyword) {
                if (str_contains($motivation, $keyword)) {
                    $socialCount++;
                }
            }
            if ($socialCount >= 2) {
                $score += 2;
                $details['impact_social'] = ['points' => 2];
            }

            // Qualité de la rédaction (2 points)
            $wordCount = str_word_count($motivation);
            if ($wordCount >= 100) {
                $score += 2;
                $details['qualite_redaction'] = ['points' => 2, 'mots' => $wordCount];
            }
        }

        return [
            'score' => $score,
            'details' => $details
        ];
    }

    /**
     * Calcule le score final PEUB
     */
    public static function calculateFinalScore(Bachelier $bachelier): array
    {
        $academic = self::calculateAcademicScore($bachelier);
        $geographic = self::calculateGeographicScore($bachelier);
        $socioEconomic = self::calculateSocioEconomicScore($bachelier);
        $motivations = self::calculateMotivationsScore($bachelier);

        // Normalisation des scores (min-max scaling)
        $maxAcademic = 70; // 40 + 10 + 20
        $maxGeographic = 88; // Maximum des points régionaux
        $maxSocioEconomic = 40; // 10 + 10 + 10 + 10
        $maxMotivations = 10; // 4 + 2 + 2 + 2

        $normalizedAcademic = ($academic['score'] / $maxAcademic) * 100;
        $normalizedGeographic = ($geographic['score'] / $maxGeographic) * 100;
        $normalizedSocioEconomic = ($socioEconomic['score'] / $maxSocioEconomic) * 100;
        $normalizedMotivations = ($motivations['score'] / $maxMotivations) * 100;

        // Calcul du score final pondéré
        $finalScore = (0.3 * $normalizedAcademic) + 
                     (0.3 * $normalizedGeographic) + 
                     (0.3 * $normalizedSocioEconomic) + 
                     (0.1 * $normalizedMotivations);

        return [
            'score_final' => round($finalScore, 2),
            'scores_composants' => [
                'academique' => [
                    'score_brut' => $academic['score'],
                    'score_normalise' => round($normalizedAcademic, 2),
                    'details' => $academic['details']
                ],
                'geographique' => [
                    'score_brut' => $geographic['score'],
                    'score_normalise' => round($normalizedGeographic, 2),
                    'details' => $geographic['details']
                ],
                'socio_economique' => [
                    'score_brut' => $socioEconomic['score'],
                    'score_normalise' => round($normalizedSocioEconomic, 2),
                    'details' => $socioEconomic['details']
                ],
                'motivations' => [
                    'score_brut' => $motivations['score'],
                    'score_normalise' => round($normalizedMotivations, 2),
                    'details' => $motivations['details']
                ]
            ]
        ];
    }

    /**
     * Calcule et sauvegarde le score pour un bachelier
     */
    public static function calculateAndSaveScore(Bachelier $bachelier): array
    {
        try {
            $scoringResult = self::calculateFinalScore($bachelier);
            
            // Désactiver les events temporairement pour éviter la boucle infinie avec l'Observer
            $bachelier->withoutEvents(function () use ($bachelier, $scoringResult) {
                $bachelier->score_academique = $scoringResult['scores_composants']['academique']['score_normalise'];
                $bachelier->score_geographique = $scoringResult['scores_composants']['geographique']['score_normalise'];
                $bachelier->score_socio_economique = $scoringResult['scores_composants']['socio_economique']['score_normalise'];
                $bachelier->score_motivations = $scoringResult['scores_composants']['motivations']['score_normalise'];
                $bachelier->score_final_peub = $scoringResult['score_final'];
                $bachelier->details_scoring = $scoringResult['scores_composants'];
                $bachelier->date_calcul_scoring = now();
                $bachelier->save();
            });

            return $scoringResult;
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul du score PEUB', [
                'bachelier_id' => $bachelier->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }


    /**
     * Sélectionne les 2000 meilleurs candidats avec équité de genre
     */
    public static function selectTop2000Candidates(): array
    {
        $candidates = Bachelier::whereNotNull('score_final_peub')
            ->orderBy('score_final_peub', 'desc')
            ->get();

        $selected = [];
        $countByGender = ['M' => 0, 'F' => 0];
        $countByRegion = [];

        foreach ($candidates as $candidate) {
            // Vérifier la limite de 2000
            if (count($selected) >= 2000) {
                break;
            }

            // Équité de genre (50% filles, 50% garçons)
            $gender = $candidate->sexe;
            if ($countByGender[$gender] >= 1000) {
                continue;
            }

            // Équité territoriale (quota par région)
            $region = $candidate->region;
            if (!isset($countByRegion[$region])) {
                $countByRegion[$region] = 0;
            }

            // Limite par région (à ajuster selon les besoins)
            $maxPerRegion = 200; // Exemple
            if ($countByRegion[$region] >= $maxPerRegion) {
                continue;
            }

            $selected[] = $candidate;
            $countByGender[$gender]++;
            $countByRegion[$region]++;
        }

        return $selected;
    }
} 
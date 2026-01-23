<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bachelier>
 */
class BachelierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->bachelier(),
            'nom' => fake()->lastName(),
            'prenoms' => fake()->firstName(),
            'date_naissance' => fake()->dateTimeBetween('-25 years', '2020-12-31'),
            'lieu_naissance' => fake()->city(),
            'sexe' => fake()->randomElement(['M', 'F']),
            'piece_identite_type' => fake()->randomElement(['carte_scolaire', 'cni', 'attestation']),
            'piece_identite_file' => 'pieces_identite/test.jpg',
            'telephone_eleve' => '+225' . fake()->numerify('07########'),
            'telephone_parent' => '+225' . fake()->numerify('05########'),
            'email_eleve' => fake()->unique()->safeEmail(),
            'email_parent' => fake()->unique()->safeEmail(),
            'region' => fake()->randomElement([
                'Abidjan', 'Yamoussoukro', 'Agnéby‑Tiassa', 'Bafing', 'Bagoué', 'Bélier', 'Béré', 'Bounkani',
                'Cavally', 'Folon', 'Gbêkê', 'Gbôklé', 'Gôh', 'Gontougo', 'Grands‑Ponts', 'Guémon', 'Hambol',
                'Haut‑Sassandra', 'Iffou', 'Indénié‑Djuablin', 'Kabadougou', 'La Mé', 'LôhDjiboua', 'Marahoué',
                'Moronou', 'Nawa', 'Nzi', 'Poro', 'San‑Pédro', 'Sud‑Comoé', 'Tchologo', 'Tonkpi', 'Worodougou'
            ]),
            'commune' => fake()->city(),
            'matricule_bac' => fake()->unique()->numerify('##########'),
            'serie_bac' => fake()->randomElement(['A1', 'A2', 'C', 'D', 'E', 'F1', 'F2', 'F3', 'F4', 'G1', 'G2', 'G3']),
            'note_bac' => fake()->randomFloat(2, 100, 380),
            'mention' => fake()->randomElement(['passable', 'assez_bien', 'bien', 'tres_bien']),
            'etablissement_nom' => fake()->company() . ' Lycée',
            'etablissement_type' => fake()->randomElement(['public', 'prive_homologue', 'prive_non_homologue']),
            'collante_bac_file' => 'collantes_bac/test.jpg',
            'annee_bac' => fake()->numberBetween(2020, 2024),
            'pensionnaire_internat' => fake()->boolean(20),
            'bourse_scolaire_lycee' => fake()->boolean(30),
            'profession_pere' => fake()->randomElement([
                'cadres_professions_intellectuelles',
                'administration_services',
                'employes_bureau',
                'ouvriers_qualifies_artisans',
                'travailleurs_agricoles_pecheurs',
                'travailleurs_non_qualifies',
                'sans_emploi_informel'
            ]),
            'profession_mere' => fake()->randomElement([
                'cadres_professions_intellectuelles',
                'administration_services',
                'employes_bureau',
                'ouvriers_qualifies_artisans',
                'travailleurs_agricoles_pecheurs',
                'travailleurs_non_qualifies',
                'sans_emploi_informel'
            ]),
            'situations_particulieres' => fake()->optional()->randomElements(['handicap', 'orphelin', 'autre'], fake()->numberBetween(0, 2)),
            'possede_ordinateur' => fake()->boolean(30),
            'connexion_internet' => fake()->randomElement(['aucune', '3g_4g', 'fibre']),
            'acces_smartphone' => fake()->boolean(80),
            'acces_ia' => fake()->boolean(20),
            'motivation' => fake()->paragraphs(3, true),
            'boursier_peub' => false,
            'date_integration_peub' => null,
            'status_candidature' => 'en_attente',
            'status_profil' => 'complet',
            'date_verification' => null,
            'bio' => fake()->optional()->paragraph(),
            'competences' => fake()->optional()->randomElements(['Informatique', 'Langues', 'Mathématiques', 'Sciences', 'Arts'], fake()->numberBetween(1, 3)),
            'langues' => fake()->optional()->randomElements(['Français', 'Anglais', 'Espagnol', 'Allemand'], fake()->numberBetween(1, 2)),
            'photo' => fake()->optional()->imageUrl(),
            'cv_path' => fake()->optional()->filePath(),
            'photo_profil' => fake()->optional()->imageUrl(),
        ];
    }

    /**
     * Indicate that the bachelier is a PEUB scholar.
     */
    public function boursier(): static
    {
        return $this->state(fn (array $attributes) => [
            'boursier_peub' => true,
            'date_integration_peub' => now(),
            'status_candidature' => 'accepte',
        ]);
    }

    /**
     * Indicate that the bachelier has excellent academic results.
     */
    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'mention' => 'tres_bien',
            'serie_bac' => 'C',
            'note_bac' => fake()->randomFloat(2, 300, 380),
            'etablissement_type' => 'public',
        ]);
    }

    /**
     * Indicate that the bachelier is from a disadvantaged region.
     */
    public function regionDefavorisee(): static
    {
        return $this->state(fn (array $attributes) => [
            'region' => fake()->randomElement(['Bafing', 'Folon', 'Béré', 'Bounkani', 'Kabadougou']),
        ]);
    }

    /**
     * Indicate that the bachelier has socio-economic difficulties.
     */
    public function situationDifficile(): static
    {
        return $this->state(fn (array $attributes) => [
            'bourse_scolaire_lycee' => false,
            'pensionnaire_internat' => false,
            'profession_pere' => 'sans_emploi_informel',
            'profession_mere' => 'travailleurs_non_qualifies',
            'possede_ordinateur' => false,
            'connexion_internet' => 'aucune',
        ]);
    }
}

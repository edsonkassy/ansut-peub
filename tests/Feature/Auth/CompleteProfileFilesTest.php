<?php

namespace Tests\Feature\Auth;

use App\Models\Bachelier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompleteProfileFilesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['openai.api_key' => 'test-key']);
    }

    private function makeUser(): User
    {
        return User::create([
            'email' => 'eleve@example.test',
            'provider' => 'google',
            'provider_id' => 'test-123',
            'role' => 'bachelier',
            'status' => 'pending',
            'email_verified_at' => now(),
        ]);
    }

    private function profileData(): array
    {
        return [
            'nom' => 'KOUASSI',
            'prenoms' => 'Awa',
            'date_naissance' => '2007-05-14',
            'lieu_naissance' => 'Abidjan',
            'sexe' => 'F',
            'piece_identite_type' => 'cni',
            'telephone_eleve' => '+22507000001',
            'telephone_parent' => '+22505000002',
            'email_eleve' => 'eleve@example.test',
            'email_parent' => 'parent@example.test',
            'region' => 'Abidjan',
            'commune' => 'Cocody',
            'matricule_bac' => 'TEST-2026-001',
            'serie_bac' => 'C',
            'note_bac' => 300.5,
            'annee_bac' => 2026,
            'etablissement_nom' => 'Lycée Test',
            'etablissement_type' => 'public',
            'pensionnaire_internat' => false,
            'bourse_scolaire_lycee' => false,
            'profession_pere' => 'non_applicable',
            'profession_mere' => 'non_applicable',
            'connexion_internet' => '3g_4g',
            'possede_ordinateur' => false,
            'acces_smartphone' => true,
            'acces_ia' => false,
            'motivation' => str_repeat('Je souhaite rejoindre PEUB. ', 10),
            'acceptation_carte_publique' => false,
            'mention' => 'assez_bien',
            'piece_identite_file_temp' => 'temp/piece.jpg',
            'collante_bac_file_temp' => 'temp/collante.jpg',
            'photo_profil_temp' => 'temp/photo.jpg',
        ];
    }

    public function test_les_chemins_des_documents_sont_enregistres(): void
    {
        Storage::fake('public');
        Mail::fake();
        Queue::fake();

        foreach (['piece', 'collante', 'photo'] as $name) {
            Storage::disk('public')->put("temp/{$name}.jpg", 'contenu');
        }

        $this->actingAs($this->makeUser())
            ->withSession(['profile_data' => $this->profileData()])
            ->post(route('auth.complete-profile.store'))
            ->assertSessionMissing('error')
            ->assertRedirect(route('dashboard'));

        $bachelier = Bachelier::where('matricule_bac', 'TEST-2026-001')->first();
        $this->assertNotNull($bachelier, "Le profil bachelier n'a pas été créé.");

        $this->assertSame('documents/pieces_identite/piece.jpg', $bachelier->piece_identite_file);
        $this->assertSame('documents/collantes_bac/collante.jpg', $bachelier->collante_bac_file);
        $this->assertSame('photos/profils/photo.jpg', $bachelier->photo_profil);

        Storage::disk('public')->assertExists([
            'documents/pieces_identite/piece.jpg',
            'documents/collantes_bac/collante.jpg',
            'photos/profils/photo.jpg',
        ]);
        Storage::disk('public')->assertMissing('temp/piece.jpg');
    }

    public function test_le_formulaire_propose_l_annee_2026(): void
    {
        $this->withoutVite();

        $this->actingAs($this->makeUser())
            ->get(route('auth.complete-profile'))
            ->assertOk()
            ->assertSee('value="2026"', false);
    }
}

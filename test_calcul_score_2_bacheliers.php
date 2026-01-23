<?php

// Test d'inscription de 2 bachelières avec calcul automatique des scores
// Ligne 17: KONE Yves Aissata (Passable, Non handicapé, Non orphelin, Féminin)
// Ligne 18: SERY Esther Patrick (Bien, Non handicapé, Orphelin de mère, Féminin)

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Bachelier;

echo "🎓 TEST DE CALCUL DES SCORES - 2 BACHELIÈRES\n";
echo "============================================\n\n";

// ============================================================================
// BACHELIÈRE 1 : KONE Yves Aissata (Ligne 17)
// ============================================================================
echo "📋 BACHELIÈRE 1 : KONE Yves Aissata\n";
echo "====================================\n";
echo "  Matricule: 23215324F\n";
echo "  Sexe: F (Féminin)\n";
echo "  Série BAC: A1\n";
echo "  Note BAC: 228/400 (57%)\n";
echo "  Mention: Passable\n";
echo "  Région: Tonkpi (Man)\n";
echo "  Établissement: Institut National (Public)\n";
echo "  Handicap: Non\n";
echo "  Orphelin: Non\n\n";

echo "⏳ Vérification si existe déjà...\n";
$existingUser1 = User::where('email', 'kone.yves.23215324f@peub.bj')->first();
if ($existingUser1) {
    echo "⚠️  Existe déjà, suppression...\n";
    $existingBachelier1 = Bachelier::where('user_id', $existingUser1->id)->first();
    if ($existingBachelier1) $existingBachelier1->delete();
    $existingUser1->delete();
}

echo "📝 Création du compte utilisateur...\n";
$user1 = User::create([
    'nom' => 'Kone',
    'prenom' => 'Yves Aissata',
    'email' => 'kone.yves.23215324f@peub.bj',
    'telephone' => '0165713114',
    'password' => bcrypt('password123'),
    'email_verified_at' => now(),
    'user_type' => 'bachelier',
    'status' => 'active',
]);
echo "✅ Utilisateur créé (ID: {$user1->id})\n\n";

echo "🎓 Création du profil bachelier...\n";
echo "   → L'Observer va calculer automatiquement le score\n\n";

$startTime1 = microtime(true);

$bachelier1 = Bachelier::create([
    'user_id' => $user1->id,
    'nom' => 'Kone',
    'prenoms' => 'Yves Aissata',
    'date_naissance' => '2005-08-09',
    'lieu_naissance' => 'Man',
    'sexe' => 'F',
    'telephone_eleve' => '0165713114',
    'telephone_parent' => '0165713114',
    'email_eleve' => 'kone.yves.23215324f@peub.bj',
    'email_parent' => 'parent.kone@email.bj',
    'region' => 'Tonkpi',
    'commune' => 'Man',
    'matricule_bac' => '23215324F',
    'serie_bac' => 'A1',
    'annee_bac' => 2024,
    'note_bac' => 228,
    'mention' => 'passable',
    'etablissement_nom' => 'Institut National',
    'etablissement_type' => 'public',
    'collante_bac_file' => 'collante_23215324F.pdf',
    'situations_particulieres' => [], // Pas de handicap
    'connexion_internet' => 'aucune',
]);

$executionTime1 = round((microtime(true) - $startTime1) * 1000, 2);

echo "✅ Bachelier créé (ID: {$bachelier1->id})\n";
echo "⏱️  Temps d'exécution: {$executionTime1}ms\n\n";

$bachelier1->refresh();

echo "📊 RÉSULTAT DU CALCUL AUTOMATIQUE\n";
echo "==================================\n";
echo "Score Total: " . ($bachelier1->score_final_peub ?? 'NULL') . "/100\n\n";
echo "Détail des 4 composantes:\n";
echo "1. Excellence Académique: " . ($bachelier1->score_academique ?? 'NULL') . "/50\n";
echo "   → Mention Passable devrait donner 10 points\n\n";
echo "2. Handicap: " . ($bachelier1->score_geographique ?? 'NULL') . "/20\n";
echo "   → Non handicapé devrait donner 10 points\n\n";
echo "3. Orphelinat: " . ($bachelier1->score_socio_economique ?? 'NULL') . "/20\n";
echo "   → Non orphelin devrait donner 0 points\n\n";
echo "4. Genre: " . ($bachelier1->score_motivations ?? 'NULL') . "/10\n";
echo "   → Féminin devrait donner 10 points\n\n";

$scoreAttendu1 = 10 + 10 + 0 + 10;
$scoreObtenu1 = $bachelier1->score_final_peub ?? 0;
echo "✅ Score attendu: {$scoreAttendu1}/100\n";
echo ($scoreObtenu1 == $scoreAttendu1 ? "✅" : "❌") . " Score obtenu: {$scoreObtenu1}/100\n\n";

// ============================================================================
// BACHELIÈRE 2 : SERY Esther Patrick (Ligne 18)
// ============================================================================
echo "\n" . str_repeat("=", 60) . "\n\n";
echo "📋 BACHELIÈRE 2 : SERY Esther Patrick\n";
echo "======================================\n";
echo "  Matricule: 98696553M\n";
echo "  Sexe: F (Féminin)\n";
echo "  Série BAC: D\n";
echo "  Note BAC: 311/400 (77.75%)\n";
echo "  Mention: Bien\n";
echo "  Région: Poro (Korhogo)\n";
echo "  Établissement: Ecole Technique (Privé)\n";
echo "  Handicap: Non\n";
echo "  Orphelin: Mère (orphelin de mère)\n\n";

echo "⏳ Vérification si existe déjà...\n";
$existingUser2 = User::where('email', 'sery.esther.98696553m@peub.bj')->first();
if ($existingUser2) {
    echo "⚠️  Existe déjà, suppression...\n";
    $existingBachelier2 = Bachelier::where('user_id', $existingUser2->id)->first();
    if ($existingBachelier2) $existingBachelier2->delete();
    $existingUser2->delete();
}

echo "📝 Création du compte utilisateur...\n";
$user2 = User::create([
    'nom' => 'Sery',
    'prenom' => 'Esther Patrick',
    'email' => 'sery.esther.98696553m@peub.bj',
    'telephone' => '0573208639',
    'password' => bcrypt('password123'),
    'email_verified_at' => now(),
    'user_type' => 'bachelier',
    'status' => 'active',
]);
echo "✅ Utilisateur créé (ID: {$user2->id})\n\n";

echo "🎓 Création du profil bachelier...\n";
echo "   → L'Observer va calculer automatiquement le score\n\n";

$startTime2 = microtime(true);

$bachelier2 = Bachelier::create([
    'user_id' => $user2->id,
    'nom' => 'Sery',
    'prenoms' => 'Esther Patrick',
    'date_naissance' => '2004-12-15',
    'lieu_naissance' => 'Korhogo',
    'sexe' => 'F',
    'telephone_eleve' => '0573208639',
    'telephone_parent' => '0573208639',
    'email_eleve' => 'sery.esther.98696553m@peub.bj',
    'email_parent' => 'parent.sery@email.bj',
    'region' => 'Poro',
    'commune' => 'Korhogo',
    'matricule_bac' => '98696553M',
    'serie_bac' => 'D',
    'annee_bac' => 2024,
    'note_bac' => 311,
    'mention' => 'bien',
    'etablissement_nom' => 'Ecole Technique',
    'etablissement_type' => 'prive_homologue',
    'collante_bac_file' => 'collante_98696553M.pdf',
    'situations_particulieres' => ['orphelin'], // Orphelin de mère
    'connexion_internet' => 'aucune',
]);

$executionTime2 = round((microtime(true) - $startTime2) * 1000, 2);

echo "✅ Bachelier créé (ID: {$bachelier2->id})\n";
echo "⏱️  Temps d'exécution: {$executionTime2}ms\n\n";

$bachelier2->refresh();

echo "📊 RÉSULTAT DU CALCUL AUTOMATIQUE\n";
echo "==================================\n";
echo "Score Total: " . ($bachelier2->score_final_peub ?? 'NULL') . "/100\n\n";
echo "Détail des 4 composantes:\n";
echo "1. Excellence Académique: " . ($bachelier2->score_academique ?? 'NULL') . "/50\n";
echo "   → Mention Bien devrait donner 30 points\n\n";
echo "2. Handicap: " . ($bachelier2->score_geographique ?? 'NULL') . "/20\n";
echo "   → Non handicapé devrait donner 10 points\n\n";
echo "3. Orphelinat: " . ($bachelier2->score_socio_economique ?? 'NULL') . "/20\n";
echo "   → Orphelin de mère devrait donner 15 points\n\n";
echo "4. Genre: " . ($bachelier2->score_motivations ?? 'NULL') . "/10\n";
echo "   → Féminin devrait donner 10 points\n\n";

$scoreAttendu2 = 30 + 10 + 15 + 10;
$scoreObtenu2 = $bachelier2->score_final_peub ?? 0;
echo "✅ Score attendu: {$scoreAttendu2}/100\n";
echo ($scoreObtenu2 == $scoreAttendu2 ? "✅" : "❌") . " Score obtenu: {$scoreObtenu2}/100\n\n";

// ============================================================================
// RÉSUMÉ FINAL
// ============================================================================
echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 RÉSUMÉ DES 2 BACHELIÈRES\n";
echo str_repeat("=", 60) . "\n\n";

echo "1. KONE Yves Aissata (Passable)\n";
echo "   Score: {$scoreObtenu1}/100 " . ($scoreObtenu1 == $scoreAttendu1 ? "✅" : "❌ Attendu: {$scoreAttendu1}") . "\n";
echo "   Composantes: {$bachelier1->score_academique}+{$bachelier1->score_geographique}+{$bachelier1->score_socio_economique}+{$bachelier1->score_motivations}\n\n";

echo "2. SERY Esther Patrick (Bien + Orphelin mère)\n";
echo "   Score: {$scoreObtenu2}/100 " . ($scoreObtenu2 == $scoreAttendu2 ? "✅" : "❌ Attendu: {$scoreAttendu2}") . "\n";
echo "   Composantes: {$bachelier2->score_academique}+{$bachelier2->score_geographique}+{$bachelier2->score_socio_economique}+{$bachelier2->score_motivations}\n\n";

$totalSuccess = ($scoreObtenu1 == $scoreAttendu1 ? 1 : 0) + ($scoreObtenu2 == $scoreAttendu2 ? 1 : 0);
echo str_repeat("=", 60) . "\n";
echo "✅ TESTS RÉUSSIS : {$totalSuccess}/2\n";
echo str_repeat("=", 60) . "\n";


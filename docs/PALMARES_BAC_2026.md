# Palmarès officiel BAC 2026 : vérification, espace admin, carte

Les 2 050 meilleurs bacheliers 2026 (25 filles + 25 garçons par DRENA, 41 DRENA) sont chargés dans une table
de **référence** `palmares_bac`. Ce ne sont **pas** des comptes : ils n'ont ni email ni téléphone. Ils servent à :

1. **Vérifier** qu'un bachelier qui s'inscrit est bien dans la liste officielle (matricule + nom + date de naissance).
2. **Afficher** le palmarès dans l'espace admin (page « Palmarès BAC 2026 », menu Gestion > Bacheliers).
3. **Placer des pastilles** sur la carte de la landing (rose = fille, bleu = garçon ; pleine = inscrit vérifié, pâle = pas encore inscrit).

D'autres bacheliers que ceux du palmarès peuvent s'inscrire : ils sont simplement « non vérifiés ».

## Mise en service
```bash
php artisan migrate
# Déposer le CSV directement sur le serveur (il n'est PAS dans git : données personnelles de mineurs)
#   -> storage/app/imports/palmares_bac_2026.csv
php artisan palmares:import --dry-run    # contrôle sans écrire
php artisan palmares:import              # 2 050 lignes, relançable sans doublon
```
Colonnes du CSV : `matricule,nom,prenoms,date_naissance,lieu_naissance,drena,sexe,serie,mention,etablissement,points,rang_drena,annee_bac`
(`mention` : `ims`, `passable`, `assez_bien`, `bien`, `tres_bien`).

## Fonctionnement de la vérification
À la fin de l'inscription (`SocialAuthController::completeProfile`) :
- matricule trouvé + nom + date de naissance concordants → `bac_verifie = true` ; la note, la série, l'année et la
  **mention officielles remplacent celles déclarées** (empêche de gonfler sa note pour le score PEUB) ;
- matricule trouvé mais nom ou date différents → `bac_verification_statut = divergent` (« à revoir » dans l'admin) ;
- matricule absent → inscription acceptée, profil non vérifié.
La vérification ne bloque jamais l'inscription (erreur journalisée si la table est absente).

## Consentement carte publique
Le nom (« Prénom + initiale ») n'apparaît sur la carte publique que pour les inscrits ayant coché la case de consentement
(`bacheliers.consentement_carte_publique`). Les autres pastilles sont anonymes (« Meilleur bachelier / Meilleure bachelière »).

## Reste à faire (non inclus dans cette PR)
1. **Case de consentement dans le formulaire** `resources/views/auth/complete-profile.blade.php` (fichier de 54 Ko non modifié ici).
   Le contrôleur lit déjà le champ `acceptation_carte_publique`. Sans la case, personne ne consent : aucun nom n'est affiché (comportement sûr).
   ```html
   <label class="flex items-start gap-3 text-sm text-gray-700">
       <input type="checkbox" name="acceptation_carte_publique" value="1"
              {{ old('acceptation_carte_publique', $sessionData['acceptation_carte_publique'] ?? false) ? 'checked' : '' }} class="mt-1">
       <span>J'accepte que mon prénom et l'initiale de mon nom apparaissent sur la carte publique des boursiers PEUB (facultatif).
       Si je suis mineur(e), mon parent ou tuteur y consent. Je peux retirer mon accord à tout moment.</span>
   </label>
   ```
   Prévoir aussi un interrupteur de retrait du consentement dans la page profil.
2. **Barème des mentions** (`Bachelier::calculateMention`), **à faire valider par l'ANSUT** : il est décalé d'un cran par rapport au palmarès.

   | Points /400 | Palmarès officiel | Code actuel |
   |---|---|---|
   | < 200 | IMS | aucune |
   | 200 – 239 | passable | aucune |
   | 240 – 279 | assez bien | passable |
   | 280 – 319 | bien | assez bien |
   | 320 – 359 | très bien | bien |
   | 360 – 400 | très bien | très bien |

   Le score PEUB dépend de la mention (très bien 50 pts, bien 30, autres 10) : les vérifiés (mention officielle) sont aujourd'hui
   avantagés par rapport aux non vérifiés. Correction proposée :
   ```php
   public static function calculateMention(float $note): ?string
   {
       if ($note < 200) { return 'ims'; }
       if ($note < 240) { return 'passable'; }
       if ($note < 280) { return 'assez_bien'; }
       if ($note < 320) { return 'bien'; }
       return 'tres_bien';
   }
   ```
   Les bacheliers déjà inscrits gardent leur ancienne mention tant qu'elle n'est pas recalculée.
3. **Mention IMS dans l'admin bacheliers** (`Admin/BachelierManagementController.php`) : ajouter `'ims' => 'IMS'` au tableau `$mentions`
   (méthode `index`) et `ims` à la règle `mention` de `update()`.
4. Coordonnées des DRENA (`config/drena.php`) : approximatives (chef-lieu), à affiner si besoin.

## Checklist de test (base de développement)
- [ ] `php artisan migrate` passe (la migration `..._000003` convertit `bacheliers.mention` de ENUM en VARCHAR(20) : vérifier sur le SGBD utilisé).
- [ ] `php artisan palmares:import --dry-run` : 2 050 lignes valides, 0 erreur ; puis import réel ; relancer = pas de doublon.
- [ ] Admin : Gestion > Bacheliers > « Palmarès BAC 2026 » affiche 2 050 lignes, filtres et export CSV OK.
- [ ] Landing : la carte affiche « Cohorte 2026 » avec ~2 050 pastilles ; infobulle anonyme ; les cohortes 2023-2025 fonctionnent toujours.
- [ ] Inscription test avec un matricule du fichier (nom + date corrects) : `bac_verifie = true`, note/mention officielles, pastille pleine sur la carte.
- [ ] Inscription test avec nom ou date faux : statut « à revoir ». Avec un matricule hors liste : inscription acceptée, non vérifié.
- [ ] Bac 2026 accepté dans le formulaire d'inscription.

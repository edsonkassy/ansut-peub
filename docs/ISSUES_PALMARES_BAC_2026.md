# Chantier « Palmarès BAC 2026 » : suivi des issues

Projet : `edsonkassy/ansut-peub` · Branche de travail : `feature/palmares-2026` (patch `feature-palmares-2026.patch`, base `develop`)
Document rédigé le 28/09/2026. Chaque issue est écrite pour être copiée telle quelle dans GitHub (titre, labels, description, cases à cocher).

## Contexte en 5 lignes
L'ANSUT a reçu la liste officielle des **2 050 meilleurs bacheliers 2026** (25 filles + 25 garçons pour chacune des 41 DRENA). Ces élèves n'ont ni email ni téléphone : ils ne peuvent donc pas être des comptes. La liste est stockée dans une table de référence `palmares_bac`. Elle sert à **vérifier** les bacheliers qui s'inscrivent, à les **consulter dans l'espace admin**, et à afficher des **pastilles sur la carte** de la landing.

## Décisions déjà prises (ne pas rouvrir sans raison)
| # | Décision | Prise par |
|---|---|---|
| D1 | Les 2 050 restent une table de référence, pas des comptes. Les élèves s'inscrivent eux-mêmes. | Direction / ANSUT |
| D2 | D'autres bacheliers que les 2 050 peuvent s'inscrire ; un matricule hors liste est accepté, profil « non vérifié ». | Direction / ANSUT |
| D3 | `IMS` devient une mention à part entière (moins de 200 points). Les 3 élèves de Minignan sans mention (154 à 158 pts) sont classés `ims` comme les 7 autres. | Direction |
| D4 | Carte publique : « Prénom + initiale » uniquement pour les inscrits ayant coché une case de consentement ; les autres pastilles sont anonymes. | Direction |
| D5 | Quand un élève est vérifié, sa note, sa série, son année et sa mention officielles remplacent celles qu'il a saisies. | Équipe technique |
| D6 | Le CSV du palmarès (noms de mineurs) n'est jamais versionné dans git ; il est déposé directement sur le serveur. | Équipe technique |

## Légende
**Priorité** : P0 bloquant avant mise en ligne · P1 juste après · P2 à planifier · P3 confort.
**Statut** : ☐ à faire · 🟡 livré dans le patch, à tester · ✅ terminé.
**Responsable** : Dev (équipe technique) · ANSUT (décision métier) · Chef de projet.

---

## Vue d'ensemble

| # | Titre | Priorité | Type | Statut | Responsable | Dépend de |
|---|---|---|---|---|---|---|
| 1 | Appliquer le patch et ouvrir la pull request | P0 | Déploiement | ☐ | Dev | : |
| 2 | Tester sur base de développement (migrations, import, parcours) | P0 | Test | ☐ | Dev | 1 |
| 3 | Importer le palmarès en production (CSV hors git) | P0 | Données | ☐ | Dev | 2 |
| 4 | Sécurité : bypass OTP, liste d'emails visible dans le dépôt public (hors chantier) | P2 | Sécurité | ☐ | Dev | - |
| 5 | Ajouter la case de consentement au formulaire d'inscription | P0 | Fonctionnel | ☐ | Dev | 1 |
| 6 | Vérifier rétroactivement les bacheliers déjà inscrits | P0 | Données | ☐ | Dev | 3 |
| 7 | Valider puis corriger le barème des mentions | P1 | Métier + code | ☐ | ANSUT puis Dev | : |
| 8 | Gérer la mention IMS dans l'admin des bacheliers | P1 | Fonctionnel | ☐ | Dev | 2 |
| 9 | Afficher le badge « BAC vérifié » et traiter les dossiers « à revoir » | P1 | Fonctionnel | ☐ | Dev | 2 |
| 10 | Couvrir tous les points de création de profil bachelier (API, admin) | P1 | Fonctionnel | ☐ | Dev | 1 |
| 11 | Faire confirmer le sens de « IMS » par la source | P1 | Métier | ☐ | Chef de projet | : |
| 12 | Vérifier la date limite d'inscription affichée sur la landing | P1 | Contenu | ☐ | Chef de projet | : |
| 13 | Réactiver la validation IA des documents (erreur SSL) | P1 | Sécurité / qualité | ☐ | Dev | : |
| 14 | Cadre RGPD : conservation et accès aux données du palmarès | P1 | Juridique | ☐ | ANSUT + Chef de projet | : |
| 15 | Tests automatisés de la vérification, de l'import et de la carte | P2 | Qualité | ☐ | Dev | 2 |
| 16 | Affiner les coordonnées des DRENA sur la carte | P2 | Fonctionnel | ☐ | Dev | : |
| 17 | Rendre l'année du bac dynamique dans le formulaire | P2 | Maintenance | ☐ | Dev | : |
| 18 | Décider du sort du fichier manquant `boursiers-data.blade.php` | P2 | Maintenance | 🟡 (atténué) | Dev | : |
| 19 | Performance et cache de la carte | P3 | Performance | ☐ | Dev | 3 |
| 20 | Nettoyage de la dette technique repérée à la lecture | P2 | Maintenance | ☐ | Dev | : |
| 21 | Déploiement automatique sur `main` : migrations manuelles et dernier déploiement en échec | P0 | Déploiement | ☐ | Dev | 1 |
| 22 | Jeton Mapbox visible dans le dépôt public (restriction, migration des 2 autres fichiers) | P1 | Sécurité | ☐ | Dev | - |
| 23 | Aligner les clés régionales entre RegionHelper et PeubScoringHelper (points géographiques manquants) | P1 | Métier + code | ☐ | ANSUT puis Dev | : |

**Déjà livré dans le patch (à tester, issues 1 à 3)** : table `palmares_bac`, commande d'import, service de vérification, page admin « Palmarès BAC 2026 » avec lien dans le menu, carte 2026 (2 050 pastilles), mention `ims`, bac 2026 autorisé.

---

## Détail des issues

### #1 Appliquer le patch et ouvrir la pull request
**Labels** : `deploiement` `P0`
Le connecteur GitHub de l'assistant n'a pas de droit d'écriture sur le dépôt (erreur 403) : le travail est livré sous forme de patch.
- [ ] `git checkout develop && git pull`
- [ ] `git checkout -b feature/palmares-2026`
- [ ] `git am feature-palmares-2026.patch`
- [ ] `git push -u origin feature/palmares-2026`
- [ ] Ouvrir la pull request vers `develop` (20 fichiers modifiés ou ajoutés, 993 lignes ajoutées)
- [ ] Relecture par un second développeur
**Critère d'acceptation** : la pull request est ouverte, sans conflit, et la CI (si elle existe) passe.
**Note** : `main` a un commit d'avance sur `develop` (changement d'images de la landing). Le patch s'applique proprement sur les deux.

### #2 Tester sur base de développement
**Labels** : `test` `P0`
- [ ] `php artisan migrate` passe. Vérifier en particulier la migration `..._000003_allow_ims_mention_on_bacheliers.php` (elle transforme `bacheliers.mention` d'ENUM en VARCHAR(20)) sur le SGBD réellement utilisé (MySQL, SQL Server ou PostgreSQL : le dépôt contient des fichiers SQL pour deux d'entre eux).
- [x] `php artisan palmares:import --dry-run` : 2 050 lignes valides, 0 erreur (SQLite et MySQL).
- [x] `php artisan palmares:import` puis relance : pas de doublon (2 050 lignes exactement) (SQLite ; MySQL : import unique, 1 025 F / 1 025 M, mentions conformes).
- [ ] Admin : Gestion > Bacheliers > « Palmarès BAC 2026 » affiche 2 050 lignes ; filtres (DRENA, sexe, série, mention, inscription) et export CSV fonctionnent.
- [ ] Landing : la carte affiche « Cohorte 2026 » avec environ 2 050 pastilles ; l'infobulle est anonyme ; les cohortes 2023 à 2025 fonctionnent toujours.
- [ ] Inscription test avec un matricule du fichier, nom et date exacts : `bac_verifie = true`, note et mention officielles, pastille pleine sur la carte.
- [ ] Inscription test avec nom ou date de naissance faux : statut « à revoir ».
- [ ] Inscription test avec un matricule hors liste : inscription acceptée, profil non vérifié.
- [ ] Bac 2026 accepté par le formulaire.
- [x] Le code PHP a été exécuté en local (SQLite et MySQL) : `verify()` (vérifié, divergent, absent, dates jj/mm/aaaa) et `markBachelier` validés ; `route:cache` passe.
- Reste à faire : parcours navigateur (OAuth Google, formulaire, case de consentement cochée puis décochée, page admin, carte) sur staging, et `migrate --pretend` sur le schéma réel de production.
- Constat : le dépôt ne reconstruit pas la base sur MySQL (voir #20) ; la production a été construite autrement.

### #3 Importer le palmarès en production
**Labels** : `donnees` `P0`
**Statut** : en attente de l'accès au serveur de l'ANSUT ; suivre `docs/DEPLOIEMENT_PALMARES.md`.
- [ ] Récupérer `palmares_bac_2026.csv` (fourni avec le patch) et le transférer par un canal sûr, sans passer par git ni par email.
- [ ] Le déposer sur le serveur : `storage/app/imports/palmares_bac_2026.csv`.
- [ ] `php artisan palmares:import --dry-run`, puis `php artisan palmares:import`.
- [ ] Contrôler : 2 050 lignes, 41 DRENA, 1 025 filles et 1 025 garçons, 10 lignes en mention `ims`.
- [ ] Supprimer ou archiver de façon sécurisée le CSV du serveur une fois l'import validé (voir #14).
**Note** : `storage/app/imports/` et `database/data/*.csv` sont ajoutés au `.gitignore`.

### #4 Sécurité : bypass OTP (hors chantier, à décider séparément)
**Labels** : `securite` `P2`
**Contexte** : le bypass OTP de certains comptes est voulu et reste en place, hors de ce chantier. Point de vigilance à traiter séparément : la liste des emails est codée en dur dans un dépôt public, donc lisible par tous. Piste : liste dans le .env du serveur et interrupteur explicite pour la production.

### #5 Case de consentement dans le formulaire d'inscription
**Labels** : `fonctionnel` `rgpd` `P0`
Le contrôleur lit déjà le champ `acceptation_carte_publique`, mais le formulaire (`resources/views/auth/complete-profile.blade.php`, 54 Ko) n'a pas été modifié. Sans la case, personne ne consent et aucun nom n'apparaît sur la carte (comportement sûr, mais incomplet).
- [x] Ajouter la case **facultative**, non cochée par défaut, dans le formulaire (fait, version simplifiée : sans `old()` ni mention du parent ; règle `nullable|boolean` ajoutée à la prévisualisation). Modèle initial :
  ```html
  <label class="flex items-start gap-3 text-sm text-gray-700">
      <input type="checkbox" name="acceptation_carte_publique" value="1"
             {{ old('acceptation_carte_publique', $sessionData['acceptation_carte_publique'] ?? false) ? 'checked' : '' }} class="mt-1">
      <span>J'accepte que mon prénom et l'initiale de mon nom apparaissent sur la carte publique des boursiers PEUB (facultatif).
      Si je suis mineur(e), mon parent ou tuteur y consent. Je peux retirer mon accord à tout moment.</span>
  </label>
  ```
- [ ] Faire valider le texte par l'ANSUT (formulation légale, mention du parent ou tuteur).
- [ ] Reprendre la valeur dans le formulaire si l'élève revient de la prévisualisation (la valeur survit en session, mais la case n'est pas re-cochée). À tester sur staging.
- [ ] Ajouter dans la page profil un interrupteur « Afficher mon nom sur la carte » qui met `consentement_carte_publique` à faux (retrait du consentement) et vide le cache de la carte.
**Critère d'acceptation** : un élève qui coche la case voit son « Prénom I. » sur la carte ; un élève qui ne coche pas reste anonyme ; le retrait est effectif en moins de 10 minutes.

### #6 Vérifier rétroactivement les bacheliers déjà inscrits
**Labels** : `donnees` `P0`
**Statut** : non commencé ; dépend de l'import en production (#3) et de l'accès au serveur.
La vérification ne se déclenche qu'à l'inscription. Tout bachelier inscrit **avant** l'import du palmarès n'est pas vérifié, même si son matricule est dans la liste.
- [ ] Créer une commande `palmares:verifier-existants` qui parcourt les `bacheliers` non vérifiés, appelle `PalmaresVerificationService::verify()` et enregistre le résultat (`markBachelier`).
- [ ] Décider pour les vérifiés : appliquer aussi la note, la série et la mention officielles, puis **recalculer le score PEUB** (le recalcul automatique ne se relance pas seul, il faut forcer).
- [ ] Mode `--dry-run` qui liste ce qui changerait, avant toute écriture.
- [ ] Exécuter après l'import (#3) et journaliser les cas « à revoir ».
**Critère d'acceptation** : tout bachelier existant dont le matricule, le nom et la date de naissance concordent est marqué vérifié.

### #7 Valider puis corriger le barème des mentions
**Labels** : `metier` `bug` `P1`
**Problème** : `Bachelier::calculateMention()` est décalé d'un cran par rapport à la liste officielle.

| Points /400 | Palmarès officiel | Code actuel |
|---|---|---|
| moins de 200 | IMS | aucune |
| 200 à 239 | passable | aucune |
| 240 à 279 | assez bien | passable |
| 280 à 319 | bien | assez bien |
| 320 à 359 | très bien | bien |
| 360 à 400 | très bien | très bien |

Le score PEUB dépend de la mention (très bien 50 points, bien 30, autres 10) : les élèves vérifiés (mention officielle) sont aujourd'hui avantagés par rapport aux non vérifiés à niveau égal.
- [ ] L'ANSUT confirme le tableau ci-dessus (le palmarès fait foi ; aucune ligne du fichier n'est incohérente avec ce barème).
- [ ] Corriger `calculateMention()` :
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
- [ ] Corriger le commentaire de la méthode (il décrit l'ancien barème).
- [ ] Recalculer la mention **puis** le score des bacheliers déjà inscrits (le calcul automatique ne force pas le recalcul quand un score existe déjà).
- [ ] Communiquer le changement (les classements PEUB peuvent bouger).

### #8 Mention IMS dans l'admin des bacheliers
**Labels** : `fonctionnel` `P1`
- [ ] `Admin/BachelierManagementController.php` : ajouter `'ims' => 'IMS'` au tableau `$mentions` (méthode `index`).
- [ ] Même fichier, méthode `update()` : ajouter `ims` à la règle `mention`.
- [ ] Vérifier l'affichage des badges de mention dans les vues admin, bachelier et partenaire, et les ressources API.
- [ ] Vérifier que le formulaire d'édition admin ne vide pas une mention `ims` à l'enregistrement.
**Note** : le score PEUB traite `ims` comme « autre mention » (10 points), rien à changer de ce côté.

### #9 Badge « BAC vérifié » et dossiers « à revoir »
**Labels** : `fonctionnel` `P1`
Le résultat de la vérification n'est visible aujourd'hui que dans la page Palmarès.
- [ ] Afficher un badge « BAC vérifié » dans la fiche bachelier admin (`admin/bacheliers/show`), la liste admin et le profil du bachelier.
- [ ] Ajouter un filtre « BAC vérifié / à revoir / non vérifié » dans la liste admin des bacheliers.
- [ ] Workflow « à revoir » : sur la fiche, afficher la note de divergence (`bac_verification_note`) et proposer **Accepter avec les données officielles** ou **Rejeter**.
- [ ] Prévoir une notification admin quand un dossier passe en « à revoir ».
**Critère d'acceptation** : un admin peut traiter tous les dossiers « à revoir » sans passer par la base de données.

### #10 Couvrir tous les points de création de profil bachelier
**Labels** : `fonctionnel` `P1`
La vérification est branchée sur `SocialAuthController::completeProfile()` uniquement.
- [ ] Rechercher tous les endroits qui créent un `Bachelier` (routes `api.php`, contrôleurs `Api/`, création côté admin, seeders, imports).
- [ ] Brancher `PalmaresVerificationService` partout où un profil peut être créé, ou déplacer la logique dans un point unique (Observer ou service commun).
- [ ] Vérifier le cas de la connexion sociale (Google, etc.) qui passe par le même formulaire.

### #11 Faire confirmer le sens de « IMS » par la source
**Labels** : `metier` `P1`
Dix élèves, tous de la DRENA de Minignan (154 à 179 points), sont enregistrés en `ims`. Sept portaient cette mention dans le fichier ; trois n'en avaient aucune.
- [ ] Demander à la source (ministère ou DRENA) la signification exacte de « IMS ».
- Vérifié en base (SQLite et MySQL) : les 10 `ims` ont entre 154 et 179 points, les 3 élèves sans mention en font partie.
- [ ] Confirmer que les 3 élèves sans mention (matricules dans le CSV source, non reproduits ici : dépôt public) doivent bien être classés `ims`.
- [ ] Ajuster le libellé affiché dans l'admin si besoin (aujourd'hui « IMS »).

### #12 Date limite d'inscription affichée sur la landing
**Labels** : `contenu` `P1`
Le bouton de la section « Nos boursiers » affiche « S'inscrire avant le 30 Sept. », en dur dans `landing/partials/boursiers.blade.php`. Nous sommes le 28/09/2026.
- [ ] Confirmer avec l'ANSUT la date limite réelle de la campagne 2026.
- [ ] Mettre à jour le texte, ou le rendre configurable pour ne plus le coder en dur.

### #13 Réactiver la validation IA des documents
**Labels** : `securite` `qualite` `P1`
Dans `SocialAuthController::showPreview()`, la validation par l'IA de la pièce d'identité et de la collante du bac est **désactivée** (« Erreur SSL sur le serveur »). Aujourd'hui, n'importe quelle image est acceptée comme document.
- [ ] Diagnostiquer l'erreur SSL du serveur (certificats de l'autorité racine, version de cURL/OpenSSL, accès sortant vers l'API IA).
- [ ] Réactiver les appels à `AiExtractionService::validateDocument`.
- [ ] Recouper les données extraites de la collante avec le palmarès (matricule, note) quand le bachelier y figure.
**Lien** : avec #6 et la vérification, c'est la seconde barrière contre les faux dossiers.

### #14 Cadre RGPD : conservation et accès
**Labels** : `juridique` `P1`
**Statut** : accord de l'ANSUT reçu sur l'affichage « Prénom + initiale » (consentement parental recueilli par la DECO). Conserver la trace écrite. Restent : conservation, accès, politique de confidentialité.
La table `palmares_bac` contient noms, dates de naissance et établissements de 2 050 élèves, dont des mineurs, et une partie n'a pas encore de compte.
- [ ] Définir la base légale et la durée de conservation avec l'ANSUT.
- [ ] Limiter l'accès à la page Palmarès aux administrateurs autorisés (le patch exige la permission `users.bacheliers.view`) et tracer les exports CSV.
- [ ] Décider de la conservation du fichier CSV source (suppression du serveur, archive chiffrée).
- [ ] Vérifier que la carte publique ne publie que : prénom + initiale (avec consentement), série, DRENA et rang.
- [ ] Mettre à jour la politique de confidentialité (`privacy.blade.php`).

### #15 Tests automatisés
**Labels** : `qualite` `P2`
Le dossier `tests/` ne contient que `TestCase.php`.
- [ ] Tests unitaires de `PalmaresVerificationService` : `verifie`, `divergent`, `absent`, normalisation des noms (accents, tirets, noms composés), formats de date, remplacement des données officielles.
- [ ] Test de la commande `palmares:import` : fichier valide, ligne invalide, relance sans doublon.
- [ ] Test du point d'entrée `/landing/cohorte/2026` : anonymat par défaut, nom affiché seulement avec consentement, statut inscrit.
- [ ] Brancher l'exécution des tests dans la CI.

### #16 Coordonnées des DRENA
**Labels** : `fonctionnel` `P2`
`config/drena.php` contient le chef-lieu approximatif de chaque DRENA. Les 4 DRENA d'Abidjan et les 2 de Bouaké sont simplement décalées pour ne pas se superposer.
- [ ] Obtenir le découpage réel des DRENA d'Abidjan (1 à 4) et de Bouaké (1 et 2).
- [ ] Ajuster les coordonnées, contrôler visuellement la carte.
- [ ] Vérifier que les pastilles restent bien dans le pays (le décalage est de 0,03 à 0,13 degré autour du centre).

### #17 Année du bac dynamique
**Labels** : `maintenance` `P2`
La règle `annee_bac` est maintenant limitée à 2026 en dur (dans `showPreview` et `completeProfile`). Elle refusera 2027 l'an prochain.
- [ ] Remplacer par une valeur calculée (année en cours) ou un paramètre de configuration.
- [ ] Aligner la liste déroulante du formulaire `complete-profile.blade.php`.
- [ ] Mettre à jour les messages d'erreur.

### #18 Fichier manquant `boursiers-data.blade.php`
**Labels** : `maintenance` `P2`
La landing incluait `landing.partials.boursiers-data` (données des cohortes 2023 à 2025), qui n'est **pas dans le dépôt**. Le patch remplace l'inclusion par `@includeIf` et protège le code : la landing ne plante plus, mais les anciennes cohortes s'affichent vides si le fichier n'existe pas.
- [ ] Vérifier si le fichier existe sur les serveurs (récupérer sa version de production).
- [ ] Soit le versionner dans git, soit remplacer ces données par une source en base.
- [ ] Retirer les données personnelles éventuelles avant de le versionner.

### #19 Performance et cache de la carte
**Labels** : `performance` `P3`
- [ ] Mesurer la taille de la réponse `/landing/cohorte/2026` (environ 2 050 points) et activer la compression gzip.
- [ ] Le cache dure 10 minutes : décider s'il faut l'invalider dès qu'un élève est vérifié ou retire son consentement.
- [ ] Tester le rendu sur mobile et sur connexion lente.
- [ ] Le jeton Mapbox est codé en dur dans la vue : le déplacer dans `.env` et le restreindre à l'adresse du site côté Mapbox.

### #20 Nettoyage de la dette technique
**Labels** : `maintenance` `P2`
Points repérés pendant la lecture du dépôt, sans lien direct avec le palmarès :
- [ ] Supprimer les contrôleurs `old_BachelierManagementController.php` et `old_BoursierController.php`, ainsi que la vue `old_index.blade.php`.
- [ ] Sortir de la racine les scripts ad hoc (`create_admin.php`, `test-openai.php`, `test_calcul_score_2_bacheliers.php`) ou les transformer en vrais tests.
- [ ] Choisir entre `nginx-peub.conf` et `nginx-peub-secured.conf` et supprimer l'autre.
- [ ] Vérifier que les comptes de démonstration du `BachelierSeeder` (adresses en `@example.com`) n'existent pas en production ; sinon les supprimer.
- [ ] Harmoniser les valeurs de `status_profil` (la migration prévoit `incomplet/complet/verifie`, l'admin utilise aussi `en_attente/rejete`).
- [ ] Remplacer `MODIFICATIONS_LOG.txt` par l'historique git et des notes de version.
- [ ] **Migrations incompatibles MySQL** (le dépôt ne peut pas reconstruire la base depuis zéro ; constaté lors d'un `migrate:fresh` sur MySQL, alors que SQLite les accepte). À corriger dans une PR séparée, sans effet sur la production déjà migrée :
  - [ ] `0001_01_01_000000_create_users_table` : retirer les `->after()` dans le `Schema::create`.
  - [ ] `2025_06_19_112332_create_partenaire_opportunite_types_table` : nom d'index unique trop long (66 caractères, limite 64).
  - [ ] `2025_09_10_183037_create_library_resources_table` : index `library_category_id, is_active, published_at` trop long (68).
  - [ ] `2025_09_10_183044_create_library_comments_table` : index `library_resource_id, is_approved, created_at` trop long (65).
  - [ ] `2025_09_10_183048_create_library_likes_table` : unique à 4 colonnes trop long (74).
  - Correctif : retirer `after` et donner un nom court en second argument de `index()` / `unique()`.
- [ ] Ajouter un `.env.example` au dépôt (absent aujourd'hui).

### #21 Déploiement automatique sur `main` : migrations manuelles et dernier déploiement en échec
**Labels** : `deploiement` `P0`
Constats (fichier `.github/workflows/deploy.yml`) :
- Chaque **push sur `main`** déclenche un déploiement en production : connexion SSH, `git pull origin main`, `composer install`, puis mise en cache de la configuration, des vues et des routes. Une branche ou une pull request vers `develop` ne déploie rien.
- Le déploiement **n'exécute pas `php artisan migrate`**. Après la fusion vers `main`, les nouvelles tables et colonnes n'existent pas tant que les migrations ne sont pas lancées à la main. Sans elles, la page admin Palmarès et les données de la carte renvoient une erreur 500 (l'inscription reste protégée : la vérification ne bloque jamais le parcours).
- Le dernier commit de `main` (`dd4fec2`) affiche une **croix rouge** sur GitHub : le déploiement (ou une vérification) a échoué.
- Le workflow se place dans `/var/www/peub` alors que le script `deploy.sh` se place dans `/var/www/ansut-peub-v0` : deux chemins de production différents.
- [ ] Ouvrir l'onglet **Actions** de GitHub, lire l'erreur du dernier lancement et la corriger.
- [ ] Confirmer quel dossier du serveur est réellement la production, et aligner workflow et script.
- [ ] Prévoir une sauvegarde de la base avant la première migration en production.
- [ ] Le jour de la mise en ligne : fusionner, puis lancer `php artisan migrate --force` sur le serveur, vérifier `php artisan route:list --path=palmares`, puis importer le palmarès (#3).
- [ ] Optionnel : ajouter `php artisan migrate --force` au workflow, seulement après validation d'une sauvegarde automatique.
- [ ] **Protéger `main`** (Settings > Branches) : pull request obligatoire avant fusion, car tout push sur `main` part en production.
- [ ] `config:cache` est lancé par le workflow **avant** toute modification manuelle : `MAPBOX_PUBLIC_TOKEN` doit être dans le `.env` de production AVANT la fusion, sinon relancer `php artisan config:cache`.
- [ ] Le workflow ne redémarre pas les workers de file : si le serveur en utilise, lancer `php artisan queue:restart` après le déploiement.
- [ ] Entre le `git pull` et le `migrate` manuel, le nouveau code tourne sur l'ancienne base : enchaîner la migration dès que l'onglet Actions est vert, hors heures de pointe.
**Critère d'acceptation** : le dernier déploiement sur `main` est vert, et la procédure de mise en ligne (fusion, migration, import) est écrite et testée sur un environnement de test.

---

### #22 Jeton Mapbox visible dans le dépôt public
**Labels** : `securite` `P1`
Un jeton Mapbox (préfixe `pk.`, donc public par conception) était écrit en dur dans `resources/views/landing/partials/boursiers.blade.php`. GitHub l'a signalé comme « Mapbox Secret Access Token ». Il est sorti de ce fichier (lecture de `config('services.mapbox.public_token')`, variable `MAPBOX_PUBLIC_TOKEN`), mais il reste dans l'historique de `develop` et dans deux autres fichiers :
- `resources/views/admin/boursiers/index.blade.php`
- `resources/views/partenaire/analytics.blade.php`
- [ ] Dans le compte Mapbox, vérifier les scopes du jeton : uniquement les scopes publics par défaut, aucun scope secret.
- [ ] Ajouter des restrictions d'URL (domaine de production, et `http://localhost` pour le test local).
- [ ] Définir `MAPBOX_PUBLIC_TOKEN` dans le `.env` de production **avant** la fusion dans `main` (le déploiement lance `config:cache`).
- [ ] Si les scopes ne sont pas propres ou si le jeton n'est pas restreint : créer un nouveau jeton restreint, migrer les deux fichiers ci-dessus vers la config, puis supprimer l'ancien jeton après vérification en production.
**Critère d'acceptation** : le jeton en circulation est restreint aux URL du site, sans scope secret, et aucune valeur de jeton n'est écrite dans le code.

---

### #23 Aligner les clés régionales entre RegionHelper et PeubScoringHelper (points géographiques manquants)
**Labels** : `metier` `bug` `P1`
**Découvert le** : 29/09/2026, pendant le portage de `RegionHelper.php` du chantier de réconciliation (`docs/ISSUES_RECONCILIATION_RECETTE.md`, issue #2).

**Problème** : 7 clés de `PeubScoringHelper::REGION_POINTS` ne correspondent à aucune clé de `RegionHelper::getRegions()` :
- 5 régions utilisent un tiret ASCII simple dans `PeubScoringHelper` là où `RegionHelper` (et le reste de l'application) utilise un tiret insécable : Sud-Comoé, Grands-Ponts, Haut-Sassandra, Agnéby-Tiassa, Indénié-Djuablin.
- 2 régions ont en plus un nom différent : `Lôh-Djiboua` (PeubScoringHelper) contre `LôhDjiboua` (RegionHelper) ; `San-Pédro`, dont le tiret insécable est le seul écart.

**Conséquence** : les bacheliers de ces 7 régions ne reçoivent actuellement **aucun point géographique** dans le score PEUB, faute de correspondance de clé.

**Le conflit à trancher** : l'issue #2 du chantier de réconciliation indiquait initialement qu'aligner les chaînes de caractères entre les deux fichiers pouvait se faire sans attendre d'arbitrage, tant que le barème de points lui-même n'était pas modifié. Dans les faits, ces deux consignes se contredisent : aligner les clés **redonne mécaniquement** ces points aux bacheliers concernés, ce qui revient à corriger le barème. Le palmarès BAC 2026 étant déjà en ligne sur `develop`, ce changement modifierait un classement déjà visible.

- [ ] L'ANSUT / Mamadou confirme que les bacheliers de ces 7 régions doivent bien recevoir leurs points géographiques (correction d'un bug plutôt qu'un changement de règle).
- [ ] Une fois confirmé : remplacer les 7 clés de `PeubScoringHelper::REGION_POINTS` par les valeurs exactes de `RegionHelper::getRegions()` (tirets insécables, `LôhDjiboua` sans tiret).
- [ ] Recalculer le score des bacheliers déjà inscrits dont la région fait partie des 7 concernées (le recalcul automatique ne se relance pas seul).
- [ ] Communiquer le changement si le classement bouge de façon visible.
**Critère d'acceptation** : les 41 régions/DRENA de `RegionHelper` et de `PeubScoringHelper` utilisent exactement les mêmes clés, et les bacheliers concernés reçoivent leurs points géographiques.

---

## Ordre de travail recommandé

**Avant l'ouverture aux vrais bacheliers (P0)** : #1 → #2 → #21 → #5 → #3 → #6 (la #4, bypass OTP, est passée en P2 et traitée hors de ce chantier).
**Dans la foulée (P1)** : #7, #11 et #23 (décisions métier à lancer dès maintenant, elles sont indépendantes du code), puis #8, #9, #10, #12, #13, #14.
**Ensuite (P2 / P3)** : #15 à #20.

## Commandes utiles
```bash
php artisan migrate
php artisan palmares:import --dry-run
php artisan palmares:import
php artisan route:list --path=palmares      # doit lister admin/palmares, admin/palmares/export et landing/cohorte/{annee}
php artisan config:clear && php artisan cache:clear
```

## Pour créer les issues dans GitHub
Chaque section ci-dessus peut être collée comme une issue (titre, labels, corps). Si le connecteur GitHub reçoit un accès en écriture sur le dépôt, l'assistant peut créer les 22 issues, leurs labels et un jalon « Palmarès BAC 2026 » automatiquement.

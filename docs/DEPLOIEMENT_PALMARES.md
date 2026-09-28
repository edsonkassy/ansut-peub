# Déploiement du palmarès BAC 2026

Le déploiement automatique (push sur `main`) **ne lance pas `php artisan migrate`**. Les étapes ci-dessous sont manuelles.

## 0. Prérequis (avant la fusion dans main)
- [ ] Pull request relue et fusionnée dans `develop`.
- [ ] Test sur staging avec une copie de la base de production (voir section 1).
- [x] Réponse de l'ANSUT sur l'affichage des prénoms de mineurs : accord reçu (conserver la trace écrite). La case reste facultative.
- [ ] Jeton Mapbox restreint aux URL du site (compte Mapbox).
- [ ] Sauvegarde complète de la base de production (section 2).
- [ ] `MAPBOX_PUBLIC_TOKEN` déjà présent dans le `.env` de production AVANT la fusion : le déploiement lance `config:cache`, qui fige la configuration.
- [ ] Le workflow déploie dans `/var/www/peub` : vérifier que c'est bien le dossier servi par nginx. Ignorer `deploy.sh`, qui cible un autre dossier.

## 1. Test sur staging
1. Restaurer une copie de la base de production sur un environnement séparé.
2. `php artisan migrate --pretend` : relire le SQL, surtout la migration `allow_ims_mention_on_bacheliers` (ENUM vers VARCHAR).
3. `php artisan migrate`.
4. Copier le CSV dans `storage/app/imports/`, puis `php artisan palmares:import --dry-run`, puis `php artisan palmares:import`.
5. Parcours navigateur : inscription avec un matricule du palmarès, case cochée puis décochée. Vérifier en base `consentement_carte_publique`, `bac_verifie`, `bac_verification_statut`.
6. Vérifier que les bacheliers déjà inscrits sont inchangés (comptage avant/après).
7. Ouvrir la page d'accueil : la carte s'affiche avec le vrai jeton Mapbox.

## 2. Sauvegarde (production)
Sauvegarder la base avant toute migration (mysqldump ou outil de l'hébergeur). Noter l'emplacement du fichier et vérifier qu'il n'est pas vide. Ne jamais le placer dans le dépôt.

## 3. Configuration du serveur
Dans le `.env` de production :
```
MAPBOX_PUBLIC_TOKEN=<jeton public restreint>
```
Sans cette variable, la carte utilise un faux jeton et ne s'affiche pas.

## 4. Déploiement
1. Fusionner `develop` dans `main` de préférence hors heures de pointe. Le workflow fait : `git pull`, `composer install --no-dev`, puis `config:cache`, `view:cache`, `route:cache`. Il ne lance PAS `migrate`.
   Entre le pull et votre `migrate`, le nouveau code tourne sur l'ancienne base : enchaînez l'étape 2 dès que l'onglet **Actions** est vert.
2. Sur le serveur, dans le dossier de l'application :
   - `git log -1` : vérifier que le dernier commit est le bon.
   - `php artisan migrate --pretend` : relire.
   - `php artisan migrate --force`.
3. Copier le CSV depuis votre poste (hors git) :
   `scp palmares_bac_2026.csv <utilisateur>@<serveur>:<dossier>/storage/app/imports/`
4. `php artisan palmares:import --dry-run` : attendu 2050 lignes valides, 0 erreur.
5. `php artisan palmares:import` : attendu 2050 bacheliers en base.
6. Si `MAPBOX_PUBLIC_TOKEN` a été ajouté après le déploiement : `php artisan config:cache`.
7. Si des workers de file tournent sur le serveur : `php artisan queue:restart`.
8. Retirer le CSV du serveur une fois l'import contrôlé (données personnelles de mineurs).

## 5. Contrôles après déploiement
- Base : 2050 lignes dans `palmares_bac` (1025 F / 1025 M), 10 en mention `ims`.
- Page d'accueil : la carte 2026 s'affiche.
- Admin : page « Palmarès BAC 2026 » accessible et export CSV fonctionnel.
- Inscription test (compte jetable, à supprimer ensuite).
- Logs : aucune erreur nouvelle dans `storage/logs`.

## 6. Retour arrière
- Restaurer la sauvegarde de la base (méthode la plus sûre).
- Ne pas s'appuyer sur `migrate:rollback` : le retour de `mention` en ENUM peut échouer ou perdre la valeur `ims`.
- Revenir au commit précédent de `main`.

# Chantier « Réconciliation recette/ui-ux → develop » : suivi des issues

Projet : `edsonkassy/ansut-peub` · Point de départ commun : `0c310e8` (6 mai 2026)
Document rédigé le 29/09/2026. Numérotation propre à ce document (`#1` à `#9`), indépendante de celle du chantier Palmarès BAC 2026 (`docs/ISSUES_PALMARES_BAC_2026.md`, `#1` à `#22`).

## Constat en 5 lignes
Depuis le 6 mai 2026, `develop` et le travail réel du serveur de recette ont avancé chacun de leur côté sans jamais se recroiser. `develop` a reçu le système de design, quatre lots de refonte de l'espace bachelier, une seconde refonte début septembre, puis cette semaine le palmarès BAC 2026 et deux correctifs admin partenaires. Le serveur de recette a reçu, en parallèle et hors git, 32 fichiers de modifications manuelles (formulaire d'inscription en deux étapes, correctifs mobiles, refontes de pages, jeton Mapbox lu depuis la config), archivées a posteriori dans la branche `recette/ui-ux`. Résultat : certains fichiers ont été corrigés deux fois, différemment, des deux côtés.

## Décisions de méthode (à valider avant de commencer)
| # | Décision proposée | À valider par |
|---|---|---|
| M1 | `develop` reste la seule référence. Le travail de la recette est **porté** dedans, jamais l'inverse. | Équipe technique |
| M2 | Aucune fusion automatique (`git merge`) sur les fichiers en conflit : chaque fichier du groupe 2 et 3 est relu à la main avant décision. | Équipe technique |
| M3 | Une fois la réconciliation fusionnée dans `develop`, un seul déploiement contrôlé remplace le contenu du serveur. Jusque-là, le serveur actuel n'est pas touché. | Chef de projet |
| M4 | À partir de cette réconciliation, plus aucune modification de code directement sur le serveur : toute correction passe par une branche et une pull request. | Chef de projet |

## Légende
**Priorité** : P0 bloquant avant la prochaine mise en ligne · P1 juste après · P2 à planifier · P3 confort.
**Statut** : ☐ à faire · 🟡 en cours / partiellement vérifié · ✅ terminé.
**Responsable** : Dev (équipe technique) · Chef de projet.

---

## Vue d'ensemble

| # | Titre | Priorité | Groupe | Statut | Responsable | Dépend de |
|---|---|---|---|---|---|---|
| 1 | Vérifier l'hypothèse du logo avant de trancher le groupe 2 | P1 | Diagnostic | ☐ | Dev | - |
| 2 | Porter RegionHelper.php dans develop | P1 | Groupe 1 | ☐ | Dev | - |
| 3 | Porter BoursierController.php et admin/boursiers/index.blade.php | P1 | Groupe 1 | ☐ | Dev | - |
| 4 | Porter faq.blade.php et les petits fichiers admin isolés | P2 | Groupe 1 | ☐ | Dev | - |
| 5 | Réconcilier PartenaireManagementController.php | P0 | Groupe 3 | ☐ | Dev | - |
| 6 | Réappliquer le correctif d'inscription dans le formulaire en 2 étapes | P0 | Groupe 3 | ☐ | Dev | 1 |
| 7 | Réconcilier les fichiers de la refonte UI (forum, library, inbox, layouts, landing, mobile) | P1 | Groupe 2 | ☐ | Dev | 1 |
| 8 | Déploiement contrôlé : remplacer le contenu du serveur par develop réconcilié | P0 | Déploiement | ☐ | Dev + Chef de projet | 2,3,4,5,6,7 |
| 9 | Interdire les modifications directes sur le serveur à l'avenir | P1 | Processus | ☐ | Chef de projet | 8 |

---

## Détail des issues

### #1 Vérifier l'hypothèse du logo avant de trancher le groupe 2
**Labels** : `diagnostic` `P1`
Les fichiers du groupe 2 (`app.css`, `mobile-gestures.js`, `app.js`, `layouts/app.blade.php`, `layouts/guest.blade.php`, `components/opportunites-nav.blade.php`, forum, library, inbox, landing) ont chacun un diff minuscule côté recette (2 à 4 lignes), sur des fichiers que `develop` a aussi réécrits en profondeur (lots UI d'août, refonte de septembre). Le commit de septembre sur `develop` signalait explicitement que le logo ANSUT restait affiché trop petit dans sept autres vues, dont la navigation et le pied de page.
- [ ] Comparer le contenu de 2 ou 3 de ces petits diffs (`git show recette/ui-ux:<fichier>` vs `develop`) pour savoir s'il s'agit du correctif de logo, d'un ajustement de couleur obsolète (antérieur au système de design d'août), ou d'autre chose.
- [ ] Si c'est le correctif de logo : le porter dans `develop` plutôt que de l'écraser.
- [ ] Si c'est obsolète (couleur en dur remplacée depuis par les rôles du système de design) : documenter la décision de l'écarter, ne pas le porter.
**Critère d'acceptation** : chaque petit diff du groupe 2 a une décision écrite (porté / écarté), pas de suppression silencieuse.

### #2 Porter RegionHelper.php dans develop
**Labels** : `groupe-1` `donnees` `P1`
La recette a corrigé des coordonnées (Bélier/Toumodi) et ajouté des villes. Un point à ne pas reprendre : l'entrée `'Dictionnaire'`, qui est une erreur. Des doublons existent aussi (Tengréla/Tingréla) et dans `mapOldRegionToNew`.
- [ ] Comparer `RegionHelper.php` sur `recette/ui-ux` et `develop`, ligne par ligne.
- [ ] Reprendre les coordonnées corrigées et les villes ajoutées, à l'exclusion de `'Dictionnaire'`.
- [ ] Dédupliquer les entrées répétées.
- [ ] Vérifier au passage la correspondance des noms de régions entre `RegionHelper` (tiret insécable) et `PeubScoringHelper` (tiret simple) — problème repéré séparément, qui fait perdre des points géographiques à plusieurs bacheliers. Ne pas corriger les points sans l'arbitrage de Mamadou, mais aligner les chaînes de caractères peut se faire dès maintenant.
**Critère d'acceptation** : `RegionHelper::getRegions()` ne contient plus `'Dictionnaire'` ni de doublon, et sert la même liste de régions que `PeubScoringHelper`.

### #3 Porter BoursierController.php et admin/boursiers/index.blade.php
**Labels** : `groupe-1` `fonctionnel` `P1`
La recette a refondu `getBoursiersWithCoordinates` : passage de points par commune à un regroupement par région avec compteurs filles/garçons, avec une réduction de 985 lignes sur la vue associée.
- [ ] Comparer les deux versions du contrôleur.
- [ ] Porter le regroupement par région dans `develop`.
- [ ] Porter la vue associée, testée dans un navigateur (pas seulement en lecture de code).
**Critère d'acceptation** : la page admin des boursiers affiche le regroupement par région avec les compteurs, testé visuellement.

### #4 Porter faq.blade.php et les petits fichiers admin isolés
**Labels** : `groupe-1` `contenu` `P2`
Fichiers sans recoupement connu avec le travail de `develop` : `faq.blade.php` (réécriture complète), `admin/analytics.blade.php`, `admin/articles/index.blade.php`, `partenaire/analytics.blade.php`, `welcome.blade.php`, `actualite.blade.php`, `actualites.blade.php`.
- [ ] Relire chaque diff pour confirmer l'absence de recoupement avec un travail récent de `develop`.
- [ ] Porter tel quel.
**Critère d'acceptation** : les 7 fichiers sont dans `develop`, sans régression visible sur les pages concernées.

### #5 Réconcilier PartenaireManagementController.php
**Labels** : `groupe-3` `securite` `P0`
Les deux côtés ont ajouté un `export()` indépendamment :
- `develop` : neutralise les formules Excel dans les champs texte libres, compte les opportunités par `withCount`.
- `recette/ui-ux` : aucune neutralisation de formule (un partenaire malveillant peut injecter une formule dans son nom d'organisation), comptage par chargement complet puis `count()` en PHP.
La recette n'a pas de méthode `verify()` : elle utilise encore `toggleStatus()`, qui fait à peu près la même chose autrement.
- [ ] Garder `export()` et `verify()` de `develop` (plus sûrs, plus efficaces).
- [ ] Vérifier dans `routes/web.php` de la recette si le bouton « Vérifier » de l'admin pointe vers `toggleStatus()` ou vers autre chose.
- [ ] Décider : supprimer `toggleStatus()` une fois `verify()`/`reject()` en place, ou les faire coexister avec un usage distinct.
- [ ] Vérifier qu'aucune vue ne référence encore `toggleStatus()` avant de la supprimer.
**Critère d'acceptation** : une seule implémentation d'`export()` (celle avec neutralisation des formules), le bouton « Vérifier » de l'admin fonctionne, pas de méthode morte.

### #6 Réappliquer le correctif d'inscription dans le formulaire en 2 étapes
**Labels** : `groupe-3` `bug` `P0`
La PR #5 (fusionnée dans `develop`) corrige `Storage::move()` qui renvoyait un booléen au lieu du chemin, et ajoute l'année 2026 — sur la version en une étape du formulaire. La recette a transformé `complete-profile.blade.php` et `SocialAuthController.php` en formulaire à deux étapes ; le même bug `move()` y a été confirmé présent (`git show recette/ui-ux:...` fait tout à l'heure).
- [ ] Lire en entier la version à deux étapes de `SocialAuthController.php` et `complete-profile.blade.php`/`complete-profile-preview.blade.php` sur `recette/ui-ux`.
- [ ] Retrouver l'équivalent des trois appels `move()` dans cette nouvelle structure et les remplacer par `moveTempFile()` (ou l'adapter si la structure des données temporaires a changé).
- [ ] Vérifier si le sélecteur d'année existe encore sous la même forme dans le formulaire à deux étapes, et y ajouter 2026 si besoin.
- [ ] Adapter ou dupliquer `CompleteProfileFilesTest.php` pour qu'il couvre le parcours à deux étapes.
**Dépend de** : #1, pour ne pas refaire ce travail deux fois si le groupe 2 touche aussi ces fichiers.
**Critère d'acceptation** : le test de régression passe sur la version à deux étapes du formulaire, avec les mêmes vérifications que sur la version actuelle de `develop`.

### #7 Réconcilier les fichiers de la refonte UI
**Labels** : `groupe-2` `P1`
Fichiers où `develop` a une refonte plus récente et plus aboutie que le petit correctif de la recette : `app.css`, `mobile-gestures.js`, `app.js`, `layouts/app.blade.php`, `layouts/guest.blade.php`, `components/opportunites-nav.blade.php`, `bachelier/forum/{index,favorites,members}.blade.php`, `bachelier/library/{index,favorites}.blade.php`, `bachelier/inbox/index.blade.php`, `bachelier/opportunites.blade.php`, `landing/partials/{about,hero,boursiers,news}.blade.php`.
- [ ] Pour chaque fichier, appliquer la décision prise dans #1 (porter ou écarter).
- [ ] Pour `mobile-gestures.js` en particulier : la recette avait un correctif rapide du 6 mai pour le même blocage de défilement tactile que `develop` a corrigé en profondeur le 20 août. Confirmer que la version `develop` couvre bien le cas que la recette avait patché avant d'écarter ce dernier.
**Critère d'acceptation** : chaque fichier du groupe a une décision tracée, aucun n'est écrasé sans vérification.

### #8 Déploiement contrôlé : remplacer le contenu du serveur par develop réconcilié
**Labels** : `deploiement` `P0`
Une fois les issues #2 à #7 fusionnées dans `develop` via une branche d'intégration.
- [ ] Sauvegarde de la base de données du serveur de recette (`mysqldump` ou équivalent).
- [ ] Créneau hors heures de pointe, prévenir les utilisateurs si besoin.
- [ ] Vérifier `.env` du serveur (`MAPBOX_PUBLIC_TOKEN`, `OPENAI_API_KEY` notamment).
- [ ] Remplacer le contenu du serveur par `develop` réconcilié (méthode à définir : nouveau clone propre, ou `git reset --hard` suivi d'un `git pull`, selon l'état réel du dépôt sur le serveur).
- [ ] `php artisan migrate --force`, en lecture seule d'abord (`migrate:status`).
- [ ] Parcours de test de fumée : inscription, connexion admin, page partenaires, page boursiers, palmarès.
**Critère d'acceptation** : le serveur exécute exactement le contenu de `develop`, aucune perte de donnée, parcours de fumée sans erreur.

### #9 Interdire les modifications directes sur le serveur à l'avenir
**Labels** : `processus` `P1`
Cette réconciliation n'a été nécessaire que parce que des mois de travail ont été faits directement sur le serveur, hors git, jusqu'à leur archivage tardif dans `recette/ui-ux`.
- [ ] Décider et documenter la règle : toute modification, même petite, passe par une branche locale et une pull request.
- [ ] Si un accès SSH au serveur reste nécessaire pour du dépannage, décider d'une procédure explicite (ticket, notification à l'équipe) plutôt qu'une modification silencieuse.
- [ ] Revoir avec l'équipe qui a accès en écriture au serveur de recette.
**Critère d'acceptation** : la règle est écrite et communiquée à toute personne ayant accès au serveur.

---

## Ordre de travail recommandé

**D'abord** : #1 (diagnostic rapide, conditionne #6 et #7).
**En parallèle, sans dépendance** : #2, #3, #4 (groupe 1, aucun recoupement connu).
**Ensuite, le plus sensible** : #5 et #6 (sécurité de l'export, formulaire d'inscription en production).
**Puis** : #7, une fois #1 tranché.
**Enfin** : #8 (déploiement), puis #9 (processus, pour que ça ne se reproduise pas).

## Commandes utiles
```bash
# Comparer un fichier entre les deux lignes de travail
git show recette/ui-ux:<chemin/du/fichier> > /tmp/recette.php
git show develop:<chemin/du/fichier> > /tmp/develop.php
diff -u /tmp/develop.php /tmp/recette.php

# Créer la branche d'intégration
git switch develop && git pull
git switch -c integration/recette-ui-ux
```

## Pour créer les issues dans GitHub
Si le connecteur GitHub reçoit un accès en écriture sur le dépôt : matérialiser d'abord les 22 issues de `docs/ISSUES_PALMARES_BAC_2026.md` (déjà référencées par plusieurs messages de commit), puis les 9 issues de ce document à la suite, pour que la numérotation GitHub ne rentre pas en collision avec les références existantes.

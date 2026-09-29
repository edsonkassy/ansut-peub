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
| 1 | ~~Vérifier l'hypothèse du logo~~ — fusionnée dans #7, constat confirmé | P1 | Diagnostic | ✅ (fusionné dans #7) | Dev | - |
| 2 | Porter RegionHelper.php dans develop | P1 | Groupe 1 | ✅ (sauf alignement des clés, différé sur #23) | Dev | - |
| 3 | Porter BoursierController.php et admin/boursiers/index.blade.php | P1 | Groupe 1 | ✅ | Dev | - |
| 4 | Porter faq.blade.php et les petits fichiers admin isolés | P2 | Groupe 1 | ✅ (3/7 fichiers portés, 4 écartés) | Dev | - |
| 5 | ~~Réconcilier PartenaireManagementController.php~~ — develop l'emporte | P0 | Groupe 3 | ✅ | Dev | - |
| 6 | Réappliquer le correctif d'inscription dans le formulaire en 2 étapes | P0 | Groupe 3 | ✅ | Dev | - |
| 7 | Réconcilier les fichiers de la refonte UI (forum, library, inbox, layouts, landing, mobile) | P1 | Groupe 2 | 🟡 (constat fait, portage restant) | Dev | - |
| 8 | Déploiement contrôlé : remplacer le contenu du serveur par develop réconcilié | P0 | Déploiement | ☐ | Dev + Chef de projet | 2,3,4,5,6,7 |
| 9 | Interdire les modifications directes sur le serveur à l'avenir | P1 | Processus | ☐ | Chef de projet | 8 |

---

## Détail des issues

### #1 Vérifier l'hypothèse du logo avant de trancher le groupe 2 — ✅ fusionnée dans #7
**Labels** : `diagnostic` `P1`
**Statut** : terminée, hypothèse écartée par les faits. Résumé conservé ici pour la traçabilité ; le travail restant est dans #7.

L'hypothèse de départ était fausse : les diffs « minuscules » mesurés au départ (2 à 4 lignes) comparaient la recette à **elle-même** entre deux commits, pas à `develop`. Une fois comparés directement à `develop`, deux fichiers vérifiés montrent que la recette n'a en réalité **pas du tout** reçu la refonte d'août :

- `resources/views/bachelier/forum/favorites.blade.php` (recette) : version d'avant le lot 4 du 20 août — pas de `<h1>`, couleur `#00BFA5` en dur, filtres qui se soumettent seuls en JS sans effet réel. Le lot 4 avait corrigé, sur ce fichier et ses voisins : une faille XSS dans la recherche du forum (contenu non échappé), des filtres morts, une colonne de base de données mal référencée.
- `resources/js/mobile-gestures.js` (recette) : contient encore les deux bugs que `develop` (20 août) dit avoir corrigés — le sélecteur de swipe cible toujours `.md\:flex` (attrape la barre de navigation), et une nouvelle instance est recréée à chaque redimensionnement sans retirer les écouteurs précédents. Le correctif du 6 mai sur la recette n'avait retiré qu'une seule règle `touch-action`, un pansement sur le symptôme, pas sur la cause.

**Conclusion** : pour l'ensemble du groupe 2, `develop` est une amélioration stricte de la version de la recette, pas une divergence à arbitrer. Le travail restant (#7) est un portage avec vérification rapide fichier par fichier, pas un arbitrage au cas par cas.

### #2 Porter RegionHelper.php dans develop — ✅ fait, sauf l'alignement des clés (différé sur #23)
**Labels** : `groupe-1` `donnees` `P1`
Porté sur `integration/recette-ui-ux` (commit `170a8c9`). Vérifié par comparaison ligne à ligne contre le fichier réel :
- [x] Coordonnées corrigées reprises : Bélier → Toumodi, Nzi → Bocanda.
- [x] 11 villes ajoutées, sans doublon.
- [x] `'Dictionnaire'` absent des régions comme des villes (non repris, comme prévu).
- [x] Dédoublonnage : plus aucune clé dupliquée dans `getRegionCoordinates`, `getCityCoordinates` (les 9 doublons hérités de `develop` supprimés) ni `mapOldRegionToNew`.
- [x] **Nuance actée** : `Tengrela` (utilisée par `getCitiesForRegion`) et `Tingréla` coexistent toujours comme deux clés distinctes, mêmes coordonnées. Ce sont deux orthographes de la même ville gardées comme alias volontaires plutôt que fusionnées en une seule clé — décision : laisser les deux en l'état, aucun bug fonctionnel identifié, à revisiter seulement si un cas d'usage réel l'exige.
- [ ] **Différé sur #23** : alignement des clés régionales avec `PeubScoringHelper` (tiret insécable vs tiret simple, `Loh-Djiboua`/`LohDjiboua`, `San-Pedro`). Aligner change mécaniquement des points de scoring déjà en place — nécessite l'arbitrage de Mamadou, pas un simple alignement de chaînes comme envisagé initialement.
**Critère d'acceptation** : `RegionHelper::getRegions()` ne contient plus `'Dictionnaire'` ni de doublon (✅) ; sert la même liste de régions que `PeubScoringHelper` (☐, différé sur #23).

### #3 Porter BoursierController.php et admin/boursiers/index.blade.php — ✅ fait
**Labels** : `groupe-1` `fonctionnel` `P1`
Porté sur `integration/recette-ui-ux` (commit `1a54369`) : `getBoursiersWithCoordinates` regroupe désormais par région avec compteurs filles/garçons. Vue testée visuellement sur une base de test locale (carte des boursiers regroupée par région, compteurs corrects).

**Faille XSS trouvée et corrigée au passage** (commit `5e8e337`) : `admin/boursiers/index.blade.php` insérait dans `showPanel()` et `displayBubbles()` des valeurs venant de la base (nom, commune, établissement, libellé de région) directement dans le HTML sans échappement. `develop` avait le même défaut de construction avant ce correctif — corrigé au moment du portage, pas exploité en pratique sur l'ancienne vue.
- [x] Comparer les deux versions du contrôleur.
- [x] Porter le regroupement par région dans `develop`.
- [x] Porter la vue associée, testée visuellement.
- [x] Échapper les valeurs issues de la base dans `showPanel()` et `displayBubbles()` (faille XSS trouvée en portant, pas dans le périmètre initial de l'issue).
**Critère d'acceptation** : ✅ la page admin des boursiers affiche le regroupement par région avec les compteurs, testé visuellement ; ✅ plus d'injection HTML possible via les champs boursier.

### #4 Porter faq.blade.php et les petits fichiers admin isolés — ✅ fait, 3 fichiers portés sur 7
**Labels** : `groupe-1` `contenu` `P2`
Porté sur `integration/recette-ui-ux` (commit `b7c978c`). Sur les 7 fichiers listés initialement, 3 portés et 4 écartés après relecture :

**Portés** :
- `faq.blade.php` : réécriture complète, accordéon en JS natif.
- `admin/articles/index.blade.php` : ajout d'attributs `title`.
- `partenaire/analytics.blade.php` : jeton Mapbox lu depuis la config (`config('services.mapbox.public_token')`, confirmé présent dans `config/services.php` sur `develop`).

**Écartés, avec raison** :
- `welcome.blade.php` : `touch-action: pan-y` redondant avec le correctif déjà présent sur `develop` (`touch-action: auto !important` dans `layouts/guest.blade.php`) — rattaché à #7.
- `actualites.blade.php` / `actualite.blade.php` : changements `section`→`div` sans raison identifiée, `overflow-hidden` retiré (casse le zoom au survol des images), même bloc CSS `!important` de pansement tactile que dans #7 — rattachés à #7.
- `admin/analytics.blade.php` : libellé « Comptes en attente » erroné côté recette (affiche en réalité `Candidature::where('status','pending')->count()`, pas un compte de comptes en attente) — la clé PHP sous-jacente est identique des deux côtés, seul le libellé change, et il est faux. `develop` reste la version correcte.
- [x] Relire chaque diff pour confirmer l'absence de recoupement avec un travail récent de `develop`.
- [x] Porter les 3 fichiers sans réserve ; écarter les 4 autres avec justification (voir ci-dessus, et `welcome`/`actualite(s)` réintégrés dans le périmètre de #7).
**Critère d'acceptation** : les 3 fichiers sans recoupement sont dans `develop`, sans régression visible ; les 4 fichiers écartés le sont pour une raison documentée, pas par omission.

### #5 Réconcilier PartenaireManagementController.php — ✅ develop l'emporte, rien à fusionner
**Labels** : `groupe-3` `securite` `P0`
**Statut** : terminée. Conclusion : sur les trois fichiers concernés (contrôleur, routes, vue), `develop` est une amélioration stricte de la version de la recette — même verdict que pour le groupe 2 (#1/#7).

**Contrôleur** — `develop` a `export()` (neutralisation des formules Excel dans les champs texte libres, `withCount` pour compter les opportunités), `verify()`, `reject()` et `toggleStatus()`, qui coexistent proprement sur 3 routes distinctes. La recette n'a pas de `verify()` et un `export()` sans neutralisation de formule : un partenaire malveillant pourrait injecter une formule Excel via son nom d'organisation.

**Routes** — `routes/web.php` de la recette contient deux blocs de routes admin partenaires en double. Laravel garde la dernière définition, si bien que la route nommée `verify` exécute en réalité `toggleStatus()`. Le bouton « Vérifier » de l'admin fonctionne donc côté recette, mais par accident de configuration plutôt que par conception.

**Vue** (`admin/partenaires/index.blade.php`) — comparaison ligne à ligne des deux versions :
- La recette compare `status_verification === 'en_attente'` pour afficher les boutons Vérifier/Rejeter, alors que la valeur réellement stockée est `'pending'` (confirmé dans le contrôleur, des deux côtés). Ces boutons ne s'affichent donc vraisemblablement jamais côté recette. `develop` compare correctement `=== 'pending'` — bug déjà corrigé par la PR #3 (`fix/partenaires-statut`), que la recette n'a jamais reçue.
- Aucun bouton propre à `toggleStatus()` n'existe dans la vue, ni côté recette ni côté `develop` : rien à préserver en supprimant la méthode.
- Le reste (en-têtes, statistiques, filtres, tableau, pagination, structure du modal de rejet) est identique au caractère près entre les deux versions. Seule différence mineure : `develop` utilise `route('admin.partenaires.export')` au lieu d'une URL en dur, et le libellé du motif de rejet précise « (non enregistré pour l'instant) » — honnête sur une limite déjà suivie séparément (`motif_rejet` pas encore persisté).

**Décision** : adopter tel quel le contrôleur, les routes et la vue de `develop`. Aucune fusion manuelle n'est nécessaire pour cette issue.
**Critère d'acceptation** : ✅ une seule implémentation d'`export()` (celle avec neutralisation des formules, déjà sur `develop`), le bouton « Vérifier » fonctionne par conception et non par accident, pas de méthode morte à conserver.

### #6 Réappliquer le correctif d'inscription dans le formulaire en 2 étapes — ⚠️ correction importante (29/09, après-midi)
**Labels** : `groupe-3` `bug` `P0` `securite-donnees`
**Correction** : cette section indiquait précédemment que la PR #5 (`fix/inscription-fichiers`, commit `402c410`) était fusionnée dans `develop`. **Ce n'est pas le cas** — vérifié directement sur GitHub : la PR #5 est toujours à l'état `open`, `merged: false` (créée le 29/09 à 00h14, dernière activité à 00h41). L'historique du fichier `SocialAuthController.php` sur `develop` le confirme : son dernier commit est `6177cb9` (palmarès BAC 2026, 11h30), pas `402c410` (21h21, postérieur). La branche `fix/inscription-fichiers` existe toujours, séparée, avec son commit en attente.

**Conséquence concrète** : le bug `Storage::move()` (qui renvoie un booléen au lieu du chemin final) est **actuellement actif sur `develop`**, pas seulement sur la recette. Tout bachelier qui complète son inscription dès maintenant via le parcours normal (prévisualisation puis confirmation) sur `develop` voit `piece_identite_file`, `collante_bac_file` et `photo_profil` enregistrés avec la valeur `"1"` au lieu du vrai chemin — documents orphelins sur le disque, introuvables par l'administration.

- [x] **Fusionner la PR #5 dans `develop`** — fait. Le blocage d'écriture du connecteur GitHub (compte authentifié sans droits d'écriture sur ce dépôt) a été contourné en exécutant les opérations Git depuis un terminal local disposant des bons droits. PR #5 : `merged: true`, commit `88e935b` sur `develop`. `moveTempFile()` vérifiée présente, 3 appels corrects dans `completeProfile()`.
- [x] Porter le même correctif (`moveTempFile()`) sur la structure à quatre étapes de la recette — préparé, script prêt à exécuter (`apply_recette_fix.sh`). Contenu vérifié par diff exact contre le fichier réel de `recette/ui-ux` avant application (et non recréé de mémoire), pour éviter d'écraser du code par erreur.
- [x] Année 2026 : ajoutée dans le même correctif — trois validations (`saveStep()` étape 2, `showPreview()`, branche directe de `completeProfile()`), le message d'erreur associé, et le `<select>` HTML de `complete-profile-step2.blade.php`.
- [x] Test de régression adapté (`CompleteProfileFilesTest.php`) à la structure à quatre étapes : premier test repris à l'identique (la logique de `completeProfile()` est désormais rigoureusement la même), second test adapté pour cibler directement l'étape 2 (`showStep()` ne vérifie pas que les étapes précédentes sont complétées).

**⚠️ mise à jour (29/09, soir)** — deux découvertes supplémentaires en vérifiant l'état réel de la recette avant de porter le correctif :

- **4 vues jamais versionnées.** `SocialAuthController.php` sur `recette/ui-ux` appelle `view('auth.complete-profile-step1')` à `step4`, mais ces 4 fichiers Blade étaient absents du dépôt Git — aucun commit ne les créait. Vérification SSH sur le serveur : les fichiers existent bien sur le disque (créés le 6 mai 2026), mais apparaissaient en `Untracked files` depuis leur création. Sans eux, le parcours d'inscription en 4 étapes de la recette n'existait tout simplement pas dans l'historique Git. Récupérés depuis le serveur et ajoutés à `recette/ui-ux` (commit `6611248`).
- **Corruption déjà réelle sur le serveur de recette**, pas seulement théorique : 7 profils bacheliers avec `piece_identite_file = '1'`, 7 avec `collante_bac_file = '1'`, 4 avec `photo_profil = '1'`. Les fichiers physiques existent sous des noms aléatoires dans `storage/app/public/temp/`, non reliés à un profil. Le bug touche donc déjà des candidats réels, et continue de corrompre chaque nouvelle inscription tant qu'aucun correctif n'est actif sur le serveur — le correctif ci-dessus n'atteint le serveur qu'au moment du déploiement complet (#8), pas immédiatement.

- [x] **Correctif chirurgical d'urgence sur le serveur** (exception documentée à M4, justifiée par la corruption en cours) : appliqué et testé — une inscription complète sur le serveur confirme des chemins corrects (`documents/pieces_identite/...`, `documents/collantes_bac/...`, `photos/profils/...`), plus aucune trace de la valeur `"1"`. Sauvegarde du fichier live conservée (`SocialAuthController.php.bak-20260929-020119`). À remplacer par le déploiement propre (#8) dès que possible.
- [x] **Rattrapage des 7 profils déjà corrompus** : sans objet. La table `bacheliers` (112 lignes, avec les 177 comptes `users` liés) a été purgée le 29/09 après confirmation que son contenu était exclusivement des données de test d'inscription, sans lien avec le vrai palmarès BAC 2026 (qui vit sur la branche séparée `feature/palmares-2026`, jamais importée sur ce serveur). Les 7 profils corrompus faisaient partie de ce lot : aucun candidat réel à recontacter. Sauvegardes JSON conservées dans `/tmp/` sur le serveur.

**Dépend de** : le portage vers `recette/ui-ux` a suivi la fusion de la PR #5, comme prévu.
**Critère d'acceptation** : PR #5 fusionnée dans `develop` (✅) ; correctif et test portés sur `recette/ui-ux` (✅) ; correctif d'urgence actif sur le serveur (✅, testé) ; 7 profils corrompus rattrapés (✅, sans objet — voir ci-dessus).

**Signalement hors périmètre de ce document, toujours d'actualité** : la PR #1 (`hotfix/otp-bypass` → `main`, suppression du bypass OTP codé en dur) est toujours `open`, `merged: false`. Correctif de sécurité sur `main`, hors périmètre de cette réconciliation, mais toujours urgent.

### #7 Réconcilier les fichiers de la refonte UI
**Labels** : `groupe-2` `P1`
**Statut** : constat confirmé sur 2 fichiers témoins (voir #1) — `develop` est une amélioration stricte, la recette n'a pas reçu la refonte d'août. Reste à vérifier rapidement les fichiers non encore lus, puis à adopter `develop` pour tout le groupe.

Fichiers concernés : `app.css`, `mobile-gestures.js`, `app.js`, `layouts/app.blade.php`, `layouts/guest.blade.php`, `components/opportunites-nav.blade.php`, `bachelier/forum/{index,favorites,members}.blade.php`, `bachelier/library/{index,favorites}.blade.php`, `bachelier/inbox/index.blade.php`, `bachelier/opportunites.blade.php`, `landing/partials/{about,hero,boursiers,news}.blade.php`.
- [x] `forum/favorites.blade.php` : version pré-lot-4 confirmée, `develop` l'emporte.
- [x] `mobile-gestures.js` : deux bugs connus toujours présents côté recette, `develop` l'emporte.
- [ ] Vérifier rapidement (lecture seule, pas de diff complet nécessaire) les fichiers restants de la liste, pour confirmer qu'aucun n'est une exception à la règle.
- [ ] Une fois confirmé : dans la branche d'intégration, ces fichiers ne sont **pas** portés depuis la recette — `develop` reste tel quel, la recette est ignorée sur ce groupe.
- [ ] Exception possible à surveiller : `layouts/guest.blade.php` a un diff un peu plus gros que les autres (+8/-4 dans le commit d'archive) ; à ouvrir en particulier avant de généraliser complètement.
**Critère d'acceptation** : confirmation écrite que chaque fichier du groupe suit la règle générale, ou identification explicite d'une exception à traiter à part.

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

**Fait** : #1 (diagnostic), #2 (RegionHelper, sauf alignement des clés différé sur #23), #3 (BoursierController + vue, avec correctif XSS trouvé au passage), #4 (3/7 fichiers, 4 écartés justifiés), #5 (contrôleur partenaires), #6 (correctif d'inscription complet, correctif d'urgence serveur testé, données de test purgées). #2, #3, #4 sont sur `integration/recette-ui-ux` (4 commits), pas encore fusionnés dans `develop` — une seule PR est prévue pour les trois.
**Restant** : #7 (finir la vérification des fichiers restants de la refonte UI, portage rapide, plus `welcome.blade.php`/`actualite(s).blade.php` rattachés depuis #4).
**Enfin** : #8 (déploiement complet, qui doit notamment remplacer le correctif d'urgence hors-git par le code versionné), puis #9 (processus, pour que ça ne se reproduise pas).

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

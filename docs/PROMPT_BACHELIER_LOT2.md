# Refonte UI et UX espace bachelier — Lot 2 : candidatures

## REGLE ABSOLUE TOUJOURS
- Français entièrement accentué dans tout texte visible. Identifiants, classes et attributs en ASCII.
- Zéro em-dash (U+2014), zéro en-dash (U+2013), zéro emoji.
- Zéro valeur hexadécimale, zéro `rgb()`, zéro `rgba()` dans les vues. Uniquement les rôles définis dans `resources/css/theme.css`.
- Zéro classe de palette Tailwind (`bg-gray-100`, `text-green-800`, `bg-green-100`, etc.). Les échelles `primary-*` et `secondary-*` sont des alias de compatibilité figés : ne pas les utiliser non plus.
- Zéro librairie ajoutée.
- Jamais `touch-action: manipulation`, jamais `preventDefault` sur `touchmove` ou `touchstart`, jamais d écouteur tactile non passif. Le projet vient d en purger 25 occurrences : c est la cause d un bug de production qui a bloqué le défilement pendant des semaines.
- Build vert avant livraison : `npm run build`.
- Si une règle ne peut pas être tenue : STOP, expliquer, ne pas continuer.

## ETAPE 0 : LIRE AVANT D ECRIRE
Lire intégralement, dans cet ordre :
1. `resources/css/theme.css` — les rôles, en particulier les rôles de statut de candidature déjà définis
2. `resources/css/design-system.css` — les primitives `.ds-*`
3. `resources/views/layouts/bachelier.blade.php` et `resources/views/components/bachelier-sidebar.blade.php` — le socle livré au lot 1, qui fait foi
4. `resources/views/bachelier/dashboard.blade.php` et `profile.blade.php` — les motifs d en-tête de page, de carte et de grille à répliquer
5. Les trois vues du lot
6. Le contrôleur qui rend ces vues, dans `app/Http/Controllers/Bachelier/`
7. `app/Models/Candidature.php`
8. `resources/views/components/opportunites-nav.blade.php` et le composant `x-breadcrumb`

Ne rien écrire tant que ces fichiers ne sont pas lus.

## CONTEXTE
L espace bachelier est l application que voit un bachelier admis au programme PEUB. Public : 17 à 20 ans, très majoritairement sur mobile Android, connexion souvent lente, parfois en WebView Android via l APK PEUB.

Ce lot couvre le suivi des candidatures : la liste, le détail d une candidature, et la modale de confirmation qui sert à postuler. C est le cœur métier du produit.

Le lot 1 a livré le socle : layout, sidebar, tableau de bord, profil. Ses motifs font foi, ne pas en inventer de nouveaux.

## PERIMETRE
A modifier, exclusivement :
- `resources/views/bachelier/candidatures.blade.php`
- `resources/views/bachelier/candidatures-show.blade.php`
- `resources/views/bachelier/candidature-confirm-modal.blade.php`

Interdit de modifier :
- Tout contrôleur, toute route, tout modèle, toute migration
- `resources/css/theme.css` — les rôles sont figés et validés
- `resources/css/app.css`
- Toute autre vue, y compris les composants `x-breadcrumb` et `x-opportunites-nav`

## CE QUI EXISTE ET DOIT ETRE PRESERVE

**La modale n est pas décorative.** Elle porte cinq fonctions JavaScript, dont `calculateConfirmCompatibilityScore` et `submitCandidature`, qui poste en `fetch` sur `bachelier.candidatures.store`. Tout ce comportement doit fonctionner à l identique après refonte. Si une classe ou un identifiant ciblé par ce JavaScript change, mettre à jour le JavaScript en conséquence et le signaler.

**Les statuts.** Le modèle `Candidature` expose `pending`, `accepted`, `rejected`. La vue liste en style davantage. Vérifier lesquels existent réellement avant d en styler un ; ne pas inventer de statut.

`theme.css` définit déjà des rôles dédiés : `--status-draft-*`, `--status-pending-*`, `--status-review-*`, `--status-accepted-*`, `--status-rejected-*`. Les utiliser pour les statuts de candidature, plutôt que les variantes de badge génériques.

**Les actions conditionnelles.** Retirer sa candidature n est proposé que si le statut vaut `pending` ; voir l offre, que si `accepted`. Conserver ces conditions exactement.

**Les filtres** forment un formulaire GET rechargé par JavaScript. Conserver le comportement et tous les champs.

## NIVEAU D INTERVENTION : UI ET UX DE SURFACE

Ce lot ne se limite pas au restylage. La structure des pages peut être revue, dans les limites suivantes.

**Autorisé :** réordonner les blocs, hiérarchiser, ajouter un titre de page ou un état vide, replier un bloc, reformuler un libellé ambigu, fusionner ou supprimer un bloc redondant.

**Interdit :** ajouter, supprimer ou renommer une route ; modifier un contrôleur, un modèle ou une requête ; afficher une donnée que le contrôleur ne fournit pas ; retirer un filtre existant.

**Quatre observations sur `candidatures.blade.php`, à traiter :**

1. **Aucun `h1`.** La page s ouvre sur un fil d Ariane en capitales, puis `x-opportunites-nav`, puis les filtres. Ajouter un en-tête de page nommant l espace.

2. **Les filtres occupent le premier écran.** Un bloc de six champs passe avant la moindre candidature. Sur un écran de 360px, un bachelier ayant trois candidatures doit défiler pour les voir. Faire passer la liste devant, et replier les filtres derrière un déclencheur, sans jamais les supprimer et en gardant le formulaire GET fonctionnel. Si des filtres sont actifs, le déclencheur doit l indiquer.

3. **Aucun repère de suivi.** La page suit des candidatures mais n indique nulle part combien sont en attente, acceptées ou refusées. Si le contrôleur fournit de quoi les compter, ajouter ces repères en tête. S il ne le fournit pas, ne rien calculer dans la vue : le signaler dans le résumé.

4. **`#00BFA5`** est utilisé comme couleur d action. Cette valeur n existe ni dans `theme.css`, ni ailleurs dans le projet. La remplacer par le rôle approprié.

**Sur le ton :** une candidature refusée doit rester lisible sans être punitive. Le rouge plein sur toute une carte est à proscrire pour un public de 17 à 20 ans. Le statut doit être clair, la carte doit rester neutre.

**Sur `candidatures-show.blade.php` et la modale :** appliquer la même exigence. Lire, identifier ce qui gêne réellement l usage, corriger, et lister chaque modification de structure dans le résumé final.

## EXIGENCES TECHNIQUES

1. **Rôles uniquement.** Toute couleur passe par `var(--role)` ou par une classe Tailwind adossée à un rôle (`bg-surface`, `text-content-secondary`, `border-line`).

2. **Mode sombre.** Les trois vues doivent être correctes avec `data-theme="dark"` sur `<html>`. Aucune règle spécifique au sombre ne doit être écrite : si une couleur ne bascule pas, c est qu un rôle a été contourné.

3. **Mobile d abord.** Concevoir à 360px, puis élargir. Aucun défilement horizontal. La modale doit être utilisable à 360px : elle ne doit ni déborder, ni piéger le défilement de la page en dessous.

4. **La modale et le clavier.** Piège de focus quand elle est ouverte, fermeture par Échap, `role="dialog"`, `aria-modal="true"`, focus rendu à l élément déclencheur à la fermeture. Le code actuel écoute déjà `keydown` : le fiabiliser plutôt que le réécrire.

5. **La cascade héritée.** `app.css` contient de nombreux `!important`, et Tailwind v3 n émet pas de `@layer` natif : seules la spécificité et l ordre source comptent. Si une neutralisation est nécessaire, appliquer la méthode déjà en place dans `layouts/bachelier.blade.php` : règle préfixée `html[data-ds]`, commentée avec le sélecteur adverse et sa spécificité. Ne jamais neutraliser globalement.

6. **Accessibilité.** Un seul `h1` par page. Contraste AA mesuré, jamais supposé : 4,5:1 pour le texte courant, 3:1 pour les éléments d interface. Cibles tactiles de 44px minimum. Focus visible conservé, jamais de `focus:outline-none`. `alt` sur chaque image.

7. **Icônes.** Remplacer les `<i data-lucide="...">` par du SVG inline :

```
<svg width="20" height="20" viewBox="0 0 24 24" fill="none"
     stroke="currentColor" stroke-width="2" stroke-linecap="round"
     stroke-linejoin="round" aria-hidden="true" focusable="false">
  <path d="..."/>
</svg>
```

`stroke="currentColor"` sans exception : c est ce qui fait basculer les icônes en sombre gratuitement. Ne pas retirer le script Lucide du layout, les vues non encore migrées en dépendent.

## LIVRAISON ATTENDUE
1. Les trois fichiers modifiés.
2. `npm run build` vert.
3. Vérification, commandes à l appui, qu il ne subsiste dans les fichiers modifiés aucun hexadécimal, aucun `rgb()`, aucune classe de palette Tailwind, aucun `touch-action`, aucun `data-lucide`.
4. Vérification en navigateur à 360px, dans les deux thèmes : la liste, le détail, et surtout la modale ouverte — soumission d une candidature de bout en bout, score de compatibilité affiché, fermeture par Échap, focus rendu au déclencheur.
5. Vérification que les filtres GET fonctionnent toujours, y compris repliés.
6. Un résumé court : ce qui a changé par vue, chaque modification de structure justifiée, et tout point où une décision a dû être prise faute d information.

Ne pas commiter. La revue se fait avant.

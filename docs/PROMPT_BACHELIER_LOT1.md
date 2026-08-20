# Refonte UI espace bachelier — Lot 1 : socle

## REGLE ABSOLUE TOUJOURS
- Français entièrement accentué dans tout texte visible. Identifiants, classes et attributs en ASCII.
- Zéro em-dash (U+2014), zéro en-dash (U+2013), zéro emoji.
- Zéro valeur hexadécimale, zéro `rgb()`, zéro `rgba()` dans les vues. Uniquement les rôles définis dans `resources/css/theme.css`.
- Zéro classe de palette Tailwind (`bg-gray-50`, `text-orange-500`, `bg-[#0A2540]`, etc.). Les échelles `primary-*` et `secondary-*` sont des alias de compatibilité figés : ne pas les utiliser non plus.
- Zéro librairie ajoutée.
- Jamais `touch-action: manipulation`, jamais `preventDefault` sur `touchmove` ou `touchstart`, jamais d écouteur tactile non passif. Le projet vient d en purger 25 occurrences : c est la cause d un bug de production qui a bloqué le défilement pendant des semaines.
- Build vert avant livraison : `npm run build`.
- Si une règle ne peut pas être tenue : STOP, expliquer, ne pas continuer.

## ETAPE 0 : LIRE AVANT D ECRIRE
Lire intégralement, dans cet ordre :
1. `resources/css/theme.css` — les trois couches, la liste exacte des rôles
2. `resources/css/design-system.css` — les primitives `.ds-*`
3. `resources/views/landing/partials/` — les six partials déjà migrés, qui font foi sur les motifs de grille, d espacement et de carte
4. `resources/views/layouts/guest.blade.php` — en particulier le bloc `<style>` de neutralisation `html[data-ds]` et son raisonnement
5. `resources/views/layouts/bachelier.blade.php`
6. `resources/views/components/bachelier-sidebar.blade.php`
7. `resources/views/bachelier/dashboard.blade.php`
8. `resources/views/bachelier/profile.blade.php`
9. Les contrôleurs qui rendent ces deux vues

Ne rien écrire tant que ces fichiers ne sont pas lus.

## CONTEXTE
L espace bachelier est l application que voit un bachelier admis au programme PEUB : son tableau de bord, ses candidatures, ses opportunités, la bibliothèque, la messagerie et le forum.

Public : 17 à 20 ans, très majoritairement sur mobile Android, connexion souvent lente, parfois en WebView Android via l APK PEUB.

Ce lot refait le socle. Les quatre lots suivants s appuieront dessus, donc tout ce qui est décidé ici sera répliqué 26 fois. Un raccourci pris maintenant coûtera cher ensuite.

## PERIMETRE
A modifier, exclusivement :
- `resources/views/layouts/bachelier.blade.php`
- `resources/views/components/bachelier-sidebar.blade.php`
- `resources/views/bachelier/dashboard.blade.php`
- `resources/views/bachelier/profile.blade.php`

Interdit de modifier :
- Tout contrôleur, toute route, tout modèle, toute migration
- `resources/css/theme.css` — les rôles sont figés et validés
- `resources/css/app.css` — sauf si un `!important` s y révèle impossible à battre autrement ; dans ce cas, le signaler avant d agir
- Toute autre vue de `resources/views/bachelier/`

## CE QUI EXISTE
`layouts/bachelier.blade.php` n a pas de `@yield('content')`. Il inclut `components.bachelier-sidebar`, qui porte à la fois la navigation et le `@yield('content')` en ligne 150. La sidebar est donc le vrai squelette de page, pas un simple composant de navigation.

Huit entrées de navigation, toutes à conserver avec leurs routes exactes et leurs conditions `request()->routeIs(...)` : tableau de bord, opportunité, ressources, messagerie, communauté, parcours, mes dotations, profil.

Le layout porte déjà `data-mobile-gestures` sur `<body>` : c est volontaire, ne pas le retirer. Cet attribut active `resources/js/mobile-gestures.js`, cantonné à ce seul layout.

## NIVEAU D INTERVENTION : UI ET UX DE SURFACE

Ce lot ne se limite pas au restylage. La structure des pages peut être revue, dans les limites suivantes.

**Autorisé :**
- Réordonner les blocs d une page selon leur importance pour l utilisateur
- Fusionner ou supprimer un bloc redondant
- Ajouter un titre de page, un fil d Ariane, un état vide
- Reformuler un libellé de navigation ou de bouton s il est ambigu
- Hiérarchiser : ce qui compte doit être vu en premier sur un écran de 360px

**Interdit :**
- Ajouter, supprimer ou renommer une route
- Modifier un contrôleur, un modèle ou une requête
- Retirer une entrée de navigation
- Afficher une donnée que le contrôleur ne fournit pas

**Deux observations sur le tableau de bord, à traiter :**

Il n a aucun `h1` : la page s ouvre directement sur un `h2`. Un bachelier arrivant sur son espace ne sait pas où il est. Ajouter un en-tête de page nommant l espace et, si le contrôleur le permet, l utilisateur.

Le bloc « Statut Boursier PEUB » (ou « Programme Boursier PEUB » selon le statut) est en bas de page, après trois listes. C est pourtant l information la plus structurante pour un bachelier. Le remonter.

**Sur la navigation :** huit entrées, dont « Opportunité », « Ressources », « Communauté », « Parcours » et « Mes dotations ». Ces libellés ne sont pas tous immédiatement clairs pour un public de 17 à 20 ans. Proposer des reformulations dans le résumé final, sans les appliquer : la décision revient à ANSUT.

Toute modification de structure doit être listée et justifiée dans le résumé final.

## EXIGENCES

### 1. Rôles uniquement
Toute couleur passe par `var(--role)` ou par une classe Tailwind adossée à un rôle (`bg-surface`, `text-content-secondary`, `border-line`).

La sidebar est actuellement en `bg-[#0A2540]` avec un état actif en `bg-orange-500`. Ces deux valeurs doivent devenir des rôles. Utiliser `--surface-inverse` pour le fond et `--accent-highlight` pour l état actif si le contraste le permet ; sinon, proposer l ajout d un rôle dédié dans `theme.css` **et attendre validation avant de l ajouter**.

### 2. Mode sombre
La page doit être correcte avec `data-theme="dark"` sur `<html>`. Aucune règle spécifique au sombre ne doit être écrite. Si une couleur ne bascule pas, c est qu un rôle a été contourné.

Le layout porte encore `x-data="{ darkMode: false }"` et `x-bind:class="{ 'dark': darkMode }"` sur `<html>`. C est du code mort, comme dans le layout guest : `.dark html` ne peut jamais correspondre. Le remplacer par `@yield('html-attrs')`, et poser `@section('html-attrs', 'data-ds')` dans les vues du lot.

### 3. Mobile d abord
Concevoir à 360px, puis élargir. La sidebar se replie déjà en tiroir sous `lg` via Alpine : conserver ce comportement, le fiabiliser si nécessaire.

Vérifier qu il n y a aucun défilement horizontal à 360px.

### 4. La cascade héritée
`app.css` fait 1128 lignes et contient de nombreux `!important`, dont plusieurs indexés spécifiquement sur `.bachelier-sidebar`. Tailwind v3 n émet pas de `@layer` natif : seules la spécificité et l ordre source comptent.

Si une neutralisation est nécessaire, appliquer la même méthode que dans `layouts/guest.blade.php` : un bloc `<style>` dédié, chaque règle préfixée par `html[data-ds]`, chaque règle commentée avec le sélecteur adverse et sa spécificité. Ne jamais neutraliser globalement.

### 5. Accessibilité
- Un seul `h1` par page
- Contraste AA : 4,5:1 pour le texte courant, 3:1 pour les éléments d interface. Le vérifier, ne pas le supposer
- Cibles tactiles de 44px minimum
- Focus visible conservé, jamais de `focus:outline-none`
- La sidebar en tiroir doit être utilisable au clavier : piège de focus quand elle est ouverte, fermeture par la touche Échap, `aria-expanded` sur le bouton d ouverture
- `alt` sur chaque image

### 6. Icônes
Les vues utilisent `<i data-lucide="...">` avec un script CDN chargé par le layout. Remplacer par du SVG inline dans les fichiers du lot :

```
<svg width="20" height="20" viewBox="0 0 24 24" fill="none"
     stroke="currentColor" stroke-width="2" stroke-linecap="round"
     stroke-linejoin="round" aria-hidden="true" focusable="false">
  <path d="..."/>
</svg>
```

`stroke="currentColor"` sans exception : c est ce qui fait basculer les icônes en sombre gratuitement.

Ne pas retirer le script Lucide du layout : les 26 vues non encore migrées en dépendent.

## LIVRAISON ATTENDUE
1. Les quatre fichiers modifiés.
2. `npm run build` vert.
3. Vérification, commandes à l appui, qu il ne subsiste dans les fichiers modifiés aucun hexadécimal, aucun `rgb()`, aucune classe de palette Tailwind, aucun `touch-action`, aucun `data-lucide`.
4. Vérification en navigateur à 360px, dans les deux thèmes : pas de défilement horizontal, défilement vertical fluide, tiroir de sidebar fonctionnel, focus visible à chaque arrêt de tabulation.
5. Vérification de non-régression : les 26 autres vues bachelier ne portent pas `data-ds`, donc toute règle de neutralisation ajoutée doit y être inerte. Le confirmer sur au moins trois d entre elles.
6. Un résumé court : ce qui a changé, et tout point où une décision a dû être prise faute d information.

Ne pas commiter. La revue se fait avant.

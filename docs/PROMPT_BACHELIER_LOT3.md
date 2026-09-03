# Refonte UI et UX espace bachelier — Lot 3 : opportunités

## REGLE ABSOLUE TOUJOURS
- Français entièrement accentué dans tout texte visible. Identifiants, classes et attributs en ASCII.
- Zéro em-dash (U+2014), zéro en-dash (U+2013), zéro emoji.
- Zéro valeur hexadécimale, zéro `rgb()`, zéro `rgba()` dans les vues. Uniquement les rôles définis dans `resources/css/theme.css`.
- Zéro classe de palette Tailwind (`bg-gray-100`, `text-purple-600`, etc.). Les échelles `primary-*` et `secondary-*` sont des alias de compatibilité figés : ne pas les utiliser non plus.
- Zéro librairie ajoutée.
- Jamais `touch-action: manipulation`, jamais `preventDefault` sur `touchmove` ou `touchstart`, jamais d écouteur tactile non passif.
- Build vert avant livraison : `npm run build`.
- Si une règle ne peut pas être tenue : STOP, expliquer, ne pas continuer.

## ETAPE 0 : LIRE AVANT D ECRIRE
1. `resources/css/theme.css` et `resources/css/design-system.css`
2. `resources/views/layouts/bachelier.blade.php` et `resources/views/components/bachelier-sidebar.blade.php` — le socle du lot 1
3. `resources/views/bachelier/candidatures.blade.php` — le lot 2, qui fait foi sur les motifs de liste, de filtres repliés, de bande de repères et de statut en pastille
4. `resources/views/bachelier/candidature-confirm-modal.blade.php` — déjà migrée, incluse par deux vues de ce lot
5. Les sept vues du lot
6. Les contrôleurs qui les rendent
7. `app/Models/Opportunite.php`, `app/Models/ParcoursUniversitaire.php`

Ne rien écrire tant que ces fichiers ne sont pas lus.

## PERIMETRE
A modifier, exclusivement :
- `resources/views/bachelier/opportunites.blade.php`
- `resources/views/bachelier/opportunites-show.blade.php`
- `resources/views/bachelier/favoris.blade.php`
- `resources/views/bachelier/dotations.blade.php`
- `resources/views/bachelier/parcours/index.blade.php`
- `resources/views/bachelier/parcours/create.blade.php`
- `resources/views/bachelier/parcours/edit.blade.php`
- `resources/views/components/opportunites-nav.blade.php`

Interdit : tout contrôleur, toute route, tout modèle, `theme.css`, `app.css`, toute autre vue.

## TROIS DETTES CONNUES, A TRAITER

**1. `#00BFA5`, 125 occurrences.** C est la seule couleur en dur du lot, et elle n appartient à aucune palette du projet. La remplacer partout par le rôle approprié.

**2. `score_ia` n a aucune source.** Le champ n existe ni sur `Opportunite`, ni dans les contrôleurs. Ces blocs de score n ont jamais rien affiché. Contrairement au lot 2 où `score_matching` existait sur `Candidature`, il n y a ici aucun champ de remplacement.

Ne pas inventer de calcul dans la vue. Retirer ces blocs morts, et le signaler dans le résumé : si un score de compatibilité doit exister sur les offres, c est un travail backend, pas une correction de vue.

**3. `x-opportunites-nav` code une couleur en dur** et ne bascule pas en mode sombre. Le lot 2 l a contourné en reprenant les onglets en ligne. Le composant est dans le périmètre de ce lot : le corriger à la source. Le lot 2 pourra le réutiliser ensuite.

## NIVEAU D INTERVENTION : UI ET UX DE SURFACE

**Autorisé :** réordonner les blocs, hiérarchiser, ajouter un titre de page ou un état vide, replier un bloc, reformuler un libellé ambigu, fusionner ou supprimer un bloc redondant ou mort.

**Interdit :** ajouter, supprimer ou renommer une route ; modifier un contrôleur, un modèle ou une requête ; afficher une donnée que le contrôleur ne fournit pas ; retirer un filtre fonctionnel.

**Points d attention :**

`opportunites.blade.php` est la page la plus consultée de l espace : c est là qu un bachelier découvre ce à quoi il peut postuler. Elle doit répondre en un écran de 360px à trois questions — qu est-ce qui est ouvert, qu est-ce qui ferme bientôt, qu est-ce qui me correspond.

Vérifier si cette page souffre des mêmes défauts que la liste des candidatures : filtres occupant le premier écran, absence de `h1`, repères manquants. Corriger de la même façon, avec les mêmes motifs, sans en inventer de nouveaux.

`favoris.blade.php` fait 356 lignes pour une liste de favoris. Vérifier ce qui y est réellement utile.

`parcours/create` et `parcours/edit` sont deux formulaires proches. S ils sont largement identiques, le signaler plutôt que de dupliquer le travail.

`dotations.blade.php` affiche un équipement attribué. Le lot 1 a déjà traité un bloc de dotation sur le tableau de bord : reprendre le même traitement.

## EXIGENCES TECHNIQUES

1. **Rôles uniquement.** Toute couleur passe par `var(--role)` ou une classe Tailwind adossée à un rôle.

2. **Mode sombre.** Les sept vues correctes avec `data-theme="dark"`, sans écrire une seule règle spécifique au sombre.

3. **Mobile d abord.** 360px puis élargir, aucun défilement horizontal.

4. **La modale.** `opportunites.blade.php` et `opportunites-show.blade.php` incluent `candidature-confirm-modal`, déjà migrée au lot 2. Ne pas la modifier. Son `@include` vient d être déplacé à l intérieur de la section pour corriger un mode quirks : ne pas le ressortir.

5. **Accessibilité.** Un seul `h1` par page. Contraste AA mesuré, jamais supposé. Cibles de 44px. Focus visible, jamais de `focus:outline-none`. `alt` sur chaque image.

6. **Icônes.** SVG inline en `stroke="currentColor"`, `aria-hidden="true" focusable="false"`. Ne pas retirer le script Lucide du layout.

## LIVRAISON ATTENDUE
1. Les huit fichiers modifiés.
2. `npm run build` vert.
3. Vérification, commandes à l appui : aucun hexadécimal, aucun `rgb()`, aucune classe de palette Tailwind, aucun `touch-action`, aucun `data-lucide`, aucun `score_ia` dans les fichiers modifiés.
4. Vérification que `document.compatMode === "CSS1Compat"` sur les deux pages d offres.
5. Vérification en navigateur à 360px, dans les deux thèmes, sur les sept pages.
6. Un résumé court : ce qui a changé par vue, chaque modification de structure justifiée, et tout point où une décision a dû être prise faute d information.

Ne pas commiter. La revue se fait avant.

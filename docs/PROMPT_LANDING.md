# Refonte UI de la landing PEUB

## REGLE ABSOLUE TOUJOURS
- Français entièrement accentué dans tout texte visible. Identifiants et
  classes en ASCII.
- Zéro em-dash (U+2014), zéro en-dash (U+2013), zéro emoji, zéro icône
  de police externe. SVG inline uniquement.
- Zéro valeur hexadécimale dans les vues. Uniquement les rôles CSS
  définis dans `resources/css/theme.css`.
- Zéro librairie ajoutée. Pas de framer-motion, pas de lucide, pas de CDN.
- Build vert avant tout commit : `npm run build`.
- Si une règle ne peut pas être tenue : STOP, expliquer, ne pas continuer.

## ETAPE 0 : LIRE AVANT D ECRIRE
Lire intégralement, dans cet ordre, avant toute modification :
1. `resources/css/theme.css` — les trois couches, la liste exacte des rôles
2. `resources/css/design-system.css` — les primitives `.ds-*` disponibles
3. `resources/views/design/system.blade.php` — usage de référence
4. `tailwind.config.js` — les rôles exposés à Tailwind
5. `app/Http/Controllers/LandingController.php` — les variables passées
6. Les six partials existants dans `resources/views/landing/partials/`
7. `resources/views/layouts/guest.blade.php`

Ne rien écrire tant que ces fichiers ne sont pas lus.

## CONTEXTE
PEUB (Programme d Excellence Universelle pour les Bacheliers) est un
programme public ivoirien porté par l ANSUT. Il connecte les meilleurs
bacheliers du pays à des bourses, stages, formations et événements
proposés par des partenaires vérifiés.

Public de la landing : des bacheliers de 17 à 20 ans, majoritairement
sur mobile Android, connexion parfois lente. Objectif unique de la page :
les amener à déposer une candidature.

## OBJECTIF
Refaire l intégralité du UI de la landing avec le design system, sans
toucher à la logique ni au contenu métier.

## PERIMETRE
A modifier :
- `resources/views/landing/partials/hero.blade.php`
- `resources/views/landing/partials/stats.blade.php`
- `resources/views/landing/partials/about.blade.php`
- `resources/views/landing/partials/opportunities.blade.php`
- `resources/views/landing/partials/partners.blade.php`
- `resources/views/landing/partials/news.blade.php`
- `resources/views/layouts/guest.blade.php` (voir contrainte ci-dessous)

Interdit de modifier :
- `app/Http/Controllers/LandingController.php`
- Toute route, tout modèle, toute migration
- `resources/css/theme.css` (les rôles sont figés et validés)
- `resources/views/landing/partials/boursiers.blade.php` (partial orphelin,
  non inclus dans la landing, contient la carte Mapbox du back-office)

## CONTRAINTE SUR LE LAYOUT
`layouts/guest.blade.php` est partagé avec les pages d authentification
non encore migrées. Deux exigences :
1. Ajouter `data-ds` sur la balise `<html>`.
2. Ne supprimer aucun bloc `<style>` existant. Les règles globales
   actuelles utilisent `!important` sur les polices ; si elles écrasent
   le design system, les neutraliser **uniquement dans la portée
   `[data-ds]`**, sans toucher aux règles hors de cette portée.

## DONNEES DISPONIBLES (venant du controller, à ne pas inventer)
- `$stats['bacheliers_count']`, `$stats['opportunites_count']`,
  `$stats['partenaires_count']`, `$stats['satisfaction_rate']`
- `$featured_opportunities` — collection, 6 max
- `$featured_partners` — collection, 8 max
- `$featured_articles` — collection, 3 max

Chaque collection peut être vide. Prévoir un état vide sobre pour chacune,
jamais une section cassée ou un bloc fantôme.

## CONTENU A CONSERVER
Le wording existant est validé par ANSUT. Le reprendre tel quel, à
l accentuation près. Notamment :

Stats : « Bacheliers accompagnés », « Opportunités disponibles »,
« Partenaires de confiance », « Taux de satisfaction ».

About : « Une initiative de l Agence Nationale du Service Universel des
Télécommunications (ANSUT) », et les trois piliers « Sélection
d Excellence », « Opportunités Premium », « IA Personnalisée » avec
leurs descriptions et leurs trois puces chacun.

Opportunités : les catégories avec leur compteur et leur description, et
l appel « Se connecter pour voir les détails ».

News : le bloc newsletter « Restez informé » et son sous-titre.

## EXIGENCES TECHNIQUES
1. **Rôles uniquement.** Toute couleur passe par `var(--role)` ou par une
   classe Tailwind adossée à un rôle (`bg-surface`, `text-content-secondary`,
   `border-line`). Vérification finale attendue : aucun hex dans les vues
   modifiées.
2. **Mode sombre.** La page doit être correcte avec `data-theme="dark"`
   sur `<html>`. Aucune règle spécifique au sombre ne doit être écrite :
   si une couleur ne bascule pas, c est qu un rôle a été contourné.
3. **Mobile d abord.** Concevoir à 360px de large, puis élargir. Le scroll
   vertical doit rester fluide : ne jamais poser `touch-action: manipulation`,
   ne jamais appeler `preventDefault` sur `touchmove` ou `touchstart`.
   Ce point a déjà causé un bug de production bloquant.
4. **Images.** Les images de fond lourdes ont dégradé le scroll sur Chrome
   Android et ont été retirées. Ne pas en réintroduire. Travailler avec les
   dégradés de marque (`--brand-gradient-from`, `--brand-gradient-to`) et
   les surfaces.
5. **Accessibilité.** Contraste AA minimum, hiérarchie de titres cohérente
   (un seul `h1`), `alt` sur chaque image, cibles tactiles de 44px minimum,
   focus visible conservé.
6. **Aucune dépendance JavaScript nouvelle.** Si une interaction est
   nécessaire, JavaScript natif, sans `preventDefault` sur les gestes.

## LIVRAISON ATTENDUE
1. Les six partials et le layout modifiés.
2. `npm run build` vert.
3. Vérification explicite, commande à l appui, qu aucun hexadécimal ne
   subsiste dans les fichiers modifiés.
4. Un résumé court : ce qui a changé par section, et tout point où une
   décision a dû être prise faute d information.

Ne pas commiter. La revue se fait avant.

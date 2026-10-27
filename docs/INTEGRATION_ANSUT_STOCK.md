# Intégration avec ANSUT Stock

Les ordinateurs portables des boursiers sont gérés à l'unité par **ANSUT Stock** (gestion des stocks d'équipements de l'ANSUT). La PEUB décide qui reçoit un ordinateur ; ANSUT Stock réserve l'appareil au point de retrait, envoie au bachelier son code de retrait par SMS et confirme la remise. Connexion internet et abonnement IA restent gérés par la PEUB seule.

## Fonctionnement
1. **Attribution** (fiche du bachelier, « Attribuer une nouvelle dotation ») : pour un ordinateur, l'administrateur choisit aussi le **point de retrait** (liste lue dans ANSUT Stock). La PEUB appelle `POST /v1/distributions` (référence `PEUB-DOT-<id>`, marque et modèle de l'article, coordonnées du bachelier). Refus d'ANSUT Stock (rupture, point inconnu…) = aucune dotation créée, message affiché.
2. **Remise** : l'agent ANSUT remet l'ordinateur ; le webhook `distribution.delivered` passe la dotation à **active**, avec le numéro de série, et le bachelier reçoit l'e-mail habituel.
3. **Remplacement / annulation** : `distribution.replaced` met à jour le numéro de retrait ; `distribution.cancelled` suspend la dotation avec le motif.

Le stock local des ordinateurs n'est plus décompté par la PEUB (aucun mouvement de stock) : la disponibilité est celle d'ANSUT Stock.

## Configuration (`.env`)
Sans ces variables, rien ne change : les ordinateurs restent gérés par le stock local de la PEUB. L'intégration s'active en les renseignant.

```
ANSUT_STOCK_API_URL=https://api-recette.ansut.ci
ANSUT_STOCK_TOKEN_URL=https://auth-recette.ansut.ci/realms/ansut/protocol/openid-connect/token
ANSUT_STOCK_CLIENT_ID=partner-peub-xxxxxx
ANSUT_STOCK_CLIENT_SECRET=...
ANSUT_STOCK_WEBHOOK_SECRET=whsec_...
```
Dans ANSUT Stock :
- **/admin/partenaires** : partenaire PEUB (type plateforme) → créer une clé avec les droits `distribution:create`, `distribution:read`, `sites:read` → `CLIENT_ID` et `CLIENT_SECRET` (secret affiché une fois).
- **/admin/webhooks** : webhook vers `https://<peub>/api/webhooks/ansut-stock`, événements `distribution.delivered`, `distribution.replaced`, `distribution.cancelled` → `WEBHOOK_SECRET` (affiché une fois).
- **Catalogue** : la marque et le modèle de l'article d'inventaire PEUB doivent être identiques à ceux du catalogue ANSUT Stock (ex. « HP » / « ProBook 450 G10 »).

## Sécurité
Webhooks : signature `X-Signature` = `sha256=HMAC-SHA256(secret, "<X-Webhook-Timestamp>.<corps>")` vérifiée en temps constant, horodatage de moins de 5 minutes, chaque livraison traitée une fois (`X-Webhook-Id`), limite 120 appels par minute. Le jeton d'accès à ANSUT Stock est mis en cache jusqu'à son expiration.

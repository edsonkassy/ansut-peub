# Configuration OpenAI API

Ce document explique comment configurer l'API OpenAI pour le projet PEUB.

## 🔑 Obtenir votre clé API OpenAI

1. Allez sur [https://platform.openai.com/api-keys](https://platform.openai.com/api-keys)
2. Connectez-vous ou créez un compte
3. Cliquez sur "Create new secret key"
4. Copiez la clé (commence par `sk-proj-...`)

## ⚙️ Configuration pour l'API OpenAI Standard

Ajoutez ces variables dans votre fichier `.env` :

```bash
# Clé API OpenAI (OBLIGATOIRE)
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# Type d'API (par défaut: 'openai')
OPENAI_API_TYPE=openai

# Timeout des requêtes en secondes (optionnel, défaut: 30)
OPENAI_REQUEST_TIMEOUT=30
```

## 🔧 Configuration pour Azure OpenAI (optionnel)

Si vous utilisez Azure OpenAI au lieu de l'API standard :

```bash
# Type d'API
OPENAI_API_TYPE=azure

# Votre clé API Azure
OPENAI_API_KEY=votre-cle-azure

# Nom de votre ressource Azure OpenAI
AZURE_OPENAI_RESOURCE=my-openai-resource

# Version de l'API Azure
OPENAI_API_VERSION=2024-02-15-preview

# Nom du déploiement (si différent du nom du modèle)
AZURE_OPENAI_DEPLOYMENT_NAME=gpt-4o-mini
```

## 📊 Modèles Utilisés

Le service utilise ces modèles OpenAI :

- **Vision** : `gpt-4o-mini` (analyse d'images des documents)
- **Text** : `gpt-4o-mini` (analyse de texte des motivations)

Ces modèles sont définis dans `app/Services/AiExtractionService.php`.

## ✅ Vérification de la Configuration

Pour tester votre configuration :

```bash
# Vérifier que la clé API est bien chargée
php artisan tinker
>>> config('openai.api_key')
```

## 🚨 Résolution des Problèmes

### Erreur : "Could not resolve host: .openai.azure.com"

**Cause** : Le système essaie d'utiliser Azure OpenAI alors que vous utilisez l'API standard.

**Solution** : 
```bash
# Assurez-vous que cette variable est bien définie dans .env
OPENAI_API_TYPE=openai
```

Puis rechargez la configuration :
```bash
php artisan config:clear
php artisan cache:clear
```

### Erreur : "OpenAI API key not configured"

**Cause** : La clé API n'est pas définie.

**Solution** :
```bash
# Ajoutez dans .env
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# Rechargez
php artisan config:clear
```

### Erreur : "Unauthorized" (401)

**Cause** : Clé API invalide ou expirée.

**Solution** :
1. Vérifiez que votre clé est correcte
2. Générez une nouvelle clé sur [platform.openai.com](https://platform.openai.com/api-keys)
3. Mettez à jour `.env`

### Erreur : "Insufficient quota" (429)

**Cause** : Vous avez dépassé votre quota OpenAI.

**Solution** :
1. Vérifiez votre usage sur [platform.openai.com/usage](https://platform.openai.com/usage)
2. Ajoutez des crédits à votre compte OpenAI

## 📝 Logs de Débogage

Les requêtes API sont loguées dans `storage/logs/laravel.log` :

```bash
# Voir les logs en temps réel
tail -f storage/logs/laravel.log | grep "OpenAI"
```

Vous verrez :
- `[INFO] Requête API OpenAI` - Détails de chaque requête
- `[ERROR] Erreur API OpenAI` - Erreurs avec détails complets

## 💰 Coûts Estimés

Avec `gpt-4o-mini` (modèle économique) :

- **Analyse de pièce d'identité** : ~0.001$ par document
- **Analyse de collante BAC** : ~0.001$ par document
- **Analyse de motivation** : ~0.0005$ par texte

**Exemple** : 1000 candidatures complètes ≈ 2.5$

## 🔐 Sécurité

⚠️ **Ne committez JAMAIS votre clé API dans Git !**

```bash
# .env est déjà dans .gitignore
# Vérifiez avec :
git status

# Si .env apparaît, ajoutez-le à .gitignore immédiatement
echo ".env" >> .gitignore
```

## 📚 Documentation Officielle

- [OpenAI API Documentation](https://platform.openai.com/docs)
- [GPT-4o Mini](https://platform.openai.com/docs/models/gpt-4o-mini)
- [Vision API](https://platform.openai.com/docs/guides/vision)


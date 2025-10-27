#!/bin/bash
# Script pour corriger le problème de storage si $HOME est utilisé

echo "🔧 Correction du problème de storage (avec \$HOME)"
echo "==================================================="

# 1. Récupérer le répertoire réel de l'application
APP_DIR=$(pwd)
STORAGE_DIR="$APP_DIR/storage/app/public"

echo "📂 Répertoire application: $APP_DIR"
echo "📂 Répertoire storage: $STORAGE_DIR"

# 2. Supprimer l'ancien lien symbolique s'il existe
if [ -L "public/storage" ]; then
    echo "🗑️ Suppression de l'ancien lien symbolique..."
    rm public/storage
fi

# 3. Créer le bon lien symbolique (chemin absolu)
echo "🔗 Création du lien symbolique avec chemin absolu..."
ln -s "$STORAGE_DIR" public/storage

# 4. Vérifier
if [ -L "public/storage" ]; then
    echo "✅ Lien symbolique créé avec succès !"
    echo "📍 Destination: $(readlink public/storage)"
else
    echo "❌ Erreur: Impossible de créer le lien"
    exit 1
fi

# 5. Vérifier les permissions
echo "🔐 Configuration des permissions..."
chmod -R 755 storage/
chmod -R 755 public/storage
chown -R www-data:www-data storage/ 2>/dev/null || chown -R apache:apache storage/ 2>/dev/null || chown -R psacln:psacln storage/

echo ""
echo "✅ Problème résolu !"
echo "📍 Signatures: storage/app/public/signatures/"
echo "🌐 URL: http://votre-domaine.com/storage/signatures/"


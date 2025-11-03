#!/bin/bash
# Script pour corriger le lien symbolique storage sur le serveur
# À exécuter sur le serveur

echo "🔧 Correction du lien symbolique storage"
echo "========================================"

# Chemin de l'application
APP_PATH="/var/www/vhosts/fphcigrainehevea.com/httpdocs"

echo "1️⃣ Vérifier si le lien existe..."
if [ -L "$APP_PATH/public/storage" ]; then
    echo "   ✅ Lien symbolique existe"
    ls -la "$APP_PATH/public/storage"
    echo ""
    echo "2️⃣ Supprimer l'ancien lien..."
    rm "$APP_PATH/public/storage"
    echo "   ✅ Ancien lien supprimé"
else
    echo "   ❌ Lien symbolique n'existe pas"
fi

echo ""
echo "3️⃣ Créer le nouveau lien symbolique..."
cd "$APP_PATH"
ln -s ../storage/app/public public/storage
echo "   ✅ Nouveau lien créé"

echo ""
echo "4️⃣ Vérifier les permissions..."
chown -h $(stat -c '%U:%G' "$APP_PATH") "$APP_PATH/public/storage"
chmod 755 "$APP_PATH/storage/app/public"
echo "   ✅ Permissions ajustées"

echo ""
echo "5️⃣ Vérifier que le lien fonctionne..."
if [ -e "$APP_PATH/public/storage/cooperatives" ]; then
    echo "   ✅ Lien fonctionne - dossier cooperatives accessible"
else
    echo "   ⚠️  Dossier cooperatives non trouvé (peut être normal si aucun document)"
fi

echo ""
echo "6️⃣ Vérifier la configuration Apache/Plesk..."
echo "   Dans Plesk : Domaines → Votre domaine → Hosting Settings"
echo "   Assurez-vous que 'Follow symlinks' est activé"

echo ""
echo "✅ Correction terminée !"
echo "   Testez en accédant à : https://fphcigrainehevea.com/storage/cooperatives/documents/"


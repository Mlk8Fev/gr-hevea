#!/bin/bash
# Script pour corriger les permissions storage sur le serveur
# À exécuter sur le serveur en SSH

echo "🔧 Correction des permissions storage"
echo "===================================="

# Chemin de l'application
APP_PATH="/var/www/vhosts/fphcigrainehevea.com/httpdocs"

echo "1️⃣ Vérifier les permissions actuelles..."
ls -la "$APP_PATH/public/storage"
ls -la "$APP_PATH/storage/app/public"

echo ""
echo "2️⃣ Corriger les permissions du dossier storage/app/public..."
chmod -R 755 "$APP_PATH/storage/app/public"
chmod -R 755 "$APP_PATH/storage/app/public/cooperatives"
echo "   ✅ Permissions corrigées"

echo ""
echo "3️⃣ Corriger le propriétaire (remplacer mlkfph8 par votre utilisateur si différent)..."
# Trouver le propriétaire du dossier httpdocs
OWNER=$(stat -c '%U' "$APP_PATH")
GROUP=$(stat -c '%G' "$APP_PATH")
echo "   Propriétaire détecté : $OWNER:$GROUP"

chown -R "$OWNER:$GROUP" "$APP_PATH/storage/app/public"
chown -R "$OWNER:$GROUP" "$APP_PATH/storage/app/public/cooperatives"
echo "   ✅ Propriétaire corrigé"

echo ""
echo "4️⃣ Vérifier que le lien symbolique pointe correctement..."
if [ -L "$APP_PATH/public/storage" ]; then
    TARGET=$(readlink -f "$APP_PATH/public/storage")
    echo "   Lien pointe vers : $TARGET"
    if [ -d "$TARGET" ]; then
        echo "   ✅ Cible du lien existe"
    else
        echo "   ❌ Cible du lien n'existe pas !"
    fi
else
    echo "   ❌ public/storage n'est pas un lien symbolique"
    echo "   5️⃣ Recréer le lien symbolique..."
    cd "$APP_PATH"
    rm -f public/storage
    ln -s ../storage/app/public public/storage
    echo "   ✅ Lien recréé"
fi

echo ""
echo "6️⃣ Corriger les permissions du lien symbolique..."
chown -h "$OWNER:$GROUP" "$APP_PATH/public/storage" 2>/dev/null || echo "   ⚠️  Impossible de changer le propriétaire du lien (normal)"
chmod 755 "$APP_PATH/public/storage" 2>/dev/null || echo "   ⚠️  Impossible de changer les permissions du lien (normal)"
echo "   ✅ Permissions du lien vérifiées"

echo ""
echo "7️⃣ Vérifier l'accès aux fichiers..."
if [ -d "$APP_PATH/storage/app/public/cooperatives/documents" ]; then
    ls -la "$APP_PATH/storage/app/public/cooperatives/documents" | head -5
    echo "   ✅ Dossier documents accessible"
else
    echo "   ⚠️  Dossier documents n'existe pas (création nécessaire)"
    mkdir -p "$APP_PATH/storage/app/public/cooperatives/documents"
    chmod 755 "$APP_PATH/storage/app/public/cooperatives/documents"
    chown "$OWNER:$GROUP" "$APP_PATH/storage/app/public/cooperatives/documents"
    echo "   ✅ Dossier créé avec les bonnes permissions"
fi

echo ""
echo "✅ Correction terminée !"
echo ""
echo "📝 RÉCAPITULATIF :"
echo "   - Permissions : 755 (rwxr-xr-x)"
echo "   - Propriétaire : $OWNER:$GROUP"
echo "   - Lien symbolique : public/storage → storage/app/public"
echo ""
echo "🧪 Testez maintenant :"
echo "   https://fphcigrainehevea.com/storage/cooperatives/documents/nom_fichier.pdf"


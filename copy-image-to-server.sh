#!/bin/bash
# Script pour copier l'image selfd.png vers le serveur

echo "📦 Copie de l'image selfd.png vers le serveur"
echo "=============================================="

# Chemin local
LOCAL_IMAGE="public/wowdash/images/selfd.png"

# Vérifier que l'image existe
if [ ! -f "$LOCAL_IMAGE" ]; then
    echo "❌ Erreur: Fichier $LOCAL_IMAGE introuvable"
    exit 1
fi

echo "✅ Image trouvée : $LOCAL_IMAGE"
echo ""
echo "Pour copier vers le serveur, exécutez sur votre PC :"
echo ""
echo "scp public/wowdash/images/selfd.png root@votre-serveur:/var/www/vhosts/fphcigrainehevea.com/httpdocs/public/wowdash/images/"
echo ""
echo "OU avec Plesk File Manager, copier public/wowdash/images/selfd.png vers :"
echo "public/wowdash/images/selfd.png"


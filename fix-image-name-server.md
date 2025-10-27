# Commande à exécuter sur le serveur

## Problème détecté :
- Fichier sur le serveur : `Selfd.png` (S majuscule)
- Code cherche : `selfd.png` (s minuscule)

## Solution :
Sur le serveur, exécutez :

```bash
# Se connecter au serveur
ssh root@votre-serveur

# Aller dans le dossier images
cd /var/www/vhosts/fphcigrainehevea.com/httpdocs/public/wowdash/images/

# Renommer le fichier
mv Selfd.png selfd.png

# Vérifier les permissions
chmod 644 selfd.png
chown www-data:www-data selfd.png

# OU si Plesk
chown psacln:psacln selfd.png
```

## Vérification :
```bash
ls -la selfd.png
```

**Résultat attendu :**
```
-rw-r--r-- 1 psacln psacln 211.6 KB selfd.png
```

Ensuite, testez le PDF !


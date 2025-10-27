# Commandes à exécuter sur le serveur pour vérifier

## 1. Vérifier que le fichier a été mis à jour
```bash
# Sur le serveur
cd /var/www/vhosts/fphcigrainehevea.com/httpdocs

# Vérifier le contenu du fichier
grep -A 2 "selfd.png" resources/views/admin/producteurs/documents/pdf_self_declaration.blade.php
```

**Résultat attendu:**
```php
<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('wowdash/images/selfd.png'))) }}" class="pdf-bg" alt="background">
```

## 2. Vérifier que l'image existe
```bash
ls -la public/wowdash/images/selfd.png
```

## 3. Vérifier les permissions du fichier image
```bash
ls -la public/wowdash/images/
```

## 4. Tester si PHP peut lire le fichier
```bash
php -r "echo file_exists('/var/www/vhosts/fphcigrainehevea.com/httpdocs/public/wowdash/images/selfd.png') ? 'EXISTE' : 'N EXISTE PAS';"
```

## 5. Si le fichier n'existe pas sur le serveur
Copier depuis le dossier public/wowdash/images/ de votre installation locale


# Plan de Modification : Ajout des Reçus d'Achat dans les Documents de Traçabilité

## 📋 Objectif
Afficher les reçus d'achat des producteurs qui ont livré dans la section "Documents de traçabilité" de la vue producteur, avec les boutons "Voir" et "Télécharger PDF".

## 🔍 Analyse actuelle

### Structure existante
- **Documents de traçabilité** (ProducteurDocument) :
  - Fiche d'enquête (`fiche_enquete`)
  - Lettre d'engagement (`lettre_engagement`)
  - Self Declaration (`self_declaration`)

- **Reçus d'achat** (RecuAchat) :
  - Table séparée : `recus_achat`
  - Relation : `producteur_id` → `producteurs.id`
  - Route existante : `admin.recus-achat.show` et `admin.recus-achat.pdf`

### Fichiers concernés
1. `app/Models/Producteur.php` - Ajouter relation `recusAchat()`
2. `app/Http/Controllers/ProducteurController.php` - Charger les reçus d'achat dans `show()`
3. `resources/views/admin/producteurs/show.blade.php` - Afficher les reçus d'achat
4. `resources/views/cooperative/producteurs/show.blade.php` - Afficher les reçus d'achat (si nécessaire)

## 📝 Modifications à effectuer

### 1. Modèle Producteur (`app/Models/Producteur.php`)
**Action** : Ajouter la relation `recusAchat()`

```php
public function recusAchat()
{
    return $this->hasMany(RecuAchat::class);
}
```

### 2. Controller ProducteurController (`app/Http/Controllers/ProducteurController.php`)
**Action** : Modifier la méthode `show()` pour charger les reçus d'achat

**Ligne ~212** : Modifier
```php
$producteur = Producteur::with(['secteur','cooperatives','documents','parcelles'])->findOrFail($id);
```

**En** :
```php
$producteur = Producteur::with(['secteur','cooperatives','documents','parcelles','recusAchat'])->findOrFail($id);
```

### 3. Vue Producteur Show (`resources/views/admin/producteurs/show.blade.php`)
**Action** : Ajouter une section pour afficher les reçus d'achat après les documents de traçabilité

**Emplacement** : Après la boucle `@foreach($documentTypes as $key => $label)` (ligne ~147)

**Code à ajouter** :
```blade
{{-- Section Reçus d'achat --}}
@if($producteur->recusAchat->count() > 0)
    <li class="list-group-item border text-secondary-light p-16 bg-base">
        <div class="d-flex align-items-center gap-2">
            <i class="ri-check-line text-success text-xl"></i>
            <span class="fw-semibold">Reçus d'achat</span>
            <span class="badge bg-primary ms-2">{{ $producteur->recusAchat->count() }}</span>
        </div>
        <div class="mt-3">
            @foreach($producteur->recusAchat as $recu)
                <div class="d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                    <div>
                        <span class="fw-medium">Reçu #{{ $recu->numero_recu }}</span>
                        <small class="text-muted ms-2">
                            {{ $recu->date_creation->format('d/m/Y') }} - 
                            {{ number_format($recu->quantite_livree, 2) }} kg - 
                            {{ number_format($recu->montant_total, 0) }} FCFA
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.recus-achat.show', $recu) }}" 
                           class="btn btn-outline-primary btn-sm" 
                           target="_blank">
                            <i class="ri-eye-line"></i> Voir
                        </a>
                        <a href="{{ route('admin.recus-achat.pdf', $recu) }}" 
                           class="btn btn-outline-danger btn-sm">
                            <i class="ri-download-line"></i> Télécharger PDF
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </li>
@else
    <li class="list-group-item border text-secondary-light p-16 bg-base">
        <div class="d-flex align-items-center gap-2">
            <i class="ri-close-line text-danger text-xl"></i>
            <span class="fw-semibold">Reçus d'achat</span>
            <span class="text-muted ms-2">Aucun reçu d'achat disponible</span>
        </div>
    </li>
@endif
```

### 4. Vue Cooperative Producteur Show (`resources/views/cooperative/producteurs/show.blade.php`)
**Action** : Appliquer les mêmes modifications si cette vue existe et affiche les documents

## ✅ Points importants

1. **Relation** : Les reçus d'achat sont déjà liés aux producteurs via `producteur_id`
2. **Routes existantes** : 
   - `admin.recus-achat.show` - Affiche le reçu (http://127.0.0.1:8000/admin/recus-achat/1)
   - `admin.recus-achat.pdf` - Télécharge le PDF
3. **Affichage** : 
   - Si le producteur a des reçus → Afficher la liste avec boutons
   - Si aucun reçu → Afficher "Aucun reçu d'achat disponible"
4. **Informations affichées** :
   - Numéro de reçu
   - Date de création
   - Quantité livrée
   - Montant total

## 🎯 Résultat attendu

Dans la section "Documents de traçabilité" du producteur, on verra :
1. Fiche d'enquête
2. Lettre d'engagement
3. Self Declaration
4. **Reçus d'achat** (nouveau)
   - Liste des reçus avec numéro, date, quantité, montant
   - Bouton "Voir" (ouvre la page du reçu)
   - Bouton "Télécharger PDF" (télécharge le PDF)

## 📌 Notes

- Les reçus d'achat sont créés automatiquement lors de la création d'une farmer list
- Un producteur peut avoir plusieurs reçus d'achat (une livraison = un reçu)
- Les reçus sont triés par date de création (plus récent en premier)


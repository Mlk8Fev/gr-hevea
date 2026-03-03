# 🗺️ ROADMAP : Application Mobile Flutter + API Laravel

## 📊 ÉVALUATION INITIALE

### Application actuelle
- **Framework** : Laravel 12
- **Template** : WowDash (dashboard web)
- **Fonctionnalités principales** :
  - Gestion producteurs (CRUD complet)
  - Gestion coopératives (CRUD + documents)
  - Tickets de pesée
  - Connaissements
  - Factures
  - Finance & calculs
  - Statistiques
  - Documents (upload PDF)
  - Rôles multiples (admin, superadmin, agc, cs, rcoop)
  - Authentification 2FA par email
  - Audit logs

### Complexité estimée
- **Contrôleurs** : ~25 contrôleurs
- **Modèles** : ~18 modèles
- **Routes** : ~100+ routes web
- **Niveau** : Application métier complexe

---

## ⏱️ ESTIMATION TEMPS TOTALE

### Pour un développeur débutant en Flutter
**Temps total : 12 à 16 semaines** (3 à 4 mois)

### Pour un développeur expérimenté
**Temps total : 8 à 10 semaines** (2 à 2.5 mois)

---

## 📅 PHASE 1 : API LARAVEL (2-3 semaines)

### Semaine 1 : Setup API de base

#### Jour 1-2 : Installation & Configuration
- [ ] Installer Laravel Sanctum
  ```bash
  composer require laravel/sanctum
  php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
  php artisan migrate
  ```
- [ ] Configurer `config/sanctum.php`
- [ ] Créer `routes/api.php`
- [ ] Configurer CORS (`config/cors.php`)
- [ ] Tester avec Postman/Insomnia

**Temps estimé** : 1-2 jours

#### Jour 3-4 : Authentification API
- [ ] Créer `app/Http/Controllers/Api/AuthController.php`
  - `POST /api/login` (retourne token)
  - `POST /api/logout` (invalide token)
  - `GET /api/user` (profil utilisateur)
  - `POST /api/refresh-token` (renouveler token)
- [ ] Gérer 2FA pour mobile (code par SMS ou email)
- [ ] Middleware API pour vérifier tokens
- [ ] Tests unitaires authentification

**Temps estimé** : 2 jours

#### Jour 5 : Structure API Response
- [ ] Créer `app/Http/Resources/` (API Resources)
- [ ] Créer `app/Traits/ApiResponse.php` (format standardisé)
- [ ] Format JSON uniforme :
  ```json
  {
    "success": true,
    "data": {...},
    "message": "...",
    "errors": null
  }
  ```

**Temps estimé** : 1 jour

**Total Semaine 1** : 5 jours

---

### Semaine 2 : Endpoints principaux

#### Priorité 1 : Modules essentiels
- [ ] **Producteurs API**
  - `GET /api/producteurs` (liste avec pagination)
  - `GET /api/producteurs/{id}` (détails)
  - `POST /api/producteurs` (création)
  - `PUT /api/producteurs/{id}` (modification)
  - `DELETE /api/producteurs/{id}` (suppression)
  - `GET /api/producteurs/search?q=...` (recherche)
  - `POST /api/producteurs/{id}/documents` (upload document)

- [ ] **Coopératives API**
  - `GET /api/cooperatives`
  - `GET /api/cooperatives/{id}`
  - `POST /api/cooperatives`
  - `PUT /api/cooperatives/{id}`
  - `GET /api/cooperatives/{id}/documents`

- [ ] **Dashboard API**
  - `GET /api/dashboard` (statistiques globales)
  - `GET /api/dashboard/stats` (stats par rôle)

**Temps estimé** : 3 jours

#### Priorité 2 : Modules secondaires
- [ ] **Tickets de Pesée API**
  - `GET /api/tickets-pesee`
  - `GET /api/tickets-pesee/{id}`
  - `POST /api/tickets-pesee`
  - `PATCH /api/tickets-pesee/{id}/validate`

- [ ] **Connaissements API**
  - `GET /api/connaissements`
  - `GET /api/connaissements/{id}`
  - `POST /api/connaissements`

**Temps estimé** : 2 jours

**Total Semaine 2** : 5 jours

---

### Semaine 3 : Endpoints complémentaires

- [ ] **Factures API**
  - `GET /api/factures`
  - `GET /api/factures/{id}`
  - `POST /api/factures`
  - `GET /api/factures/{id}/pdf`

- [ ] **Finance API**
  - `GET /api/finance`
  - `GET /api/finance/calcul/{id}`

- [ ] **Statistiques API**
  - `GET /api/statistiques`
  - `GET /api/statistiques/avancees` (admin uniquement)

- [ ] **Upload fichiers**
  - Optimiser upload pour mobile (compression images)
  - Gérer PDF, images
  - Limites de taille

- [ ] **Filtres & Pagination**
  - Standardiser pagination sur tous les endpoints
  - Filtres par rôle (coopérative, secteur, etc.)

**Temps estimé** : 5 jours

**Total Phase 1** : **15 jours (3 semaines)**

---

## 📱 PHASE 2 : APPRENTISSAGE FLUTTER (1-2 semaines)

### Semaine 4 : Bases Flutter

#### Jour 1-2 : Installation & Setup
- [ ] Installer Flutter SDK
- [ ] Configurer Android Studio / VS Code
- [ ] Créer projet Flutter : `flutter create gr_hevea_mobile`
- [ ] Comprendre structure projet Flutter
- [ ] Premier "Hello World" sur émulateur

**Temps estimé** : 2 jours

#### Jour 3-4 : Dart & Widgets de base
- [ ] Apprendre syntaxe Dart (variables, fonctions, classes)
- [ ] Comprendre Widgets (StatelessWidget, StatefulWidget)
- [ ] Layouts de base (Column, Row, Container, Padding)
- [ ] Navigation simple (Navigator.push)
- [ ] Créer 2-3 écrans simples

**Temps estimé** : 2 jours

#### Jour 5 : Packages essentiels
- [ ] Installer packages :
  - `http` (appels API)
  - `shared_preferences` (stockage local)
  - `flutter_secure_storage` (tokens)
  - `provider` ou `riverpod` (state management)
- [ ] Créer service API de base
- [ ] Faire premier appel API (test login)

**Temps estimé** : 1 jour

**Total Semaine 4** : 5 jours

---

### Semaine 5 : Architecture Flutter

#### Jour 1-2 : State Management
- [ ] Choisir : Provider ou Riverpod (recommandé : Riverpod)
- [ ] Créer providers pour :
  - AuthProvider (gestion connexion)
  - UserProvider (données utilisateur)
- [ ] Comprendre `Consumer`, `ref.watch`, `ref.read`

**Temps estimé** : 2 jours

#### Jour 3-4 : Services & Models
- [ ] Créer `lib/services/api_service.dart`
- [ ] Créer `lib/models/` (User, Producteur, Cooperative, etc.)
- [ ] JSON serialization (`json_annotation`, `json_serializable`)
- [ ] Gestion erreurs API (try/catch, exceptions)

**Temps estimé** : 2 jours

#### Jour 5 : Navigation & Routing
- [ ] Installer `go_router` ou `auto_route`
- [ ] Configurer routes principales
- [ ] Navigation avec paramètres
- [ ] Guards de route (vérifier auth)

**Temps estimé** : 1 jour

**Total Semaine 5** : 5 jours

**Total Phase 2** : **10 jours (2 semaines)**

---

## 📱 PHASE 3 : APPLICATION MOBILE - CORE (3-4 semaines)

### Semaine 6 : Authentification Mobile

#### Jour 1-2 : Écran Login
- [ ] UI Login (champs email/password)
- [ ] Validation formulaire
- [ ] Appel API `/api/login`
- [ ] Stockage token sécurisé
- [ ] Gestion erreurs (mauvais credentials)

**Temps estimé** : 2 jours

#### Jour 3 : 2FA Mobile
- [ ] Écran saisie code 2FA
- [ ] Appel API vérification code
- [ ] Redirection après validation

**Temps estimé** : 1 jour

#### Jour 4-5 : Navigation & Splash
- [ ] Splash screen
- [ ] Vérifier token au démarrage
- [ ] Redirection automatique (login si pas de token)
- [ ] Logout fonctionnel

**Temps estimé** : 2 jours

**Total Semaine 6** : 5 jours

---

### Semaine 7 : Dashboard Mobile

#### Jour 1-2 : Écran Dashboard
- [ ] UI Dashboard (cartes statistiques)
- [ ] Appel API `/api/dashboard`
- [ ] Affichage stats par rôle
- [ ] Refresh pull-to-refresh

**Temps estimé** : 2 jours

#### Jour 3-4 : Navigation principale
- [ ] Bottom Navigation Bar ou Drawer
- [ ] Menu selon rôle utilisateur
- [ ] Icônes & design cohérent

**Temps estimé** : 2 jours

#### Jour 5 : Profil utilisateur
- [ ] Écran profil
- [ ] Affichage infos utilisateur
- [ ] Déconnexion

**Temps estimé** : 1 jour

**Total Semaine 7** : 5 jours

---

### Semaine 8 : Gestion Producteurs

#### Jour 1-2 : Liste Producteurs
- [ ] Écran liste avec pagination
- [ ] Recherche (search bar)
- [ ] Filtres (statut, secteur, coopérative)
- [ ] Pull-to-refresh
- [ ] Loading states

**Temps estimé** : 2 jours

#### Jour 3 : Détails Producteur
- [ ] Écran détails complet
- [ ] Affichage toutes infos
- [ ] Liste documents associés
- [ ] Bouton modifier (si permissions)

**Temps estimé** : 1 jour

#### Jour 4-5 : Création/Modification
- [ ] Formulaire création producteur
- [ ] Validation champs
- [ ] Upload photo (caméra/galerie)
- [ ] Upload documents PDF
- [ ] Appel API POST/PUT

**Temps estimé** : 2 jours

**Total Semaine 8** : 5 jours

---

### Semaine 9 : Gestion Coopératives

#### Jour 1-2 : Liste Coopératives
- [ ] Écran liste coopératives
- [ ] Recherche & filtres
- [ ] Pagination

**Temps estimé** : 2 jours

#### Jour 3 : Détails Coopérative
- [ ] Écran détails
- [ ] Informations complètes
- [ ] Documents (liste + téléchargement)

**Temps estimé** : 1 jour

#### Jour 4-5 : CRUD Coopératives
- [ ] Formulaire création/modification
- [ ] Upload documents PDF
- [ ] Gestion distances centres collecte

**Temps estimé** : 2 jours

**Total Semaine 9** : 5 jours

---

### Semaine 10 : Modules complémentaires

#### Jour 1-2 : Tickets de Pesée
- [ ] Liste tickets
- [ ] Détails ticket
- [ ] Création ticket
- [ ] Validation (si permissions)

**Temps estimé** : 2 jours

#### Jour 3 : Connaissements
- [ ] Liste connaissements
- [ ] Détails connaissement
- [ ] Programmation transport

**Temps estimé** : 1 jour

#### Jour 4-5 : Factures
- [ ] Liste factures
- [ ] Détails facture
- [ ] Génération PDF (affichage)
- [ ] Validation paiement

**Temps estimé** : 2 jours

**Total Semaine 10** : 5 jours

**Total Phase 3** : **25 jours (5 semaines)**

---

## 🗺️ PHASE 4 : GÉOLOCALISATION (1 semaine)

### Semaine 11 : Implémentation GPS

#### Jour 1 : Setup packages
- [ ] Installer `geolocator` (GPS)
- [ ] Installer `permission_handler` (permissions)
- [ ] Installer `geocoding` (adresses)
- [ ] Configurer permissions Android/iOS

**Temps estimé** : 1 jour

#### Jour 2 : Backend - Table & Migration
- [ ] Créer migration `localisations`
  ```php
  Schema::create('localisations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained();
      $table->decimal('latitude', 10, 8);
      $table->decimal('longitude', 11, 8);
      $table->decimal('precision', 8, 2)->nullable();
      $table->string('adresse')->nullable();
      $table->timestamp('created_at');
  });
  ```
- [ ] Créer modèle `Localisation`
- [ ] Relations User -> Localisations

**Temps estimé** : 1 jour

#### Jour 3 : API Géolocalisation
- [ ] `POST /api/localisations` (enregistrer position)
  ```json
  {
    "latitude": 5.12345678,
    "longitude": -4.12345678,
    "precision": 10.5,
    "adresse": "Abidjan, Cocody"
  }
  ```
- [ ] `GET /api/localisations` (historique utilisateur)
- [ ] `GET /api/localisations/{id}` (détails)
- [ ] `GET /api/localisations/current` (dernière position)

**Temps estimé** : 1 jour

#### Jour 4 : Mobile - Service GPS
- [ ] Créer `lib/services/location_service.dart`
- [ ] Demander permissions GPS
- [ ] Récupérer position actuelle
- [ ] Gérer erreurs (GPS désactivé, permissions refusées)
- [ ] Test sur émulateur & device réel

**Temps estimé** : 1 jour

#### Jour 5 : Mobile - UI & Envoi
- [ ] Écran géolocalisation (bouton "Enregistrer position")
- [ ] Affichage coordonnées
- [ ] Carte (optionnel : `google_maps_flutter`)
- [ ] Envoi automatique ou manuel
- [ ] Historique positions (liste)

**Temps estimé** : 1 jour

**Total Phase 4** : **5 jours (1 semaine)**

---

## 🎨 PHASE 5 : UX & POLISH (1-2 semaines)

### Semaine 12 : Amélioration UX

#### Jour 1-2 : Design System
- [ ] Définir couleurs, polices, espacements
- [ ] Créer widgets réutilisables (boutons, cartes, inputs)
- [ ] Thème clair/sombre (optionnel)

**Temps estimé** : 2 jours

#### Jour 3 : Gestion erreurs
- [ ] Messages erreurs utilisateur-friendly
- [ ] Retry automatique sur erreur réseau
- [ ] Offline mode (cache données)

**Temps estimé** : 1 jour

#### Jour 4-5 : Performance
- [ ] Optimiser images (compression, cache)
- [ ] Lazy loading listes
- [ ] Pagination infinie
- [ ] Réduire appels API inutiles

**Temps estimé** : 2 jours

**Total Semaine 12** : 5 jours

---

### Semaine 13 : Tests & Debug

#### Jour 1-2 : Tests fonctionnels
- [ ] Tester tous les écrans
- [ ] Tester tous les rôles (admin, agc, cs, rcoop)
- [ ] Tester sur Android & iOS
- [ ] Tester sur différentes tailles d'écran

**Temps estimé** : 2 jours

#### Jour 3-4 : Corrections bugs
- [ ] Corriger bugs identifiés
- [ ] Améliorer gestion erreurs
- [ ] Optimiser performances

**Temps estimé** : 2 jours

#### Jour 5 : Documentation
- [ ] Documenter API (Swagger/Postman)
- [ ] Guide utilisateur mobile (optionnel)
- [ ] README projet Flutter

**Temps estimé** : 1 jour

**Total Semaine 13** : 5 jours

**Total Phase 5** : **10 jours (2 semaines)**

---

## 📦 PHASE 6 : DÉPLOIEMENT (1 semaine)

### Semaine 14 : Build & Publication

#### Jour 1-2 : Build Android
- [ ] Configurer `android/app/build.gradle`
- [ ] Générer keystore
- [ ] Build APK/AAB
- [ ] Test APK sur device réel

**Temps estimé** : 2 jours

#### Jour 3-4 : Build iOS (si nécessaire)
- [ ] Configurer Xcode
- [ ] Certificats & provisioning
- [ ] Build IPA
- [ ] Test sur iPhone réel

**Temps estimé** : 2 jours

#### Jour 5 : Publication
- [ ] Upload Google Play Console (Android)
- [ ] Upload App Store Connect (iOS)
- [ ] Métadonnées (description, screenshots)
- [ ] Soumission review

**Temps estimé** : 1 jour

**Total Phase 6** : **5 jours (1 semaine)**

---

## 📊 RÉCAPITULATIF TEMPS

| Phase | Description | Temps (semaines) |
|-------|-------------|------------------|
| **Phase 1** | API Laravel | 3 semaines |
| **Phase 2** | Apprentissage Flutter | 2 semaines |
| **Phase 3** | App Mobile Core | 5 semaines |
| **Phase 4** | Géolocalisation | 1 semaine |
| **Phase 5** | UX & Polish | 2 semaines |
| **Phase 6** | Déploiement | 1 semaine |
| **TOTAL** | | **14 semaines (3.5 mois)** |

---

## 🎯 ORDRE D'IMPLÉMENTATION RECOMMANDÉ

### Sprint 1 (Semaines 1-3) : API Laravel
1. Setup Sanctum
2. Authentification API
3. Endpoints producteurs
4. Endpoints coopératives
5. Endpoints dashboard

### Sprint 2 (Semaines 4-5) : Apprentissage Flutter
1. Bases Flutter/Dart
2. Architecture projet
3. State management
4. Services API

### Sprint 3 (Semaines 6-7) : Auth & Dashboard Mobile
1. Écran login
2. Navigation
3. Dashboard mobile

### Sprint 4 (Semaines 8-9) : Modules principaux
1. Gestion producteurs (CRUD)
2. Gestion coopératives (CRUD)

### Sprint 5 (Semaine 10) : Modules secondaires
1. Tickets pesée
2. Connaissements
3. Factures

### Sprint 6 (Semaine 11) : Géolocalisation
1. Backend API
2. Service GPS mobile
3. UI géolocalisation

### Sprint 7 (Semaines 12-13) : Finalisation
1. UX improvements
2. Tests
3. Corrections bugs

### Sprint 8 (Semaine 14) : Déploiement
1. Build Android/iOS
2. Publication stores

---

## ⚠️ POINTS D'ATTENTION

### Pour un débutant Flutter
- **Ajouter 20-30% de temps** sur Phase 2 et Phase 3
- **Total réaliste : 16-18 semaines** (4-4.5 mois)

### Risques identifiés
1. **Complexité authentification 2FA** : Peut prendre plus de temps
2. **Upload fichiers mobile** : Gestion caméra/galerie peut être complexe
3. **Permissions GPS** : Différences Android/iOS
4. **Rôles multiples** : Logique conditionnelle complexe

### Recommandations
- **Commencer simple** : Faire fonctionner login + dashboard avant tout
- **Tester régulièrement** : Sur device réel, pas seulement émulateur
- **Documenter au fur et à mesure** : Notes sur décisions techniques
- **Versionner** : Git avec commits clairs

---

## 📚 RESSOURCES APPRENTISSAGE FLUTTER

### Pour débuter
1. **Flutter Official Docs** : https://docs.flutter.dev
2. **Dart Language Tour** : https://dart.dev/guides
3. **Flutter YouTube** : Chaîne officielle Google
4. **Riverpod** : https://riverpod.dev (state management)

### Packages essentiels
- `http` : Appels API
- `riverpod` : State management
- `go_router` : Navigation
- `geolocator` : GPS
- `permission_handler` : Permissions
- `shared_preferences` : Stockage local
- `flutter_secure_storage` : Tokens sécurisés
- `image_picker` : Caméra/galerie
- `file_picker` : Sélection fichiers

---

## ✅ CHECKLIST FINALE

Avant de considérer le projet terminé :

- [ ] Tous les endpoints API fonctionnent
- [ ] Authentification mobile complète
- [ ] Tous les écrans principaux implémentés
- [ ] Géolocalisation fonctionnelle
- [ ] Tests sur Android & iOS
- [ ] Gestion erreurs robuste
- [ ] Performance acceptable
- [ ] Documentation API complète
- [ ] Build production réussi
- [ ] Application publiée (ou prête à publier)

---

**🎉 Bon courage pour ce projet ambitieux !**


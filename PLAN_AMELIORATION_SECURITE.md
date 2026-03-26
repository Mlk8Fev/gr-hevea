# 🔒 PLAN D'AMÉLIORATION CYBERSÉCURITÉ & ISO 27001
## Application GR-HEVEA - Roadmap d'Amélioration Continue

---

## 📋 OBJECTIFS

- **Court terme** : Améliorer la conformité aux exigences Mandatory et Highly Recommended
- **Moyen terme** : Atteindre 95%+ de conformité fonctionnelle
- **Long terme** : Obtenir certification ISO 27001 et maintenir un niveau de sécurité de niveau entreprise

**Conformité actuelle** : 72% (ISO 27001) / 89% (Cyber Security)

---

## 🎯 PRIORISATION

### 🔴 PRIORITÉ CRITIQUE (À faire immédiatement)
- Expiration et historique des mots de passe
- Formalisation des politiques de sécurité
- Documentation des procédures

### 🟡 PRIORITÉ HAUTE (1-3 mois)
- Monitoring amélioré
- Scan antivirus fichiers uploadés
- Tests de pénétration internes approfondis

### 🟢 PRIORITÉ MOYENNE (3-6 mois)
- OAuth/SAML integration
- Key Vault dédié
- Chiffrement données au repos

### 🔵 PRIORITÉ BASSE (6-12 mois)
- Tests de pénétration externes
- Certification ISO 27001
- Monitoring 24/7 avancé

---

## 📅 PLAN D'ACTION DÉTAILLÉ

### 🔴 SEMAINE 1-2 : POLITIQUE DES MOTS DE PASSE

#### Tâche 1.1 : Activer l'expiration automatique des mots de passe
**Fichiers à modifier** :
- `app/Models/User.php` - Ajouter champ `password_expires_at`
- `database/migrations/XXXX_add_password_expiration_to_users.php` - Migration
- `app/Http/Middleware/CheckPasswordExpiration.php` - Nouveau middleware
- `app/Http/Kernel.php` - Enregistrer le middleware

**Actions** :
1. Créer migration pour ajouter `password_expires_at` et `password_changed_at` à la table `users`
2. Ajouter validation dans le modèle User pour vérifier expiration (90 jours par défaut)
3. Créer middleware `CheckPasswordExpiration` qui vérifie si le mot de passe a expiré
4. Rediriger vers page de changement de mot de passe si expiré
5. Ajouter notification email 7 jours avant expiration

**Estimation** : 4-6 heures

---

#### Tâche 1.2 : Implémenter l'historique des mots de passe
**Fichiers à modifier** :
- `database/migrations/XXXX_create_password_history_table.php` - Nouvelle table
- `app/Models/PasswordHistory.php` - Nouveau modèle
- `app/Models/User.php` - Relation avec PasswordHistory
- `app/Http/Controllers/Auth/ResetPasswordController.php` - Vérifier historique

**Actions** :
1. Créer table `password_history` (user_id, password_hash, created_at)
2. Créer modèle PasswordHistory avec relation vers User
3. Lors du changement de mot de passe, vérifier que le nouveau n'est pas dans les 5 derniers
4. Stocker les 5 derniers mots de passe hachés
5. Afficher message d'erreur si mot de passe réutilisé

**Estimation** : 3-4 heures

---

#### Tâche 1.3 : Renforcer la politique de complexité des mots de passe
**Fichiers à modifier** :
- `app/Http/Requests/Auth/RegisterRequest.php` - Validation
- `app/Http/Requests/Auth/ResetPasswordRequest.php` - Validation
- `app/Http/Requests/Admin/UserRequest.php` - Validation

**Actions** :
1. Augmenter minimum à 10 caractères (au lieu de 8)
2. Exiger au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial
3. Ajouter validation côté client (JavaScript) pour feedback immédiat
4. Afficher indicateur de force du mot de passe

**Estimation** : 2-3 heures

---

### 🔴 SEMAINE 3-4 : DOCUMENTATION ET POLITIQUES

#### Tâche 2.1 : Créer document de politique de sécurité
**Fichiers à créer** :
- `docs/POLITIQUE_SECURITE.md` - Politique générale
- `docs/PROCEDURE_GESTION_MOTS_DE_PASSE.md` - Procédures mots de passe
- `docs/PROCEDURE_GESTION_INCIDENTS.md` - Procédures incidents

**Contenu à inclure** :
1. Politique générale de sécurité de l'information
2. Rôles et responsabilités
3. Procédures de gestion des accès
4. Procédures de gestion des incidents
5. Procédures de sauvegarde et restauration
6. Procédures de gestion des vulnérabilités

**Estimation** : 6-8 heures

---

#### Tâche 2.2 : Documenter les procédures de continuité
**Fichiers à créer** :
- `docs/PLAN_CONTINUITE.md` - Plan de continuité
- `docs/PROCEDURE_SAUVEGARDE.md` - Procédures sauvegarde
- `docs/PROCEDURE_RESTAURATION.md` - Procédures restauration

**Contenu à inclure** :
1. Objectifs de continuité (RTO, RPO)
2. Procédures de sauvegarde (fréquence, rétention)
3. Procédures de restauration (étapes détaillées)
4. Tests de restauration (calendrier, résultats)
5. Plan de communication en cas d'incident

**Estimation** : 4-5 heures

---

#### Tâche 2.3 : Créer guide de formation utilisateurs
**Fichiers à créer** :
- `docs/GUIDE_FORMATION_UTILISATEURS.md` - Guide formation
- `resources/views/help/security-guide.blade.php` - Page d'aide dans l'app

**Contenu à inclure** :
1. Bonnes pratiques de sécurité
2. Comment créer un mot de passe fort
3. Comment utiliser le 2FA
4. Comment reconnaître les tentatives de phishing
5. Que faire en cas d'incident de sécurité

**Estimation** : 3-4 heures

---

### 🟡 SEMAINE 5-6 : MONITORING ET DÉTECTION

#### Tâche 3.1 : Intégrer Sentry pour monitoring d'erreurs
**Fichiers à modifier** :
- `composer.json` - Ajouter sentry/sentry-laravel
- `config/sentry.php` - Configuration Sentry
- `.env` - Ajouter SENTRY_DSN

**Actions** :
1. Installer package Sentry Laravel
2. Configurer Sentry avec DSN
3. Ajouter tracking des erreurs critiques
4. Configurer alertes email/SMS pour erreurs critiques
5. Créer dashboard de monitoring

**Estimation** : 3-4 heures

---

#### Tâche 3.2 : Améliorer les logs d'audit
**Fichiers à modifier** :
- `app/Services/AuditService.php` - Améliorer logging
- `app/Http/Middleware/LogUserActivity.php` - Nouveau middleware
- `database/migrations/XXXX_add_fields_to_audit_logs.php` - Ajouter champs

**Actions** :
1. Ajouter IP, User-Agent, géolocalisation aux logs
2. Créer middleware pour logger toutes les actions importantes
3. Ajouter recherche et filtres dans l'interface d'audit
4. Créer alertes automatiques pour activités suspectes
5. Exporter logs pour analyse externe

**Estimation** : 5-6 heures

---

#### Tâche 3.3 : Créer dashboard de sécurité
**Fichiers à créer** :
- `app/Http/Controllers/Admin/SecurityDashboardController.php` - Controller
- `resources/views/admin/security/dashboard.blade.php` - Vue dashboard

**Fonctionnalités** :
1. Statistiques de connexions (succès/échecs)
2. Tentatives d'accès suspectes
3. Sessions actives
4. Utilisateurs avec mots de passe expirés
5. Alertes de sécurité récentes

**Estimation** : 6-8 heures

---

### 🟡 SEMAINE 7-8 : PROTECTION DES FICHIERS

#### Tâche 4.1 : Intégrer scan antivirus pour uploads
**Fichiers à modifier** :
- `app/Http/Controllers/FileUploadController.php` - Ajouter scan
- `app/Services/VirusScanService.php` - Nouveau service
- `composer.json` - Ajouter package scan (ex: ClamAV wrapper)

**Actions** :
1. Rechercher service de scan antivirus (ClamAV, VirusTotal API, etc.)
2. Créer service VirusScanService
3. Scanner chaque fichier uploadé avant stockage
4. Rejeter fichiers infectés avec message d'erreur
5. Logger les tentatives d'upload de fichiers infectés

**Estimation** : 6-8 heures

---

#### Tâche 4.2 : Améliorer validation des uploads
**Fichiers à modifier** :
- `app/Http/Requests/FileUploadRequest.php` - Validation renforcée
- `app/Services/FileValidationService.php` - Service de validation

**Actions** :
1. Vérifier signature de fichier (magic bytes) en plus du MIME type
2. Limiter taille maximale par type de fichier
3. Scanner contenu des fichiers pour code malveillant
4. Renommer fichiers avec hash pour éviter collisions
5. Stocker métadonnées (taille, type, hash) pour traçabilité

**Estimation** : 4-5 heures

---

### 🟢 SEMAINE 9-12 : IDENTITY FEDERATION

#### Tâche 5.1 : Intégrer OAuth 2.0 (Google, Microsoft)
**Fichiers à modifier** :
- `composer.json` - Ajouter laravel/socialite
- `config/services.php` - Configuration OAuth
- `app/Http/Controllers/Auth/SocialAuthController.php` - Nouveau controller
- `resources/views/auth/login.blade.php` - Ajouter boutons OAuth

**Actions** :
1. Installer Laravel Socialite
2. Configurer providers OAuth (Google, Microsoft)
3. Créer routes et controller pour authentification sociale
4. Gérer création/compte existant avec OAuth
5. Permettre liaison compte OAuth à compte existant
6. Ajouter boutons "Se connecter avec Google/Microsoft"

**Estimation** : 8-10 heures

---

#### Tâche 5.2 : Implémenter SAML 2.0 (optionnel)
**Fichiers à modifier** :
- `composer.json` - Ajouter package SAML
- `config/saml.php` - Configuration SAML
- `app/Http/Controllers/Auth/SamlController.php` - Controller SAML

**Actions** :
1. Rechercher package SAML pour Laravel (ex: aacotroneo/laravel-saml2)
2. Configurer Identity Provider (IdP)
3. Créer endpoints SAML (SSO, SLO, Metadata)
4. Gérer assertions SAML et mapping des attributs
5. Tester avec IdP de test

**Estimation** : 12-16 heures (si nécessaire)

---

### 🟢 SEMAINE 13-16 : CHIFFREMENT ET KEY VAULT

#### Tâche 6.1 : Implémenter chiffrement des données sensibles au repos
**Fichiers à modifier** :
- `app/Models/User.php` - Chiffrer champs sensibles
- `app/Models/Producteur.php` - Chiffrer données personnelles
- `config/encryption.php` - Configuration chiffrement

**Actions** :
1. Identifier données sensibles à chiffrer (emails, contacts, adresses)
2. Utiliser Laravel Encryption pour chiffrer/déchiffrer
3. Créer accessors/mutators pour chiffrement automatique
4. Chiffrer données existantes via migration
5. Tester performance avec données chiffrées

**Estimation** : 10-12 heures

---

#### Tâche 6.2 : Intégrer Key Vault pour credentials
**Fichiers à modifier** :
- `config/vault.php` - Configuration Key Vault
- `app/Services/VaultService.php` - Service Key Vault
- `.env` - Configuration credentials Key Vault

**Options** :
1. **Option 1** : Utiliser AWS Secrets Manager ou Azure Key Vault (cloud)
2. **Option 2** : Utiliser HashiCorp Vault (self-hosted)
3. **Option 3** : Créer solution simple avec chiffrement Laravel

**Actions** :
1. Choisir solution Key Vault
2. Créer service VaultService pour stocker/récupérer secrets
3. Migrer credentials sensibles vers Key Vault
4. Mettre à jour code pour utiliser Key Vault
5. Documenter procédures de gestion des secrets

**Estimation** : 12-16 heures

---

### 🔵 SEMAINE 17-20 : TESTS DE SÉCURITÉ

#### Tâche 7.1 : Tests de pénétration internes approfondis
**Fichiers à créer** :
- `tests/Security/PenetrationTest.php` - Tests automatisés
- `docs/RAPPORT_TESTS_PENETRATION.md` - Rapport tests

**Actions** :
1. Utiliser outils OWASP ZAP ou Burp Suite
2. Tester toutes les routes pour vulnérabilités
3. Tester uploads de fichiers malveillants
4. Tester injections SQL, XSS, CSRF
5. Tester contrôle d'accès horizontal/vertical
6. Documenter résultats et corrections

**Estimation** : 16-20 heures

---

#### Tâche 7.2 : Audit de code de sécurité
**Fichiers à créer** :
- `docs/AUDIT_CODE_SECURITE.md` - Rapport audit

**Actions** :
1. Utiliser outils SAST (Static Application Security Testing)
2. Analyser code avec SonarQube ou PHPStan
3. Vérifier conformité aux standards OWASP
4. Identifier vulnérabilités potentielles
5. Corriger vulnérabilités identifiées

**Estimation** : 12-16 heures

---

#### Tâche 7.3 : Planifier tests de pénétration externes
**Actions** :
1. Rechercher fournisseurs qualifiés pour tests externes
2. Obtenir devis et calendrier
3. Préparer environnement de test
4. Participer aux tests
5. Implémenter corrections recommandées

**Estimation** : Variable (externalisé)

---

### 🔵 SEMAINE 21-24 : CERTIFICATION ET OPTIMISATION

#### Tâche 8.1 : Préparation certification ISO 27001
**Fichiers à créer** :
- `docs/ISO27001_CHECKLIST.md` - Checklist conformité
- `docs/ISO27001_EVIDENCES.md` - Preuves de conformité

**Actions** :
1. Compléter tous les points PARTIALLY en YES
2. Documenter toutes les mesures de sécurité
3. Préparer preuves de conformité
4. Contacter organisme de certification
5. Passer audit de certification

**Estimation** : Variable (processus long)

---

#### Tâche 8.2 : Monitoring 24/7 avancé
**Fichiers à modifier** :
- `app/Console/Commands/MonitorSecurity.php` - Commande monitoring
- `app/Services/SecurityMonitoringService.php` - Service monitoring

**Actions** :
1. Configurer monitoring continu (UptimeRobot, Pingdom)
2. Créer alertes automatiques pour incidents
3. Configurer notifications multi-canaux (email, SMS, Slack)
4. Créer dashboard temps réel
5. Automatiser réponses aux incidents courants

**Estimation** : 10-12 heures

---

## 📊 TABLEAU DE SUIVI

| Tâche | Priorité | Statut | Date Début | Date Fin | Notes |
|-------|----------|--------|------------|----------|-------|
| 1.1 - Expiration mots de passe | 🔴 Critique | ⬜ À faire | | | |
| 1.2 - Historique mots de passe | 🔴 Critique | ⬜ À faire | | | |
| 1.3 - Complexité mots de passe | 🔴 Critique | ⬜ À faire | | | |
| 2.1 - Politique sécurité | 🔴 Critique | ⬜ À faire | | | |
| 2.2 - Plan continuité | 🔴 Critique | ⬜ À faire | | | |
| 2.3 - Guide formation | 🔴 Critique | ⬜ À faire | | | |
| 3.1 - Sentry monitoring | 🟡 Haute | ⬜ À faire | | | |
| 3.2 - Logs audit améliorés | 🟡 Haute | ⬜ À faire | | | |
| 3.3 - Dashboard sécurité | 🟡 Haute | ⬜ À faire | | | |
| 4.1 - Scan antivirus | 🟡 Haute | ⬜ À faire | | | |
| 4.2 - Validation uploads | 🟡 Haute | ⬜ À faire | | | |
| 5.1 - OAuth 2.0 | 🟢 Moyenne | ⬜ À faire | | | |
| 5.2 - SAML 2.0 | 🟢 Moyenne | ⬜ Optionnel | | | |
| 6.1 - Chiffrement données | 🟢 Moyenne | ⬜ À faire | | | |
| 6.2 - Key Vault | 🟢 Moyenne | ⬜ À faire | | | |
| 7.1 - Tests pénétration internes | 🔵 Basse | ⬜ À faire | | | |
| 7.2 - Audit code | 🔵 Basse | ⬜ À faire | | | |
| 7.3 - Tests pénétration externes | 🔵 Basse | ⬜ À faire | | | |
| 8.1 - Certification ISO 27001 | 🔵 Basse | ⬜ À faire | | | |
| 8.2 - Monitoring 24/7 | 🔵 Basse | ⬜ À faire | | | |

**Légende** :
- ⬜ À faire
- 🟦 En cours
- ✅ Terminé
- ❌ Bloqué

---

## 🎯 OBJECTIFS PAR TRIMESTRE

### Trimestre 1 (Semaines 1-12)
- ✅ Expiration et historique des mots de passe
- ✅ Documentation des politiques et procédures
- ✅ Monitoring amélioré avec Sentry
- ✅ Scan antivirus pour uploads
- **Objectif** : Passer de 72% à 85% de conformité ISO 27001

### Trimestre 2 (Semaines 13-24)
- ✅ OAuth 2.0 intégré
- ✅ Chiffrement données au repos
- ✅ Key Vault implémenté
- ✅ Tests de pénétration internes
- **Objectif** : Passer de 85% à 92% de conformité ISO 27001

### Trimestre 3 (Semaines 25-36)
- ✅ Tests de pénétration externes
- ✅ Monitoring 24/7 opérationnel
- ✅ Préparation certification ISO 27001
- **Objectif** : Atteindre 95%+ de conformité et obtenir certification

---

## 📝 NOTES IMPORTANTES

1. **Commencez par les priorités critiques** : Les tâches marquées 🔴 doivent être faites en premier
2. **Testez après chaque modification** : Ne jamais déployer sans tests
3. **Documentez au fur et à mesure** : Mettre à jour la documentation en même temps que le code
4. **Revue de code** : Faire revue de code pour les modifications de sécurité
5. **Backup avant modifications** : Toujours faire backup avant modifications importantes
6. **Suivi régulier** : Mettre à jour le tableau de suivi chaque semaine

---

## 🔄 PROCESSUS DE TRAVAIL RECOMMANDÉ

### Pour chaque tâche :
1. **Lire** la tâche et comprendre les objectifs
2. **Planifier** les étapes de développement
3. **Développer** en suivant les bonnes pratiques
4. **Tester** localement avant déploiement
5. **Documenter** les changements
6. **Déployer** sur environnement de test
7. **Valider** sur environnement de test
8. **Déployer** sur production
9. **Marquer** la tâche comme terminée dans le tableau

---

## 📚 RESSOURCES UTILES

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security Documentation](https://laravel.com/docs/security)
- [ISO 27001 Standard](https://www.iso.org/standard/54534.html)
- [NIST Cybersecurity Framework](https://www.nist.gov/cyberframework)

---

*Document créé le : 2026-03-04*  
*Dernière mise à jour : 2026-03-04*  
*Version : 1.0*


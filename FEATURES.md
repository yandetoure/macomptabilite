# 📋 Fonctionnalités de l'Application de Comptabilité

## ✨ Vue d'ensemble

Application complète de gestion comptable développée avec Laravel 12, offrant une comptabilité en partie double automatique, gestion des factures, paiements et un système innovant de cards paramétrables.

## 🎯 Fonctionnalités principales

### 👥 Gestion des Tiers

#### Clients
- ✅ Création et gestion des clients
- ✅ Informations complètes (nom, email, téléphone, adresse)
- ✅ Numéro de TVA/SIRET
- ✅ Historique des factures
- ✅ Suivi des montants en attente
- ✅ Statut actif/inactif

#### Fournisseurs
- ✅ Création et gestion des fournisseurs
- ✅ Mêmes informations que les clients
- ✅ Historique des achats
- ✅ Suivi des paiements dus

### 📄 Gestion des Factures

#### Factures Clients
- ✅ Création manuelle de factures
- ✅ Sélection client depuis la base
- ✅ Numérotation automatique (FC-YYYY-0001)
- ✅ Date de facture et d'échéance
- ✅ Montant total
- ✅ Statut : Brouillon, En attente, Partiel, Payé
- ✅ Upload de fichier (PDF, JPG, PNG)
- ✅ **Écriture comptable automatique** :
  - Débit 411 (Clients)
  - Crédit 701 (Ventes)

#### Factures Fournisseurs
- ✅ Création de factures fournisseur
- ✅ Upload du fichier facture
- ✅ Numérotation automatique (FF-YYYY-0001)
- ✅ Même fonctionnalités que factures clients
- ✅ **Écriture comptable automatique** :
  - Débit 601 (Achats)
  - Crédit 401 (Fournisseurs)

### 💰 Gestion des Paiements

#### Enregistrement de paiement
- ✅ Méthodes multiples : Banque, Caisse, Chèque, Virement, Autre
- ✅ Montant partiel ou total
- ✅ Date de paiement
- ✅ Référence et notes
- ✅ Numérotation automatique (PAY-YYYY-0001)
- ✅ Lien avec la facture

#### Écritures de paiement automatiques

**Pour facture client :**
- Débit : Banque (512) ou Caisse (531)
- Crédit : Clients (411)

**Pour facture fournisseur :**
- Débit : Fournisseurs (401)
- Crédit : Banque (512) ou Caisse (531)

### 📊 Comptabilité en Partie Double

#### Plan Comptable
- ✅ Structure hiérarchique des comptes
- ✅ Types de comptes : Actif, Passif, Capitaux propres, Produits, Charges
- ✅ Code comptable unique
- ✅ Solde en temps réel
- ✅ Statut actif/inactif
- ✅ 25 comptes pré-configurés

#### Écritures Comptables
- ✅ Génération automatique depuis factures et paiements
- ✅ Numérotation unique (JE-YYYY-0001)
- ✅ Lignes d'écriture (débit/crédit)
- ✅ Vérification automatique de l'équilibre
- ✅ Statut : Brouillon, Comptabilisé
- ✅ Référence et description
- ✅ **Mise à jour automatique des soldes**

#### Règles de calcul des soldes
- **Actif et Charges** : Débit augmente, Crédit diminue
- **Passif, Capitaux propres et Produits** : Crédit augmente, Débit diminue

### 🎴 Système de Cards Paramétrables

#### Fonctionnalités des cards
- ✅ Création de cards personnalisées
- ✅ Configuration des comptes débit/crédit par défaut
- ✅ Types : Facture client, Facture fournisseur, Cash, Banque, Custom
- ✅ Icône et couleur personnalisables
- ✅ Ordre d'affichage
- ✅ Description
- ✅ Statut actif/inactif

#### Utilisation
- ✅ Saisie rapide de transaction
- ✅ Montant, date, référence, description
- ✅ **Écriture automatique** avec les comptes pré-configurés
- ✅ Parfait pour opérations répétitives

#### Cards pré-configurées
1. **Encaissement Banque** (🏦)
   - Débit : 512 (Banque)
   - Crédit : 411 (Clients)

2. **Encaissement Cash** (💵)
   - Débit : 531 (Caisse)
   - Crédit : 411 (Clients)

3. **Paiement Fournisseur Banque** (🏧)
   - Débit : 401 (Fournisseurs)
   - Crédit : 512 (Banque)

4. **Paiement Fournisseur Cash** (💸)
   - Débit : 401 (Fournisseurs)
   - Crédit : 531 (Caisse)

### 🔐 Authentification

#### Connexion classique
- ✅ Email et mot de passe
- ✅ Vérification d'email
- ✅ Réinitialisation de mot de passe
- ✅ Remember me

#### Google OAuth
- ✅ Configuration complète
- ✅ Connexion en un clic
- ✅ Création automatique de compte
- ✅ Récupération avatar et informations
- ✅ Stockage du Google ID

### 📈 Dashboard

#### Statistiques
- ✅ Nombre de clients actifs
- ✅ Nombre de fournisseurs actifs
- ✅ Montant factures clients en attente
- ✅ Montant factures fournisseurs en attente
- ✅ Total paiements du mois
- ✅ Nombre d'écritures comptabilisées

#### Affichages
- ✅ 10 dernières factures
- ✅ 10 derniers paiements
- ✅ Cards actives pour actions rapides
- ✅ Statuts en couleur
- ✅ Navigation rapide

### 🎨 Interface Utilisateur

#### Design
- ✅ Tailwind CSS moderne
- ✅ Mode sombre inclus
- ✅ Design responsive (mobile, tablette, desktop)
- ✅ Composants réutilisables
- ✅ Notifications de succès/erreur
- ✅ Formulaires validés

#### Navigation
- ✅ Menu principal avec toutes les sections
- ✅ Breadcrumbs
- ✅ Liens rapides
- ✅ Boutons d'action visibles

## 🔧 Architecture Technique

### Backend
- **Framework** : Laravel 12
- **PHP** : 8.2+ avec typage strict
- **Base de données** : SQLite/MySQL/PostgreSQL
- **ORM** : Eloquent avec relations complètes
- **Validation** : Form Requests
- **Services** : AccountingService pour logique métier

### Frontend
- **CSS** : Tailwind CSS
- **JS** : Vite + Alpine.js (via Breeze)
- **Templates** : Blade
- **Icons** : Emojis pour simplicité

### Sécurité
- ✅ Authentification Breeze
- ✅ CSRF Protection
- ✅ Password Hashing (Bcrypt)
- ✅ Validation des entrées
- ✅ Protection XSS
- ✅ Middleware auth

### Base de données

#### Tables créées
1. `users` - Utilisateurs (avec google_id, avatar)
2. `customers` - Clients
3. `suppliers` - Fournisseurs
4. `accounts` - Plan comptable
5. `invoices` - Factures
6. `payments` - Paiements
7. `journal_entries` - Écritures comptables
8. `journal_entry_lines` - Lignes d'écritures
9. `accounting_cards` - Cards paramétrables

#### Relations
- ✅ One-to-Many : User → Invoices, Customer → Invoices, etc.
- ✅ Many-to-One : Invoice → Customer, Payment → Invoice
- ✅ Self-referencing : Account → Parent Account
- ✅ Foreign keys avec CASCADE/SET NULL

## 🚀 Workflow Complet

### Scénario : Vente à un client

1. **Créer le client**
   - Nom, email, téléphone, etc.

2. **Créer facture client**
   - Montant : 1000 €
   - Écriture auto :
     - Débit 411 : 1000 €
     - Crédit 701 : 1000 €
   - Statut : "En attente"

3. **Client paie par virement**
   - Marquer facture comme payée
   - Montant : 1000 €
   - Méthode : Banque
   - Écriture auto :
     - Débit 512 : 1000 €
     - Crédit 411 : 1000 €
   - Statut facture : "Payé"

4. **Résultat final**
   - Compte 411 (Clients) : soldé (1000 - 1000 = 0)
   - Compte 701 (Ventes) : +1000 € (crédit)
   - Compte 512 (Banque) : +1000 € (débit)

### Scénario : Achat chez fournisseur

1. **Créer le fournisseur**

2. **Créer facture fournisseur**
   - Upload du PDF
   - Montant : 500 €
   - Écriture auto :
     - Débit 601 : 500 €
     - Crédit 401 : 500 €

3. **Payer par chèque**
   - Marquer comme payé
   - Méthode : Chèque
   - Écriture auto :
     - Débit 401 : 500 €
     - Crédit 512 : 500 €

4. **Résultat**
   - Compte 401 (Fournisseurs) : soldé
   - Compte 601 (Achats) : +500 € (débit)
   - Compte 512 (Banque) : -500 € (crédit)

### Scénario : Utilisation d'une card

1. **Sélectionner card "Encaissement Cash"**

2. **Saisir transaction**
   - Montant : 200 €
   - Description : "Vente au comptant"

3. **Écriture générée**
   - Débit 531 (Caisse) : 200 €
   - Crédit 411 (Clients) : 200 €

4. **Opération soldée automatiquement**

## 📊 Rapports et Exports (À développer)

### Rapports comptables
- ⏳ Bilan comptable
- ⏳ Compte de résultat
- ⏳ Grand livre
- ⏳ Balance générale
- ⏳ Journal

### Exports
- ⏳ Export PDF
- ⏳ Export Excel
- ⏳ Export FEC (Fichier des Écritures Comptables)

## 🎯 Points forts du système

1. **Automatisation** : Les écritures comptables sont générées automatiquement
2. **Cohérence** : La partie double garantit l'équilibre comptable
3. **Flexibilité** : Les cards permettent de créer des raccourcis personnalisés
4. **Simplicité** : Interface intuitive, pas besoin d'être comptable
5. **Traçabilité** : Toutes les opérations sont liées (facture → paiement → écriture)
6. **Sécurité** : Validation à tous les niveaux

## 🔜 Évolutions possibles

- Multi-devises
- TVA automatique
- Relances clients automatiques
- Rapports graphiques avancés
- API REST
- Application mobile
- Multi-entreprises
- Intégration bancaire (Open Banking)
- OCR pour extraction de données de factures

---

**L'application est prête à gérer votre comptabilité de manière professionnelle et automatisée !** 🎉


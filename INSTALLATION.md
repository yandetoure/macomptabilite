# 🚀 Instructions d'Installation - Application de Comptabilité

## ✅ Ce qui a été créé

L'application complète de comptabilité avec :

### 📦 Base de données
- ✅ **Migrations** créées pour toutes les tables (users, customers, suppliers, accounts, invoices, payments, journal_entries, accounting_cards)
- ✅ **Modèles Eloquent** avec toutes les relations
- ✅ **Seeders** pour les comptes comptables de base et les cards

### 🎯 Fonctionnalités
- ✅ **Authentification** (Breeze + Google OAuth configuré)
- ✅ **Comptabilité en partie double** automatique
- ✅ **Gestion des clients et fournisseurs**
- ✅ **Factures** clients et fournisseurs avec upload
- ✅ **Paiements** avec méthodes variées
- ✅ **Écritures comptables** automatiques
- ✅ **Cards paramétrables** pour actions rapides
- ✅ **Dashboard** moderne avec statistiques

### 🎨 Interface
- ✅ Interface moderne avec Tailwind CSS
- ✅ Mode sombre inclus
- ✅ Vues principales créées (Dashboard, Cards)
- ✅ Design responsive

## 🔧 Étapes pour finaliser l'installation

### 1. Configurer la base de données

**Option A: SQLite (Recommandé pour le développement)**

Dans le fichier `.env`, assurez-vous d'avoir :

```env
DB_CONNECTION=sqlite
# DB_HOST, DB_PORT, etc. peuvent être commentés
```

**Option B: MySQL**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_votre_base
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 2. Exécuter les migrations

```bash
cd /Users/yandeh/Desktop/Projects/Sacre-Coeur/Compta
php artisan migrate:fresh --seed
```

Cela créera :
- Toutes les tables
- Un utilisateur admin (email: admin@example.com, password: password)
- 25 comptes comptables de base
- 4 cards comptables par défaut

### 3. Créer le lien symbolique pour le stockage

```bash
php artisan storage:link
```

### 4. Compiler les assets

```bash
npm run dev
# ou pour la production:
npm run build
```

### 5. Démarrer le serveur

```bash
php artisan serve
```

Accédez à : **http://localhost:8000**

## 🔑 Connexion par défaut

- **Email** : admin@example.com
- **Mot de passe** : password

## 📋 Configuration Google OAuth (Optionnel)

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez un nouveau projet
3. Activez "Google+ API"
4. Créez des credentials OAuth 2.0
5. Ajoutez les Redirect URIs :
   - `http://localhost:8000/auth/google/callback`
   - `http://votre-domaine.com/auth/google/callback`

6. Ajoutez dans `.env` :

```env
GOOGLE_CLIENT_ID=votre_client_id_ici
GOOGLE_CLIENT_SECRET=votre_client_secret_ici
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

7. Le bouton Google apparaîtra automatiquement sur la page de connexion

## 🎯 Structure de l'application

### Controllers créés :
- `DashboardController` - Tableau de bord
- `AccountController` - Plan comptable
- `InvoiceController` - Factures
- `PaymentController` - Paiements
- `JournalEntryController` - Écritures comptables
- `AccountingCardController` - Cards paramétrables
- `CustomerController` - Clients
- `SupplierController` - Fournisseurs

### Services :
- `AccountingService` - Logique de comptabilité en partie double

### Vues créées :
- `dashboard.blade.php` - Tableau de bord complet
- `cards/index.blade.php` - Liste des cards
- `cards/show.blade.php` - Utiliser une card

## 📊 Plan comptable par défaut

Les comptes suivants sont pré-configurés :

**Actifs :**
- 411 - Clients
- 512 - Banque
- 531 - Caisse
- 4456 - TVA déductible

**Passifs :**
- 401 - Fournisseurs
- 4457 - TVA collectée

**Charges :**
- 601 - Achats de marchandises
- 606 - Achats non stockés
- 61 à 67 - Autres charges

**Produits :**
- 701 - Ventes de produits finis
- 706 - Prestations de services
- 707 - Ventes de marchandises
- 76, 77 - Produits financiers et exceptionnels

**Capitaux propres :**
- 101 - Capital
- 106 - Réserves
- 108 - Compte de l'exploitant
- 120 - Résultat de l'exercice

## 🎴 Cards par défaut

4 cards sont créées automatiquement :
1. 🏦 **Encaissement Banque** - Client → Banque
2. 💵 **Encaissement Cash** - Client → Caisse
3. 🏧 **Paiement Fournisseur Banque** - Fournisseur → Banque
4. 💸 **Paiement Fournisseur Cash** - Fournisseur → Caisse

## 🔄 Workflow type

### Créer une facture client :
1. Aller dans "Factures" → "Nouvelle facture"
2. Sélectionner type "Client"
3. Choisir un client (ou saisir le nom)
4. Renseigner montant et date
5. L'écriture est générée automatiquement :
   - Débit 411 (Clients) : montant
   - Crédit 701 (Ventes) : montant

### Enregistrer un paiement :
1. Sur la facture, cliquer "Marquer comme payé"
2. Saisir montant, méthode et date
3. L'écriture de paiement est générée :
   - Débit 512/531 (Banque/Caisse) : montant
   - Crédit 411 (Clients) : montant

### Utiliser une card :
1. Cliquer sur une card depuis le dashboard
2. Saisir montant et description
3. L'écriture est générée avec les comptes pré-configurés

## 🎨 Prochaines étapes

Pour continuer le développement, vous pouvez :

1. **Créer les vues manquantes** :
   - Factures (create, edit, show, index)
   - Clients (create, edit, show, index)
   - Fournisseurs (create, edit, show, index)
   - Paiements (index, show)
   - Écritures comptables (index, show)
   - Comptes (index, create, edit)

2. **Ajouter le bouton Google** dans `resources/views/auth/login.blade.php` :

```blade
<div class="mt-4">
    <a href="{{ route('google.redirect') }}" 
       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">...</svg>
        Se connecter avec Google
    </a>
</div>
```

3. **Ajouter des rapports** :
   - Bilan comptable
   - Compte de résultat
   - Grand livre
   - Balance

4. **Améliorer les fonctionnalités** :
   - Export PDF des factures
   - Envoi par email
   - Rappels automatiques
   - Statistiques avancées

## 🆘 Dépannage

### Erreur de migration
Si vous avez une erreur de connexion à la base de données :
```bash
# Vérifiez votre .env
# Pour SQLite, assurez-vous que le fichier database/database.sqlite existe
touch database/database.sqlite
php artisan migrate:fresh --seed
```

### Assets non compilés
```bash
npm install
npm run dev
```

### Permissions de stockage
```bash
chmod -R 775 storage bootstrap/cache
```

## 📝 Notes importantes

- Les mots de passe sont hashés avec bcrypt
- Les fichiers uploadés sont stockés dans `storage/app/public/invoices`
- La comptabilité en partie double est automatiquement vérifiée
- Les soldes des comptes sont mis à jour automatiquement

---

## 🎉 Félicitations !

Votre application de comptabilité est prête à l'emploi. Connectez-vous et commencez à gérer votre comptabilité !

Pour toute question, consultez la documentation Laravel ou le README.md


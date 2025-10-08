# Application de Comptabilité Laravel

Une application complète de gestion comptable avec comptabilité en partie double, gestion des factures, paiements et système de cards paramétrables.

## 🚀 Fonctionnalités

- ✅ **Authentification complète** (Email/Mot de passe + Google OAuth)
- ✅ **Gestion des clients et fournisseurs**
- ✅ **Factures clients et fournisseurs**
- ✅ **Gestion des paiements** avec différentes méthodes (Banque, Caisse, Chèque, etc.)
- ✅ **Comptabilité en partie double** automatique
- ✅ **Écritures comptables** (Journal)
- ✅ **Plan comptable** hiérarchique
- ✅ **Cards comptables paramétrables** pour actions rapides
- ✅ **Upload de factures** (PDF, JPG, PNG)
- ✅ **Dashboard** avec statistiques et aperçus
- ✅ **Interface moderne** avec Tailwind CSS et mode sombre

## 📋 Prérequis

- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (ou MySQL/PostgreSQL)

## 🛠️ Installation

### 1. Cloner le projet

```bash
cd /Users/yandeh/Desktop/Projects/Sacre-Coeur/Compta
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configuration de l'environnement

Si le fichier `.env` n'existe pas, copiez le fichier `.env.example` :

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configuration de la base de données

Pour utiliser SQLite (recommandé pour le développement) :

Éditez le fichier `.env` :

```env
DB_CONNECTION=sqlite
# Commentez les lignes MySQL suivantes :
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

Le fichier `database/database.sqlite` existe déjà.

### 5. Configuration Google OAuth (Optionnel)

Pour activer l'authentification Google :

1. Créez une application sur [Google Cloud Console](https://console.cloud.google.com/)
2. Ajoutez les Redirect URIs :
   - `http://localhost:8000/auth/google/callback`
   - `http://localhost/auth/google/callback`

3. Ajoutez dans `.env` :

```env
GOOGLE_CLIENT_ID=votre_client_id
GOOGLE_CLIENT_SECRET=votre_client_secret
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

4. Ajoutez la route OAuth dans `routes/auth.php` :

```php
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();
    
    $user = User::updateOrCreate([
        'email' => $googleUser->email,
    ], [
        'name' => $googleUser->name,
        'google_id' => $googleUser->id,
        'avatar' => $googleUser->avatar,
        'password' => bcrypt(Str::random(24))
    ]);
    
    Auth::login($user);
    
    return redirect('/dashboard');
});
```

### 6. Exécuter les migrations et seeders

```bash
php artisan migrate:fresh --seed
```

Cela créera :
- Les tables de base de données
- Un utilisateur admin (email: `admin@example.com`, password: `password`)
- Les comptes comptables de base (401, 411, 512, 531, 601, 701, etc.)

### 7. Créer le lien symbolique pour le stockage

```bash
php artisan storage:link
```

### 8. Compiler les assets

```bash
npm run dev
# ou pour la production
npm run build
```

### 9. Démarrer le serveur

```bash
php artisan serve
```

L'application sera accessible sur : `http://localhost:8000`

## 👤 Connexion par défaut

- **Email** : admin@example.com
- **Mot de passe** : password

## 📊 Structure de la base de données

### Tables principales :

- **users** - Utilisateurs de l'application
- **customers** - Clients
- **suppliers** - Fournisseurs
- **accounts** - Plan comptable (comptes avec hiérarchie)
- **invoices** - Factures clients et fournisseurs
- **payments** - Paiements
- **journal_entries** - Écritures comptables
- **journal_entry_lines** - Lignes d'écritures (débit/crédit)
- **accounting_cards** - Cards paramétrables

## 🎨 Fonctionnement des Cards Comptables

Les cards permettent de créer des actions rapides pour enregistrer des opérations comptables :

1. **Créer une card** depuis le menu Cards
2. Définir :
   - Nom de la card (ex: "Encaissement Cash")
   - Type (Cash, Banque, Custom, etc.)
   - Compte au débit par défaut
   - Compte au crédit par défaut
   - Couleur et icône

3. Utiliser la card pour enregistrer rapidement une transaction

Exemple : Card "Encaissement Cash"
- Débit : 531 (Caisse)
- Crédit : 411 (Client)
- Chaque transaction solde automatiquement les deux comptes

## 📝 Workflow comptable

### Facture Client :
1. Créer une facture client
2. L'écriture est générée automatiquement :
   - Débit 411 (Clients)
   - Crédit 701 (Ventes)

### Paiement de facture client :
1. Marquer la facture comme payée
2. Saisir le montant et la méthode de paiement
3. L'écriture de paiement est générée :
   - Débit 512 ou 531 (Banque/Caisse)
   - Crédit 411 (Clients)

### Facture Fournisseur :
1. Créer une facture fournisseur (avec upload possible)
2. L'écriture est générée :
   - Débit 601 (Achats)
   - Crédit 401 (Fournisseurs)

### Paiement fournisseur :
1. Marquer la facture comme payée
2. L'écriture est générée :
   - Débit 401 (Fournisseurs)
   - Crédit 512 ou 531 (Banque/Caisse)

## 🔧 Comptes comptables par défaut

- **401** : Fournisseurs (Passif)
- **411** : Clients (Actif)
- **512** : Banque (Actif)
- **531** : Caisse (Actif)
- **601** : Achats de marchandises (Charges)
- **701** : Ventes de produits finis (Produits)

Vous pouvez ajouter d'autres comptes depuis le menu "Plan Comptable".

## 🎯 Prochaines étapes

Pour continuer le développement :

1. Créer les vues pour :
   - Clients (`resources/views/customers/`)
   - Fournisseurs (`resources/views/suppliers/`)
   - Factures (`resources/views/invoices/`)
   - Paiements (`resources/views/payments/`)
   - Écritures comptables (`resources/views/journal-entries/`)
   - Cards (`resources/views/cards/`)

2. Ajouter les boutons Google OAuth dans les vues de login/register

3. Personnaliser les couleurs et le logo de l'application

4. Ajouter des rapports comptables (bilan, compte de résultat, etc.)

## 📱 Captures d'écran

L'application utilise Tailwind CSS avec un design moderne et un mode sombre. Le dashboard affiche :
- Statistiques des clients et fournisseurs
- Factures en attente
- Paiements récents
- Cards comptables pour actions rapides

## 🤝 Support

Pour toute question ou assistance, n'hésitez pas à consulter la documentation Laravel ou à créer une issue.

## 📄 Licence

Ce projet est open source et disponible sous la licence MIT.
# macomptabilite

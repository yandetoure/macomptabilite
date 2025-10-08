# 🔐 Configuration Google OAuth - Guide Complet

## 📋 Étape 1 : Configuration Google Cloud Console

### 1.1 Créer un projet Google Cloud

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Cliquez sur "Sélectionner un projet" → "Nouveau projet"
3. Nom du projet : `Compta-App` (ou votre choix)
4. Cliquez sur "Créer"

### 1.2 Activer l'API Google+

1. Dans le menu ☰, allez dans "API et services" → "Bibliothèque"
2. Recherchez "Google+ API"
3. Cliquez sur "Activer"

### 1.3 Créer les identifiants OAuth 2.0

1. Menu ☰ → "API et services" → "Identifiants"
2. Cliquez sur "+ Créer des identifiants" → "ID client OAuth"
3. Si demandé, configurez l'écran de consentement :
   - Type : Externe
   - Nom de l'application : `Compta App`
   - Email d'assistance : votre email
   - Domaine autorisé : localhost (pour dev)
   - Enregistrer et continuer
4. Sélectionnez "Application Web"
5. Nom : `Compta Web Client`

### 1.4 Configurer les Redirect URIs

Ajoutez ces URIs autorisées :

**Pour le développement local :**
```
http://localhost:8000/auth/google/callback
```

**Pour la production :**
```
https://votre-domaine.com/auth/google/callback
```

6. Cliquez sur "Créer"
7. **Copiez le Client ID et le Client Secret** (vous en aurez besoin)

## 📝 Étape 2 : Configuration Laravel

### 2.1 Fichier .env

Ajoutez ces lignes dans votre fichier `.env` :

```env
GOOGLE_CLIENT_ID=votre_client_id_ici.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=votre_client_secret_ici
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

**Remplacez** les valeurs par celles obtenues de Google Cloud Console.

### 2.2 Vérifier la configuration

Le fichier `config/services.php` est déjà configuré avec :

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URL'),
],
```

### 2.3 Les routes sont déjà créées

Dans `routes/auth.php`, ces routes existent déjà :

```php
Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('auth/google/callback', function () {
    // ... logique d'authentification
})->name('google.callback');
```

## 🎨 Étape 3 : Ajouter le bouton Google dans la page de connexion

### 3.1 Ouvrir le fichier de login

Fichier : `resources/views/auth/login.blade.php`

### 3.2 Ajouter le bouton Google

Ajoutez ce code **APRÈS** le bouton "Log in" et **AVANT** la fermeture du form :

```blade
<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<!-- ... formulaire email/password existant ... -->

<div class="flex items-center justify-end mt-4">
    <!-- ... bouton Log in existant ... -->
</div>

<!-- NOUVEAU : Séparateur -->
<div class="relative my-6">
    <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    <div class="relative flex justify-center text-sm">
        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">
            Ou continuer avec
        </span>
    </div>
</div>

<!-- NOUVEAU : Bouton Google -->
<div>
    <a href="{{ route('google.redirect') }}" 
       class="w-full inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Se connecter avec Google
    </a>
</div>
```

### 3.3 Version complète du bouton (optionnelle, plus stylisée)

Si vous voulez un bouton encore plus joli :

```blade
<a href="{{ route('google.redirect') }}" 
   class="group relative w-full flex justify-center py-3 px-4 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
        <!-- Logo Google SVG -->
        <svg class="h-5 w-5" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
    </span>
    Continuer avec Google
</a>
```

## 🎨 Étape 4 : Ajouter sur la page d'inscription (optionnel)

Faites la même chose dans `resources/views/auth/register.blade.php` si vous voulez permettre l'inscription via Google.

## ✅ Étape 5 : Tester

### 5.1 Vérifier la configuration

```bash
# Dans le terminal
php artisan config:clear
php artisan route:list | grep google
```

Vous devriez voir :
```
GET|HEAD  auth/google ................. google.redirect
GET|HEAD  auth/google/callback ....... google.callback
```

### 5.2 Tester la connexion

1. Démarrez le serveur : `php artisan serve`
2. Allez sur : http://localhost:8000/login
3. Vous devriez voir le bouton "Se connecter avec Google"
4. Cliquez dessus
5. Vous serez redirigé vers Google
6. Après connexion, retour sur le dashboard

### 5.3 Vérifier en base de données

L'utilisateur créé aura :
- `email` : votre email Google
- `name` : votre nom Google
- `google_id` : ID unique de Google
- `avatar` : URL de votre photo de profil
- `email_verified_at` : Date actuelle (auto-vérifié)

## 🔧 Dépannage

### Erreur : "Client ID not found"

**Solution** : Vérifiez votre `.env`, le Client ID doit être exact

```bash
php artisan config:clear
php artisan cache:clear
```

### Erreur : "Redirect URI mismatch"

**Solution** : Dans Google Cloud Console, vérifiez que l'URI est **exactement** :
```
http://localhost:8000/auth/google/callback
```

Pas de `/` à la fin, port 8000 si vous utilisez `php artisan serve`.

### Erreur : "This app isn't verified"

**Solution** : 
- En développement, c'est normal
- Cliquez sur "Advanced" puis "Go to [App Name] (unsafe)"
- En production, vous devrez faire vérifier votre app par Google

### Le bouton Google n'apparaît pas

**Vérifications** :
1. Le code est bien dans `resources/views/auth/login.blade.php`
2. Vous avez vidé le cache : `php artisan view:clear`
3. Vous êtes bien sur `/login` et non `/register`

## 🎯 Résumé

Après avoir suivi ce guide :

✅ Google Cloud Console configuré
✅ Credentials OAuth créées
✅ `.env` configuré avec Client ID et Secret
✅ Routes Google OAuth actives
✅ Bouton Google sur la page de connexion
✅ Utilisateurs peuvent se connecter avec Google en 1 clic

## 🚀 Pour la production

Avant de déployer :

1. **Changez le Redirect URI** dans Google Cloud Console
2. **Mettez à jour `.env`** avec la nouvelle URL
3. **Vérifiez l'app** auprès de Google si nécessaire
4. **Configurez le domaine** dans l'écran de consentement

---

**Votre authentification Google OAuth est maintenant prête ! 🎉**


# 💰 Gestion des Paiements de Factures

## ✅ Fonctionnalité Complète : Marquer et Annuler les Paiements

### 🎯 Workflow Complet

---

## 📝 1. Marquer une Facture comme Payée

### Accès
Sur la page d'une facture (`/invoices/{id}`) :
- **Si non payée** → Bouton "Marquer comme payé" (vert)

### Formulaire de Paiement (Modal)
Champs à remplir :
- **Date de paiement** (requis)
- **Montant** (requis, pré-rempli avec le reste à payer)
- **Méthode de paiement** (requis) :
  - 💵 Espèces (Cash)
  - 🏦 Banque (Bank)
  - 📜 Chèque (Check)
  - 💳 Virement (Transfer)
  - 🔧 Autre (Other)
- **Référence** (optionnel)
- **Notes** (optionnel)

### Actions Automatiques

#### 1️⃣ Création du Paiement
```php
Payment créé avec :
- payment_number : PAY-2025-0001 (auto-généré)
- payment_date
- amount
- payment_method
- invoice_id (lien avec la facture)
- reference
- notes
- created_by (utilisateur connecté)
```

#### 2️⃣ Mise à jour de la Facture
```php
$invoice->paid_amount += $amount;

if ($invoice->paid_amount >= $invoice->total_amount) {
    $invoice->status = 'paid'; // Payé
} elseif ($invoice->paid_amount > 0) {
    $invoice->status = 'partial'; // Partiel
}
```

#### 3️⃣ Création de l'Écriture Comptable Automatique

**Pour une Facture Client :**
```
Débit  512/531 (Banque/Caisse)  : Montant payé
Crédit 411 (Clients)            : Montant payé

Description: "Paiement facture FC-2025-0001"
Référence: payment_number
```

**Pour une Facture Fournisseur :**
```
Débit  401 (Fournisseurs)       : Montant payé
Crédit 512/531 (Banque/Caisse)  : Montant payé

Description: "Paiement facture FF-2025-0001"
Référence: payment_number
```

---

## 🔄 2. Annuler un Paiement

### Accès
Sur la page d'une facture payée :
- **Si payée/partielle** → Bouton "Annuler le paiement" (rouge)

### Confirmation avec SweetAlert2
Popup élégante affichant :
```
⚠️ Annuler le paiement ?

Cette action va :
- Supprimer tous les paiements liés
- Supprimer les écritures comptables
- Inverser les soldes des comptes
- Remettre la facture en "En attente"

[Non, conserver]  [Oui, annuler le paiement]
```

### Actions Automatiques (en Transaction)

#### 1️⃣ Récupération des Paiements
```php
$payments = $invoice->payments; // Tous les paiements liés
```

#### 2️⃣ Pour Chaque Paiement
```php
foreach ($payments as $payment) {
    // Récupérer les écritures liées
    $journalEntries = $payment->journalEntries;
    
    // Pour chaque écriture
    foreach ($journalEntries as $entry) {
        // Inverser les soldes des comptes
        foreach ($entry->lines as $line) {
            $account = $line->account;
            
            if (in_array($account->type, ['asset', 'expense'])) {
                // Actif/Charge: inverse débit/crédit
                $account->balance = $account->balance - $line->debit + $line->credit;
            } else {
                // Passif/Produit/Capitaux: inverse crédit/débit
                $account->balance = $account->balance - $line->credit + $line->debit;
            }
            
            $account->save();
        }
        
        // Supprimer l'écriture
        $entry->delete();
    }
    
    // Supprimer le paiement
    $payment->delete();
}
```

#### 3️⃣ Réinitialisation de la Facture
```php
$invoice->update([
    'paid_amount' => 0,
    'status' => 'pending', // Remise en attente
]);
```

---

## 🔍 Détails Techniques

### Routes
```php
// Marquer comme payé
POST /invoices/{invoice}/mark-paid

// Annuler paiement
POST /invoices/{invoice}/cancel-payment
```

### Contrôleur : `InvoiceController`

#### Méthode `markPaid()`
- Valide les données du formulaire
- Crée le paiement
- Met à jour la facture
- Crée l'écriture comptable automatique
- Redirige avec message de succès

#### Méthode `cancelPayment()`
- Vérifie que la facture est payée/partielle
- Transaction DB pour garantir la cohérence
- Inverse les soldes des comptes
- Supprime écritures et paiements
- Réinitialise la facture
- Redirige avec message de succès

### Service : `AccountingService`

#### Méthode `createEntryFromPayment()`
```php
public function createEntryFromPayment(
    Payment $payment, 
    int $debitAccountId, 
    int $creditAccountId
): JournalEntry
```

Crée une écriture équilibrée avec :
- Lien vers le paiement (`payment_id`)
- Date de l'écriture = date du paiement
- Référence = numéro du paiement
- Description automatique
- Statut = `posted` (comptabilisé)
- Mise à jour des soldes des comptes

---

## 💼 Cas d'Usage Pratiques

### Scénario 1 : Paiement Client Simple

**Facture** : FC-2025-0001, Client : ABC Corp, Montant : 100 000 FCFA

1. **Marquer comme payé** :
   - Date : 08/10/2025
   - Montant : 100 000 FCFA
   - Méthode : Banque
   
2. **Résultat** :
   - ✅ Paiement créé : PAY-2025-0001
   - ✅ Facture statut = `paid`
   - ✅ Écriture créée :
     ```
     Débit  512 (Banque)  : 100 000 FCFA
     Crédit 411 (Clients) : 100 000 FCFA
     ```
   - ✅ Compte 512 : +100 000
   - ✅ Compte 411 : -100 000

### Scénario 2 : Paiement Partiel

**Facture** : FC-2025-0002, Montant : 200 000 FCFA

1. **Premier paiement** :
   - Montant : 100 000 FCFA
   - Statut facture → `partial`
   
2. **Deuxième paiement** :
   - Montant : 100 000 FCFA
   - Statut facture → `paid`

### Scénario 3 : Annulation de Paiement

**Facture payée** : FC-2025-0001, Payée 100 000 FCFA par banque

1. **Clic sur "Annuler le paiement"**
   
2. **Confirmation SweetAlert2** → Accepter
   
3. **Résultat** :
   - ✅ Paiement supprimé
   - ✅ Écriture supprimée
   - ✅ Compte 512 : -100 000 (inverse)
   - ✅ Compte 411 : +100 000 (inverse)
   - ✅ Facture : statut = `pending`, paid_amount = 0

---

## 🎨 Interface Utilisateur

### Boutons Conditionnels
```blade
@if($invoice->status != 'paid')
    <!-- Bouton vert -->
    <button onclick="..." class="bg-green-600">
        Marquer comme payé
    </button>
@else
    <!-- Bouton rouge -->
    <button onclick="confirmCancelPayment()" class="bg-red-600">
        Annuler le paiement
    </button>
@endif
```

### Modal de Paiement
- Fond semi-transparent
- Formulaire centré
- Champs validés
- Boutons Annuler/Enregistrer

### Popup de Confirmation (SweetAlert2)
- Icône warning jaune
- Titre en rouge
- Liste des actions
- Boutons stylisés (rouge/gris)
- Animation fluide

---

## 🔐 Sécurité et Validation

### Validation des Données
```php
$request->validate([
    'payment_date' => 'required|date',
    'amount' => 'required|numeric|min:0',
    'payment_method' => 'required|in:cash,bank,check,transfer,other',
    'reference' => 'nullable|string|max:255',
    'notes' => 'nullable|string',
]);
```

### Vérifications
- Facture doit exister
- Montant > 0
- Utilisateur authentifié
- Pour annulation : statut doit être `paid` ou `partial`

### Transaction Database
```php
DB::transaction(function () use ($invoice) {
    // Toutes les opérations ici
    // Si erreur → rollback automatique
});
```

---

## 📊 Impact Comptable

### Comptes Affectés

**Paiement Client** :
- 🏦 512 (Banque) ou 💵 531 (Caisse) → Augmente (Débit)
- 👤 411 (Clients) → Diminue (Crédit)

**Paiement Fournisseur** :
- 🏭 401 (Fournisseurs) → Diminue (Débit)
- 🏦 512 (Banque) ou 💵 531 (Caisse) → Diminue (Crédit)

### Rapports Impactés
- ✅ Balance générale (soldes mis à jour)
- ✅ Bilan (trésorerie et créances/dettes)
- ✅ Flux de trésorerie (entrées/sorties)

---

## 🎯 Points Clés

✅ **Automatisation totale** : Écritures créées/supprimées automatiquement  
✅ **Cohérence garantie** : Transactions DB, validation stricte  
✅ **Traçabilité** : Lien paiement ↔ écriture ↔ facture  
✅ **Réversibilité** : Annulation propre avec inversion des soldes  
✅ **UX moderne** : SweetAlert2, confirmation claire  
✅ **Multiméthode** : Cash, Bank, Check, Transfer, Other  
✅ **Paiements partiels** : Statut `partial` géré automatiquement  

---

## 🚀 Utilisation Rapide

### Payer une Facture Client
1. Aller sur la facture
2. Cliquer "Marquer comme payé"
3. Remplir le formulaire
4. Enregistrer
→ ✅ Tout est créé automatiquement !

### Annuler un Paiement
1. Aller sur la facture payée
2. Cliquer "Annuler le paiement"
3. Confirmer dans la popup
→ ✅ Tout est annulé proprement !

---

**Le système de paiement est complet, automatisé et réversible ! 🎉💰**


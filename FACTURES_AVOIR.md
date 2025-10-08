# 📄 Factures d'Avoir - Documentation

## ✅ Fonctionnalité Ajoutée : Factures d'Avoir

Les factures d'avoir (avoirs/credit notes) permettent d'annuler ou de rembourser une facture.

---

## 🎯 Qu'est-ce qu'une facture d'avoir ?

Une **facture d'avoir** est un document comptable qui :
- Annule tout ou partie d'une facture
- Permet un remboursement client ou fournisseur
- Génère une écriture comptable **inversée**

---

## 🔧 Comment créer une facture d'avoir

### Étape 1 : Créer la facture d'avoir

```
Factures → + Nouvelle Facture
```

1. **Type de facture** : Client ou Fournisseur
2. **☑️ Cocher "Facture d'avoir"** 
3. Sélectionner le client/fournisseur
4. Montant de l'avoir
5. Description (ex: "Retour marchandises", "Erreur de facturation")
6. Enregistrer

→ Numéro généré : `AV-2025-0001` (au lieu de FC ou FF)

### Étape 2 : Écriture automatique

L'écriture comptable est **automatiquement inversée** :

#### Avoir Client (annulation de vente)
**Facture normale** :
```
Débit  411 (Clients)  : +100 000
Crédit 701 (Ventes)   : +100 000
```

**Avoir (inversion)** :
```
Débit  701 (Ventes)   : +100 000  ← Diminue les ventes
Crédit 411 (Clients)  : +100 000  ← Diminue la créance client
```

#### Avoir Fournisseur (annulation d'achat)
**Facture normale** :
```
Débit  601 (Achats)       : +100 000
Crédit 401 (Fournisseurs) : +100 000
```

**Avoir (inversion)** :
```
Débit  401 (Fournisseurs) : +100 000  ← Diminue la dette
Crédit 601 (Achats)       : +100 000  ← Diminue les achats
```

---

## 📊 Numérotation

### Factures normales
- Client : `FC-2025-0001`, `FC-2025-0002`, etc.
- Fournisseur : `FF-2025-0001`, `FF-2025-0002`, etc.

### Factures d'avoir
- **Avoir** : `AV-2025-0001`, `AV-2025-0002`, etc.

(Numérotation séparée pour les avoirs)

---

## 🎨 Affichage Visuel

### Dans la liste des factures
- Badge **"AVOIR"** en rouge sur le numéro
- Facilement identifiable

### Sur la facture
- Titre avec badge "AVOIR" rouge
- Description indique "Facture d'avoir - Client/Fournisseur"

---

## 💼 Cas d'usage

### Scénario 1 : Retour de marchandises

1. Client retourne des marchandises pour 50 000 FCFA
2. Créer avoir client :
   - Type : Client
   - ☑️ Facture d'avoir
   - Montant : 50 000 FCFA
   - Description : "Retour marchandises défectueuses"

**Écriture générée** :
```
Débit  701 (Ventes)  : 50 000 FCFA
Crédit 411 (Clients) : 50 000 FCFA
```

**Résultat** :
- Ventes diminuées de 50 000
- Créance client diminuée de 50 000

### Scénario 2 : Erreur de facturation

1. Erreur sur facture fournisseur (trop facturé)
2. Fournisseur émet un avoir de 25 000 FCFA
3. Créer avoir fournisseur :
   - Type : Fournisseur
   - ☑️ Facture d'avoir
   - Montant : 25 000 FCFA

**Écriture générée** :
```
Débit  401 (Fournisseurs) : 25 000 FCFA
Crédit 601 (Achats)       : 25 000 FCFA
```

**Résultat** :
- Achats diminués de 25 000
- Dette fournisseur diminuée de 25 000

---

## ✅ Corrections Effectuées

### 1. Erreur `party_name` doesn't have default value
**Problème** : Le champ était vide lors de la création

**Solution** : 
- `party_name` est maintenant automatiquement rempli depuis le client/fournisseur sélectionné
- Valeur par défaut : "Client" ou "Fournisseur" si pas de sélection

### 2. Checkbox "Facture d'avoir"
**Ajouté** :
- Checkbox dans le formulaire de création
- Badge rouge pour identification visuelle
- Numérotation séparée (AV-)

### 3. Écritures automatiques inversées
**Nouveau** :
- 2 nouvelles méthodes dans `AccountingService` :
  - `createEntryFromCustomerCreditNote()`
  - `createEntryFromSupplierCreditNote()`
- Inversion automatique débit/crédit
- Description adaptée ("Avoir client", "Annulation vente")

---

## 🗄️ Base de Données

### Colonne ajoutée
- `invoices.is_credit_note` (boolean, default: false)

### Migration exécutée
- ✅ `add_is_credit_note_to_invoices_table`

---

## 🎯 Workflow Complet

### Créer un avoir client

1. **Factures** → **+ Nouvelle Facture**
2. Type : **Client**
3. ☑️ **Cocher "Facture d'avoir"**
4. Sélectionner le client
5. Montant : ex. 100 000 FCFA
6. Description : "Retour marchandises"
7. **Enregistrer**

→ Numéro : `AV-2025-0001`  
→ Écriture automatique : Débit 701, Crédit 411  
→ Badge "AVOIR" visible

### Résultat comptable

- Compte 701 (Ventes) : -100 000 FCFA
- Compte 411 (Clients) : -100 000 FCFA
- Comptes soldés automatiquement

---

## 🎊 Résumé

✅ **Checkbox "Facture d'avoir"** dans le formulaire  
✅ **Numérotation AV-YYYY-XXXX** pour les avoirs  
✅ **Écritures automatiques inversées**  
✅ **Badge visuel** rouge "AVOIR"  
✅ **party_name** auto-rempli (erreur corrigée)  
✅ **Logique comptable** respectée  

**Les factures d'avoir fonctionnent parfaitement ! 🎉**


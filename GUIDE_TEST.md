# Guide de test — Supervision Bancaire

**URL de base :** `http://localhost/BDA/public/`
**Date :** 04/03/2026
**Environnement :** XAMPP / PHP 8 / MySQL

---

## Prérequis avant de commencer

| Élément | Vérification |
|---|---|
| XAMPP | Apache ✅ + MySQL ✅ démarrés dans le panneau XAMPP |
| Base de données | `supervision_bancaire` importée depuis `database/schema.sql` via phpMyAdmin |
| Projet | Copié dans `C:\xampp\htdocs\BDA\` |
| Navigateur | Ouvrir `http://localhost/BDA/public/?action=login` |

> **⚠ Important — Table versement vide au départ**
> La base est initialisée sans versements. Le tableau affichera *"Aucun versement trouvé."* et **il n''y aura aucun bouton crayon ni poubelle** tant que vous n''en avez pas ajouté. Commencez par le test 2.2 pour créer des données.

---

## 1. Authentification

### 1.1 — Connexion réussie — rôle `user`

**Actions :**
1. Aller sur `http://localhost/BDA/public/?action=login`
2. Saisir `user1` dans le champ *Identifiant*
3. Saisir `User123!` dans le champ *Mot de passe*
4. Cliquer **Se connecter**

**Ce que vous voyez à l'écran :**
- La page change et affiche la vue **Gestion des Versements**
- URL devient `http://localhost/BDA/public/?action=versements`
- Sidebar gauche : un seul lien **Versements** (icône carte bancaire)
- Bas de sidebar : avatar **U**, nom **user1**, rôle **User**
- Topbar : *"0 versement au total"* (base vide)
- Tableau central : icône boîte + message gris *"Aucun versement trouvé."*

---

### 1.2 — Connexion réussie — rôle `admin`

**Actions :**
1. Sur la page de connexion, saisir `admin` / `Admin123!`
2. Cliquer **Se connecter**

**Ce que vous voyez à l'écran :**
- Redirection vers la vue **Journal d'audit** (`?action=audit`)
- Sidebar : un seul lien **Journal d'audit** (icône bouclier)
- Bas de sidebar : avatar **A**, nom **admin**, rôle **Admin**
- 4 cartes statistiques en haut : **Insertions / Modifications / Suppressions / Total** (à 0 si base vide)

---

### 1.3 — Connexion échouée — mauvais mot de passe

**Actions :**
1. Sur la page de connexion, saisir `user1` / `mauvais`
2. Cliquer **Se connecter**

**Ce que vous voyez à l'écran :**
- Restez sur la page de connexion (`?action=login`)
- Bandeau rouge : *"Identifiant ou mot de passe incorrect."*
- Les champs sont vidés, aucune session créée

---

### 1.4 — Connexion échouée — champs vides

**Actions :**
1. Laisser les deux champs vides, cliquer **Se connecter**

**Ce que vous voyez à l'écran :**
- Restez sur la page de connexion
- Bandeau rouge : *"Veuillez renseigner tous les champs."*

---

### 1.5 — Protection CSRF

**Actions :**
1. Ouvrez la console navigateur (F12 → Console)
2. Collez et exécutez :

```javascript
fetch('http://localhost/BDA/public/?action=login', {
  method: 'POST',
  headers: {'Content-Type': 'application/x-www-form-urlencoded'},
  body: 'username=user1&password=User123%21&csrf_token=FAUX'
}).then(r => r.text()).then(t => console.log(t.substring(0,300)))
```

**Ce que vous voyez à l'écran :**
- La réponse dans la console contient : *"Token de sécurité invalide."*
- Aucune redirection, aucune connexion établie

---

### 1.6 — Protection des pages sans session

**Actions :**
1. Effacer tous les cookies (`Ctrl+Shift+Suppr` → Cookies)
2. Accéder directement à `http://localhost/BDA/public/?action=versements`

**Ce que vous voyez à l'écran :**
- Redirection immédiate vers `?action=login`
- La page versements n'est jamais affichée

---

### 1.7 — Cloisonnement des rôles

**Actions :**
1. Connectez-vous avec `user1` / `User123!`
2. Dans la barre d'adresse, taper `http://localhost/BDA/public/?action=audit`

**Ce que vous voyez à l'écran :**
- Page d'erreur **403 — Accès refusé**
- Message : *"Vous n'avez pas les droits nécessaires pour accéder à cette page."*
- Aucune donnée d'audit visible

---

### 1.8 — Déconnexion

**Actions :**
1. Connectez-vous avec `user1`
2. Cliquer le bouton **Déconnexion** en bas de la sidebar (icône porte de sortie)

**Ce que vous voyez à l'écran :**
- Retour sur la page de connexion
- Si vous tentez `?action=versements` : redirection vers login
- Cookie de session supprimé (F12 → Application → Cookies : vide)

---

## 2. Gestion des versements (rôle `user`)

> Se connecter avec `user1` / `User123!` avant ces tests.

---

### 2.1 — Affichage du tableau (base vide)

**Ce que vous voyez à l'écran :**
- Topbar : *"0 versement au total"* + date du jour
- Tableau avec 7 colonnes : **#, N° Chèque, N° Compte, Client, Montant (MAD), Solde Compte (MAD), Actions**
- Corps du tableau : icône boîte + *"Aucun versement trouvé."* en gris centré
- Bouton bleu **+ Nouveau versement** en haut à droite
- **Aucun bouton crayon ni poubelle** — normal, pas encore de données

---

### 2.2 — Ajout d'un premier versement

**Actions :**
1. Cliquer le bouton bleu **+ Nouveau versement**

**Ce que vous voyez à l'écran (modal) :**
- Fenêtre modale : titre **"Nouveau Versement"**
- 3 champs : *Numéro de chèque*, *Compte client* (liste déroulante avec 5 clients), *Montant (MAD)*

**Actions (suite) :**
2. Remplir : N° chèque = `CHQ-2026-001`, Compte = `1001 — Dupont Martin`, Montant = `1500`
3. Cliquer **Enregistrer**

**Ce que vous voyez à l'écran (après soumission) :**
- Modal se ferme automatiquement
- Bandeau vert : *"Versement ajouté avec succès."* (disparaît après 4 sec)
- Topbar : *"1 versement au total"*
- Tableau : une ligne apparaît avec **crayon bleu** et **poubelle rouge** dans la colonne Actions

| # | N° Chèque | N° Compte | Client | Montant (MAD) | Solde Compte (MAD) | Actions |
|---|---|---|---|---|---|---|
| 1 | CHQ-2026-001 | 1001 | Dupont Martin | 1 500,00 | **1 500,00** | ✏️ 🗑️ |

**Vérification MySQL :**
```sql
SELECT * FROM versement ORDER BY numero_versement DESC LIMIT 1;
SELECT solde FROM client WHERE numero_compte = 1001;         -- doit valoir 1500.00
SELECT type_action, montant_ancien, montant_nouveau FROM audit_versement ORDER BY id DESC LIMIT 1;
-- INSERT, 0.00, 1500.00
```

---

### 2.3 — Ajout — validation champs obligatoires

**Actions :**
1. Cliquer **+ Nouveau versement**, ne rien remplir, cliquer **Enregistrer**

**Ce que vous voyez à l'écran :**
- Bandeau rouge : message de validation
- Aucune nouvelle ligne dans le tableau

---

### 2.4 — Ajout — montant invalide

**Actions :**
1. Cliquer **+ Nouveau versement**
2. Remplir : N° chèque = `CHQ-TEST`, Compte = `1002`, Montant = `-50`
3. Cliquer **Enregistrer**

**Ce que vous voyez à l'écran :**
- Bandeau rouge : *"Le montant doit être un nombre positif."*
- Aucune ligne insérée

---

### 2.5 — Modification d'un versement

> Prérequis : avoir au moins un versement (étape 2.2 faite).

**Actions :**
1. Sur la ligne de CHQ-2026-001, cliquer l'icône **crayon bleu**

**Ce que vous voyez à l'écran (modal) :**
- Fenêtre modale **"Modifier le Versement"**
- Champs **pré-remplis** : N° chèque = `CHQ-2026-001`, Compte = `1001`, Montant = `1500`

**Actions (suite) :**
2. Effacer le montant et saisir `2000`, cliquer **Enregistrer**

**Ce que vous voyez à l'écran :**
- Bandeau vert : *"Versement modifié avec succès."*
- Ligne mise à jour : Montant = **2 000,00**, Solde = **2 000,00** (delta +500)

**Vérification MySQL :**
```sql
SELECT type_action, montant_ancien, montant_nouveau
FROM audit_versement WHERE type_action = 'UPDATE' ORDER BY id DESC LIMIT 1;
-- 1500.00 → 2000.00
SELECT solde FROM client WHERE numero_compte = 1001;  -- 2000.00
```

---

### 2.6 — Suppression d'un versement

**Actions :**
1. Sur une ligne, cliquer l'icône **poubelle rouge**

**Ce que vous voyez à l'écran (modal) :**
- Fenêtre de confirmation : *"Supprimer le versement Cheque N° CHQ-2026-001 ?"*
- Boutons **Annuler** (gris) et **Supprimer** (rouge)

**Actions (suite) :**
2. Cliquer **Supprimer**

**Ce que vous voyez à l'écran :**
- Bandeau vert : *"Versement supprimé avec succès."*
- La ligne disparaît du tableau
- Topbar decrementée, solde du client remis à 0

**Vérification MySQL :**
```sql
SELECT type_action, montant_ancien, montant_nouveau
FROM audit_versement WHERE type_action = 'DELETE' ORDER BY id DESC LIMIT 1;
-- DELETE, 2000.00, 0.00
```

---

### 2.7 — Annulation de suppression

**Actions :**
1. Cliquer la poubelle d'un versement, puis **Annuler** dans le modal

**Ce que vous voyez à l'écran :**
- Modal se ferme, tableau **inchangé**, aucun message flash

---

### 2.8 — Recherche / filtre

> Prérequis : avoir au moins 2 versements de clients différents.

**Actions :**
1. Saisir `Dupont` dans la barre de recherche (icône loupe)
2. Cliquer **Filtrer**

**Ce que vous voyez à l'écran :**
- Seules les lignes du client Dupont Martin s'affichent
- Compteur mis à jour en topbar
- Bouton **×** visible à droite de la barre
3. Cliquer **×** → liste complète restaurée, bouton × disparaît

---

### 2.9 — Pagination

**Actions :**
1. Insérer 12 versements via MySQL :

```sql
INSERT INTO versement (numero_cheque, numero_compte, montant) VALUES
('CHQ-T01', 1001, 100), ('CHQ-T02', 1002, 200), ('CHQ-T03', 1003, 300),
('CHQ-T04', 1004, 400), ('CHQ-T05', 1005, 500), ('CHQ-T06', 1001, 150),
('CHQ-T07', 1002, 250), ('CHQ-T08', 1003, 350), ('CHQ-T09', 1004, 450),
('CHQ-T10', 1005, 550), ('CHQ-T11', 1001, 600), ('CHQ-T12', 1002, 700);
```

2. Rafraîchir `?action=versements`

**Ce que vous voyez à l'écran :**
- **10 lignes** en page 1, pagination *"Page 1 / 2 — 12 enregistrements"*
- Bouton **‹** grisé (désactivé), bouton **›** actif
- Cliquer page **2** → 2 lignes restantes, bouton **›** grisé

---

## 3. Journal d'audit (rôle `admin`)

> Se déconnecter, puis se connecter avec `admin` / `Admin123!`.

---

### 3.1 — Affichage du tableau d'audit

**Ce que vous voyez à l'écran :**
- Topbar : **"Journal d'audit"**
- **4 cartes statistiques** : 🟢 Insertions · 🔵 Modifications · 🔴 Suppressions · ⚫ Total
- Tableau **10 colonnes** : ID, Action, Date/Heure, N° Versement, N° Compte, Client, Montant Anc. (MAD), Montant Nouv. (MAD), Utilisateur, Hôte
- Lignes triées par date de la plus récente à la plus ancienne
- Badge coloré colonne Action : vert = INSERT, bleu = UPDATE, rouge = DELETE
- **Aucun bouton Modifier/Supprimer** — lecture seule uniquement

---

### 3.2 — Vérification des statistiques

**Ce que vous voyez à l'écran :**
- Les 4 cartes correspondent exactement aux données de la base

```sql
SELECT type_action, COUNT(*) AS total FROM audit_versement GROUP BY type_action;
```

---

### 3.3 — Filtre Insertions

**Actions :** Sélectionner **Insertions** dans la liste déroulante "Filtrer"

**Ce que vous voyez à l'écran :**
- Seules les lignes badge vert `INSERT` s'affichent
- Les 4 cartes statistiques **ne changent pas** (elles restent globales)
- Bouton **×** disponible pour réinitialiser

---

### 3.4 — Filtre Modifications

**Actions :** Sélectionner **Modifications**

**Ce que vous voyez :** Seules les lignes badge bleu `UPDATE`

---

### 3.5 — Filtre Suppressions

**Actions :** Sélectionner **Suppressions**

**Ce que vous voyez :** Seules les lignes badge rouge `DELETE`

---

### 3.6 — Réinitialisation du filtre

**Actions :** Cliquer **×** ou choisir **"Toutes les actions"**

**Ce que vous voyez :** Tableau complet restauré

---

### 3.7 — Vérification du champ Utilisateur

**Actions :**
1. Connecté en `user1`, créer un versement (`CHQ-USER-TEST`, compte 1003, montant 999)
2. Se reconnecter en `admin`, consulter l'audit

**Ce que vous voyez :**
- La ligne créée par user1 affiche **`user1`** dans la colonne Utilisateur

---

### 3.8 — Lecture seule

**Ce que vous voyez :** Aucun bouton d'action, seuls le filtre et la pagination sont interactifs

---

### 3.9 — Récapitulatif bas de tableau

**Ce que vous voyez :**
- Pied de tableau : badge vert **N insertion(s)**, badge bleu **N modification(s)**, badge rouge **N suppression(s)**

---

## 4. Vérification des triggers MySQL

> Exécuter dans phpMyAdmin → base `supervision_bancaire` → onglet SQL.

### 4.1 — Trigger INSERT

```sql
SELECT solde FROM client WHERE numero_compte = 1001;          -- solde avant

INSERT INTO versement (numero_cheque, numero_compte, montant)
VALUES ('TRG-TEST-01', 1001, 5000.00);

SELECT solde FROM client WHERE numero_compte = 1001;          -- doit avoir augmenté de 5000
SELECT type_action, montant_ancien, montant_nouveau FROM audit_versement ORDER BY id DESC LIMIT 1;
-- INSERT, 0.00, 5000.00
```

### 4.2 — Trigger UPDATE

```sql
SET @id = LAST_INSERT_ID();
SELECT solde FROM client WHERE numero_compte = 1001;          -- solde avant

UPDATE versement SET montant = 3000.00 WHERE numero_versement = @id;

SELECT solde FROM client WHERE numero_compte = 1001;          -- delta -2000
SELECT type_action, montant_ancien, montant_nouveau FROM audit_versement ORDER BY id DESC LIMIT 1;
-- UPDATE, 5000.00, 3000.00
```

### 4.3 — Trigger DELETE

```sql
SELECT solde FROM client WHERE numero_compte = 1001;          -- solde avant

DELETE FROM versement WHERE numero_versement = @id;

SELECT solde FROM client WHERE numero_compte = 1001;          -- diminué de 3000
SELECT type_action, montant_ancien, montant_nouveau FROM audit_versement ORDER BY id DESC LIMIT 1;
-- DELETE, 3000.00, 0.00
```

---

## 5. Sécurité

### 5.1 — Injection SQL

**Actions :** Saisir `' OR '1'='1` dans la barre de recherche et filtrer

**Ce que vous voyez :** Tableau vide ou résultat normal — aucune donnée inattendue, aucune erreur SQL

### 5.2 — XSS

**Actions :** Ajouter un versement avec N° chèque = `<script>alert('XSS')</script>`, compte 1001, montant 1

**Ce que vous voyez :** Le texte brut `<script>alert('XSS')</script>` s'affiche dans le tableau — aucune alerte JS

### 5.3 — Accès direct aux fichiers internes

**Actions :** Tenter d'accéder aux URLs suivantes :
- `http://localhost/BDA/config/config.php`
- `http://localhost/BDA/models/UserModel.php`
- `http://localhost/BDA/controllers/AuthController.php`

**Ce que vous voyez :** Page **403 Forbidden** pour chacune

### 5.4 — Navigation directe hors session

**Actions :** Effacer les cookies, accéder à `?action=versements` puis `?action=audit`

**Ce que vous voyez :** Redirection vers `?action=login` dans les deux cas

---

## 6. Responsive design

**Actions :** F12 → activer le mode responsive ou réduire la fenêtre à < 768 px

**Ce que vous voyez :**
- Sidebar repositionnée en haut (pleine largeur)
- Tableau défilable horizontalement
- Boutons et modals utilisables sur mobile

---

## Récapitulatif des cas de test

| # | Module | Cas de test | Résultat |
|---|---|---|---|
| 1.1 | Auth | Connexion `user1` → redirection versements | ☐ |
| 1.2 | Auth | Connexion `admin` → redirection audit | ☐ |
| 1.3 | Auth | Mauvais mot de passe → message rouge | ☐ |
| 1.4 | Auth | Champs vides → message rouge | ☐ |
| 1.5 | Auth | Token CSRF faux → rejet | ☐ |
| 1.6 | Auth | Accès sans session → login | ☐ |
| 1.7 | Auth | user1 accède audit → 403 | ☐ |
| 1.8 | Auth | Déconnexion → session détruite | ☐ |
| 2.1 | Versement | Table vide → "Aucun versement trouvé." | ☐ |
| 2.2 | Versement | Ajout → ligne + solde + audit INSERT | ☐ |
| 2.3 | Versement | Ajout champs vides → validation | ☐ |
| 2.4 | Versement | Ajout montant négatif → rejet | ☐ |
| 2.5 | Versement | Crayon → pré-remplissage + solde recalculé | ☐ |
| 2.6 | Versement | Poubelle → ligne disparaît + solde diminué | ☐ |
| 2.7 | Versement | Annuler suppression → inchangé | ☐ |
| 2.8 | Versement | Recherche → filtre + bouton × | ☐ |
| 2.9 | Versement | Pagination → page 1/2 → navigation | ☐ |
| 3.1 | Audit | Tableau + 4 cartes + badges colorés | ☐ |
| 3.2 | Audit | Statistiques = résultat SQL exact | ☐ |
| 3.3 | Audit | Filtre INSERT → badges verts uniquement | ☐ |
| 3.4 | Audit | Filtre UPDATE → badges bleus uniquement | ☐ |
| 3.5 | Audit | Filtre DELETE → badges rouges uniquement | ☐ |
| 3.6 | Audit | Reset filtre → liste complète | ☐ |
| 3.7 | Audit | Colonne Utilisateur = `user1` | ☐ |
| 3.8 | Audit | Aucun bouton modifier/supprimer | ☐ |
| 3.9 | Audit | Pied de tableau : 3 totaux colorés | ☐ |
| 4.1 | Trigger | INSERT SQL → solde +5000, audit INSERT | ☐ |
| 4.2 | Trigger | UPDATE SQL → delta −2000, audit UPDATE | ☐ |
| 4.3 | Trigger | DELETE SQL → solde −3000, audit DELETE | ☐ |
| 5.1 | Sécu | Injection SQL → aucune fuite de données | ☐ |
| 5.2 | Sécu | XSS → texte échappé, pas d'alerte JS | ☐ |
| 5.3 | Sécu | Accès config/models/controllers → 403 | ☐ |
| 5.4 | Sécu | Accès direct hors session → login | ☐ |
| 6.1 | UI | Responsive < 768 px → mise en page adaptée | ☐ |

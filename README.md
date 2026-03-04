# Supervision Bancaire — Application Web MVC

Projet M2 2026 — Base de Données Avancées

## Description

Application web de supervision bancaire développée en PHP 8 / MySQL avec architecture MVC stricte.  
Elle permet la gestion des versements clients et la consultation d'un journal d'audit généré automatiquement via des triggers MySQL.

## Fonctionnalités

- **Authentification** sécurisée (bcrypt, sessions, protection CSRF)
- **Gestion des versements** : ajout, modification, suppression (rôle `user`)
- **Journal d'audit** : consultation en lecture seule avec filtres (rôle `admin`)
- **Triggers MySQL** : INSERT / UPDATE / DELETE → mise à jour automatique du solde + enregistrement d'audit
- **Sécurité** : requêtes préparées PDO, échappement XSS, cloisonnement des rôles, blocage des dossiers internes

## Stack technique

| Couche | Technologie |
|---|---|
| Backend | PHP 8.1 — Architecture MVC |
| Base de données | MySQL 8 — PDO singleton |
| Frontend | Bootstrap 5.3.3 + Bootstrap Icons |
| Serveur local | XAMPP (Apache + MySQL) |

## Installation

1. Copier le projet dans `C:\xampp\htdocs\BDA\`
2. Importer `database/schema.sql` dans phpMyAdmin (base : `supervision_bancaire`)
3. Démarrer Apache + MySQL dans XAMPP
4. Ouvrir `http://localhost/BDA/public/?action=login`

## Comptes de test

| Utilisateur | Mot de passe | Rôle |
|---|---|---|
| `admin` | `Admin123!` | Admin — Journal d'audit |
| `user1` | `User123!` | User — Gestion des versements |

## Structure du projet

```
BDA/
├── config/          # Configuration globale et connexion PDO
├── controllers/     # AuthController, VersementController, AuditController
├── models/          # UserModel, ClientModel, VersementModel, AuditModel
├── views/           # Templates PHP (layout, auth, versement, audit)
├── database/        # schema.sql (tables + 3 triggers)
├── public/          # Point d'entrée unique (index.php + .htaccess)
├── GUIDE_TEST.md    # Guide de test complet (34 cas)
└── README.md
```

## Triggers MySQL

| Trigger | Événement | Effet |
|---|---|---|
| `after_versement_insert` | INSERT | Augmente le solde client + INSERT dans audit |
| `after_versement_update` | UPDATE | Recalcule le solde (delta) + INSERT dans audit |
| `after_versement_delete` | DELETE | Diminue le solde client + INSERT dans audit |

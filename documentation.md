# Documentation du projet JO-Tourisme

## Structure du projet

### 1. **Fichiers principaux**

#### `index.php`
- **Description** : Le contrôleur frontal du projet. Il gère la navigation entre les différentes pages en fonction de la variable `page` passée dans l'URL.
- **Fonctionnalités** :
  - Initialise les contrôleurs nécessaires.
  - Gère les actions globales comme la suppression d'un utilisateur.
  - Charge les pages correspondantes en fonction de la valeur de `page`.

#### `styles.css`
- **Description** : Fichier de styles CSS pour le projet.
- **Fonctionnalités** :
  - Définit les styles pour les éléments principaux comme la navigation, les tableaux, les boutons, etc.
  - Inclut des animations et des styles spécifiques pour améliorer l'expérience utilisateur.

#### `sql\jo_paris.sql`
- **Description** : Script SQL pour créer et initialiser la base de données.
- **Fonctionnalités** :
  - Crée les tables nécessaires (`user`, `Client_Pro`, `Client_Particulier`, etc.).
  - Définit les procédures stockées pour insérer, modifier et supprimer des utilisateurs.
  - Crée des vues pour simplifier les requêtes SQL.

---

### 2. **Pages**

#### `pages\evenement.php`
- **Description** : Gère les événements.
- **Fonctionnalités** :
  - Affiche tous les événements.
  - Permet aux utilisateurs de réserver ou d'annuler une réservation.
  - Permet aux administrateurs et professionnels de modifier ou supprimer des événements.

#### `pages\service.php`
- **Description** : Gère les services.
- **Fonctionnalités** :
  - Affiche tous les services.
  - Permet aux utilisateurs de réserver ou d'annuler une réservation.
  - Permet aux administrateurs et professionnels de modifier ou supprimer des services.

#### `pages\mes_reservations.php`
- **Description** : Affiche les réservations de l'utilisateur connecté.
- **Fonctionnalités** :
  - Affiche les événements et services réservés par l'utilisateur.
  - Permet d'annuler une réservation.

#### `pages\gestion_utilisateurs.php`
- **Description** : Permet à l'administrateur de gérer les utilisateurs.
- **Fonctionnalités** :
  - Affiche tous les utilisateurs.
  - Permet de modifier les informations des utilisateurs.
  - Génère un PDF contenant la liste des utilisateurs.

#### `pages\rechercher_client.php`
- **Description** : Permet à l'administrateur de rechercher des clients.
- **Fonctionnalités** :
  - Affiche une liste de tous les clients.
  - Permet de rechercher des clients par leurs informations.

---

### 3. **Vues**

#### `vue\vue_les_evenements.php`
- **Description** : Affiche la liste des événements.
- **Fonctionnalités** :
  - Affiche les détails de chaque événement.
  - Permet de réserver, annuler, modifier ou supprimer un événement en fonction du rôle de l'utilisateur.

#### `vue\vue_les_services.php`
- **Description** : Affiche la liste des services.
- **Fonctionnalités** :
  - Affiche les détails de chaque service.
  - Permet de réserver, annuler, modifier ou supprimer un service en fonction du rôle de l'utilisateur.

#### `vue\vue_les_profilsPart.php`
- **Description** : Affiche la liste des utilisateurs particuliers.
- **Fonctionnalités** :
  - Affiche les informations des particuliers.
  - Permet à l'administrateur de supprimer un particulier.

#### `vue\vue_les_profilsPro.php`
- **Description** : Affiche la liste des utilisateurs professionnels.
- **Fonctionnalités** :
  - Affiche les informations des professionnels.
  - Permet à l'administrateur de supprimer un professionnel.

---

### 4. **Contrôleurs**

#### `controleur\controleurUser.class.php`
- **Description** : Gère les utilisateurs.
- **Fonctionnalités** :
  - Insère, modifie et supprime des utilisateurs.
  - Génère un PDF contenant la liste des utilisateurs.
  - Récupère les utilisateurs en fonction de leur rôle.

#### `controleur\controleurEvent.class.php`
- **Description** : Gère les événements.
- **Fonctionnalités** :
  - Insère, modifie et supprime des événements.
  - Gère les réservations et annulations pour les événements.

#### `controleur\controleurService.class.php`
- **Description** : Gère les services.
- **Fonctionnalités** :
  - Insère, modifie et supprime des services.
  - Gère les réservations et annulations pour les services.

---

### 5. **Modèles**

#### `modele\modeleUser.class.php`
- **Description** : Modèle pour les utilisateurs.
- **Fonctionnalités** :
  - Effectue les opérations CRUD sur les utilisateurs.
  - Gère les relations entre les utilisateurs et leurs rôles (particulier ou professionnel).

#### `modele\modeleEvent.class.php`
- **Description** : Modèle pour les événements.
- **Fonctionnalités** :
  - Effectue les opérations CRUD sur les événements.
  - Gère les réservations et annulations pour les événements.

#### `modele\modeleService.class.php`
- **Description** : Modèle pour les services.
- **Fonctionnalités** :
  - Effectue les opérations CRUD sur les services.
  - Gère les réservations et annulations pour les services.

---

### 6. **Composants**

#### `composants\navbar.php`
- **Description** : Barre de navigation du site.
- **Fonctionnalités** :
  - Affiche les liens vers les différentes pages en fonction du rôle de l'utilisateur.
  - Permet de se connecter, se déconnecter ou accéder à son profil.

---

### 7. **Autres**

#### `fonts\Paris2024VariableRegular.ttf`
- **Description** : Police personnalisée utilisée pour le design du site.

#### `images\`
- **Description** : Contient les images utilisées sur le site (logos, icônes, etc.).

---

## Instructions pour les développeurs

1. **Base de données** :
   - Exécutez le script `sql\jo_paris.sql` pour créer et initialiser la base de données.

2. **Configuration** :
   - Configurez les informations de connexion à la base de données dans `controleur\config_bdd.php`.

3. **Navigation** :
   - Utilisez `index.php?page=X` pour accéder aux différentes pages, où `X` est le numéro de la page.

4. **Rôles** :
   - Les fonctionnalités sont restreintes en fonction du rôle de l'utilisateur (`admin`, `clientPart`, `clientPro`).

5. **Styles** :
   - Modifiez `styles.css` pour personnaliser l'apparence du site.

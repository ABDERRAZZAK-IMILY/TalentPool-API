
## Description
Cette API, développée en Laravel, permet la gestion des recrutements en facilitant la mise en relation entre recruteurs et candidats. L'architecture intègre le Repository Pattern et une couche Service pour assurer modularité et maintenabilité.

## Fonctionnalités

### Gestion des Annonces
- Ajout, modification et suppression d'annonces (Recruteur).
- Récupération des annonces et détails (Candidat).

### Gestion des Candidatures
- Postuler à une annonce avec CV et lettre de motivation (Candidat).
- Retrait de candidature (Candidat).
- Filtrage et récupération des candidatures associées aux annonces (Recruteur).

### Suivi des Candidatures
- Mise à jour du statut d’une candidature (Recruteur).
- Notification par email en cas de changement de statut (Candidat).

### Authentification et Sécurité
- Inscription et connexion avec JWT ou Sanctum.
- Réinitialisation du mot de passe.
- Rôle défini à l'inscription (Candidat ou Recruteur), non modifiable.

### Statistiques et Rapports
- Statistiques sur les annonces et candidatures (Recruteur).
- Statistiques globales sur l'utilisation de la plateforme (Administrateur).

## Technologies utilisées
- **Laravel** (Framework PHP)
- **JWT ou Sanctum** (Authentification)
- **Laravel Gates & Policies** (Gestion des permissions)
- **PHPUnit ou Pest** (Tests unitaires)
- **MySQL / PostgreSQL** (Base de données)
- **Mailtrap / SMTP** (Gestion des emails)
## - Installation et Déploiement
### - Prérequis
- PHP 8.3.14 installé
- MySQL et un serveur Web (Apache, Nginx)
- Composer et Node.js
### les  consuption   
### daigrame e class  
<!-- ![Description de l'image](images/images.jpg) -->
### - Installation
1. **Cloner le projet** :
   ```sh
   git clone https://github.com/lakrounehamza/TalentPool.git
   cd TalentPool
   ```
2. **Installer les dépendances** :
   ```sh
   composer install
   npm install && npm run build
   ```
3. **Configurer l'environnement** :
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
   Modifier `.env` pour connecter la base de données.
4. **Exécuter les migrations et seeders** :
   ```sh
   php artisan migrate --seed
   ```
5. **Démarrer le serveur** :
   ```sh
   php artisan serve
   ```

## - Sécurité et Performances
- **Chiffrement des mots de passe avec bcrypt**
- **Protection contre les attaques DDoS**
- **Optimisation des requêtes SQL pour des temps de réponse rapides**

# BeesPlace

BeesPlace est une application web développée avec Laravel permettant de publier, consulter et gérer des annonces. Elle propose une API sécurisée pour l’authentification, la gestion des utilisateurs et la gestion des annonces avec upload d’images.

## Fonctionnalités
- Authentification (inscription, connexion, déconnexion) via Sanctum
- Création et affichage d’annonces
- Upload d’images pour les annonces
- Validation avancée des champs
- API RESTful sécurisée

## Prérequis
- PHP >= 8.1
- Composer
- MySQL ou autre base de données compatible
- Node.js & npm (pour la gestion des assets front-end si besoin)

## Installation
1. Clone le dépôt :
   ```bash
   git clone <url-du-repo>
   cd BeesPlace
   ```
2. Installe les dépendances PHP :
   ```bash
   composer install
   ```
3. Copie le fichier d’environnement et configure-le :
   ```bash
   cp .env.example .env
   # Modifie .env selon ta configuration (DB, etc.)
   ```
4. Génère la clé d’application :
   ```bash
   php artisan key:generate
   ```
5. Exécute les migrations :
   ```bash
   php artisan migrate
   ```
6. (Optionnel) Installe les dépendances front-end :
   ```bash
   npm install && npm run dev
   ```
7. Lance le serveur :
   ```bash
   php artisan serve
   ```

## Utilisation de l’API
Les routes principales de l’API sont :

| Méthode | Endpoint              | Description                        |
|---------|----------------------|------------------------------------|
| POST    | /api/register        | Inscription utilisateur            |
| POST    | /api/login           | Connexion utilisateur              |
| GET     | /api/user            | Infos utilisateur connecté         |
| POST    | /api/logout          | Déconnexion                        |
| POST    | /api/createannouncement | Créer une annonce (auth requis) |
| GET     | /api/showAnnouncements | Voir toutes les annonces (auth) |

Pour créer une annonce, envoie un formulaire `multipart/form-data` avec les champs :
- `title` (string, max 20)
- `description` (string, max 200)
- `departement` (string, max 20)
- `website` (string, max 200, optionnel)
- `image_path` (fichier image, max 2Mo)

## Sécurité
- Les routes sensibles sont protégées par le middleware `auth:sanctum`.
- Les validations sont strictes côté backend.

## Contribution
Les contributions sont les bienvenues !
1. Fork le projet
2. Crée une branche
3. Propose une Pull Request

## Licence
Ce projet est sous licence MIT.

---
Développé avec ❤️ par l’équipe BeesPlace.

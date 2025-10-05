# BeesPlace Server API

BeesPLace API pour publier, consulter et gérer des annonces, avec authentification via Sanctum et upload d'images.

## Fonctionnalités
- Authentification (inscription, connexion, déconnexion) via Laravel Sanctum
- Création, mise à jour, suppression logique (is_active=false) et consultation d'annonces
- Upload d'images (stockage public, `storage/app/public`)
- Validation serveur stricte via FormRequests

## Prérequis
- PHP ^8.2
- Composer
- Base de données (MySQL)

## Installation
1. Cloner le dépôt et entrer dans le dossier du projet
   ```bash
   git clone <url-du-repo>
   cd BeesPlaceServerApi
   ```
2. Installer les dépendances PHP
   ```bash
   composer install
   ```
3. Copier le fichier d'environnement et configurer
   ```bash
   cp .env.example .env
   # Editer .env (Acces à la base de donnée)
   ```
4. Générer la clé d'application
   ```bash
   php artisan key:generate
   ```
5. Lancer les migrations
   ```bash
   php artisan migrate
   ```
6. Exposer le stockage public pour les images
   ```bash
   php artisan storage:link
   ```
7. Démarrer le serveur local
   ```bash
   php artisan serve
   ```

## Authentification
- Connexion: renvoie un token d'accès (Sanctum Personal Access Token)
- Pour appeler les routes protégées: ajouter l'en-tête HTTP
  `Authorization: Bearer <token>`

## Endpoints

Public (sans authentification):
- GET `/api/announcement` — Liste toutes les annonces actives
- POST `/api/register` — Inscription
- POST `/api/login` — Connexion
- POST `/api/password/forgot` — Envoi du lien de réinitialisation
- POST `/api/password/reset/{token}` — Réinitialisation du mot de passe

Protégé (Authorization: Bearer <token>):
- GET `/api/user` — Infos de l'utilisateur connecté
- POST `/api/logout` — Déconnexion (révocation des tokens)
- POST `/api/announcement/create` — Créer une annonce
- GET `/api/announcement/show` — Lister les annonces de l'utilisateur connecté
- PUT `/api/announcement/update` — Mettre à jour une annonce de l'utilisateur
- PUT `/api/announcement/delete` — Suppression logique d'une annonce (is_active=false)

## Contrats des endpoints (valeurs et validations)

1) Inscription — POST `/api/register`
- Body (JSON):
  - `name`: string, requis
  - `email`: email, requis, unique
  - `password`: string, requis
  - `phone_number`: string, requis, max:10
- Réponses: 201 (utilisateur créé) ou 422 (erreurs de validation)

2) Connexion — POST `/api/login`
- Body (JSON):
  - `email`: email, requis
  - `password`: string, requis
- Réponses: 200 `{ message, token }` ou 401 en cas d'échec | 422 validation

3) Mot de passe oublié — POST `/api/password/forgot`
- Body (JSON): `email` (email requis)
- Réponses: 200 ou 400 selon l'état du broker

4) Réinitialisation du mot de passe — POST `/api/password/reset/{token}`
- Params: `token` (URL)
- Body (JSON): `email`, `password`, `password_confirmation`, `token`
- Réponses: 200 (ok) ou 400 (token/identifiants invalides) | 422 validation

5) Profil — GET `/api/user`
- Réponse 200: `{ message, name, email }`

6) Déconnexion — POST `/api/logout`
- Réponse 200: `{ message }`

7) Liste annonces publiques — GET `/api/announcement`
- Réponse 200: `{ message, announcements: [{ title, description, departement, website, image_path }] }`
  - `image_path` est un chemin relatif vers `storage`.

8) Créer une annonce — POST `/api/announcement/create`
- Content-Type: `multipart/form-data`
- Champs:
  - `title`: string, requis, max:20
  - `description`: string, requis, max:200
  - `departement`: string, requis, max:20
  - `website`: string, optionnel, max:200
  - `image_path`: fichier image, requis, max:2Mo
- Réponse 200: `{ message, announcement: { title, description, departement, website, image_path } }`
  - `image_path` renvoyé est relatif (ex: `announcements/xxx.jpg`). L'URL publique attendue est: `${APP_URL}/storage/${image_path}`

9) Annonces utilisateur — GET `/api/announcement/show`
- Réponse 200: `{ annonces: [{ title, description, departement, website, image_path }] }`
  - Ici `image_path` est déjà renvoyé sous forme d'URL absolue (via `asset('storage/...')`).

10) Mettre à jour une annonce — PUT `/api/announcement/update`
- Body: selon besoin (tous les champs sont optionnels)
  - `id`: integer (dans le corps si nécessaire pour identifier — voir contrôleur)
  - `title`: string, max:20
  - `description`: string, max:200
  - `departement`: string, max:20
  - `website`: string, max:200
  - `image_path`: fichier image, max:2Mo
- Réponses: 200 `{ message, new_announcement }` ou 404 si non trouvée | 422 validation

11) Supprimer (logique) une annonce — PUT `/api/announcement/delete`
- Body (JSON):
  - `id`: integer, requis
- Réponses: 200 `{ message }` ou 404 si non trouvée | 422 validation


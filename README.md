# Recipe Sharing Platform

A recipe sharing platform built with Laravel. Users can publish their own recipes, rate others, add them to favorites, and filter by categories and cuisines.

## 🚀 Key Features

*   **Recipe Management**: Create, read, update, and delete recipes (CRUD).
*   **Moderation System**: Admin panel to review, approve, or reject new recipes.
*   **Ratings and Reviews**: Users can rate recipes and leave comments.
*   **Favorites**: Ability to save favorite recipes to a personal list.
*   **Localization**: Full support for three languages (English, Kazakh, Russian).
*   **API Documentation**: Automated API documentation generation via Swagger.
*   **Authentication**: Implemented using Laravel Breeze (Web) and Sanctum (API).

## 🛠 Tech Stack

*   **Backend**: Laravel 13, PHP 8.5
*   **Frontend**: Tailwind CSS, Vite, Blade
*   **Database**: MySQL / PostgreSQL
*   **API**: REST API with Swagger support (L5-Swagger)
*   **Containerization**: Docker (Docker Compose)

## 📦 Installation

### 1. Clone the repository
```bash
git clone <your-repository-url>
cd recipe-sharing-platform
```

### 2. Environment Setup
Copy the example environment file:
```bash
cp .env.example .env
```
Configure your database connection in the `.env` file.

### 3. Installation via Docker (Recommended)
If you have Docker and Docker Compose installed:
```bash
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app npm install
docker-compose exec app npm run build
```

### 4. Local Installation (without Docker)
```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run dev
```

## 🗄 Database

The project includes seeders to quickly populate the database:
*   `AdminSeeder`: Creates an administrator account.
*   `CategorySeeder`: Populates the list of categories.
*   `CuisineSeeder`: Populates the list of cuisines.
*   `RecipeSeeder`: Generates sample recipes.

To run migrations with seeds:
```bash
php artisan migrate:fresh --seed
```

## 🌐 API and Swagger

API documentation is available at:
`http://localhost:8000/api/documentation`

To generate up-to-date Swagger documentation, use the following command:
```bash
php artisan l5-swagger:generate
```

## 🌍 Localization

Language switching is available via the web interface or through the following routes:
*   `/lang/en` — English
*   `/lang/kk` — Kazakh
*   `/lang/ru` — Russian

Language files are located in `resources/lang/` and `lang/`.

## 🧪 Testing

Run tests:
```bash
php artisan test
```
The project includes Feature tests for:
*   Authentication (`AuthTest`)
*   User Profile (`ProfileTest`)
*   Ratings (`RatingTest`)

## 📁 Project Structure (Key Folders)

*   `app/Http/Controllers` — Request handling logic.
*   `app/Models` — Database models (Recipe, Category, Cuisine, etc.).
*   `database/migrations` — Database schema.
*   `resources/views` — Blade templates for the interface.
*   `routes/` — Route definitions (web.php, api.php).

## 🛡 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

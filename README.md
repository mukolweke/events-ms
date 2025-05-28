# Event Management System

This is a full-stack web application using **Laravel** for the backend API, **Nuxt.js** for the frontend (SPA/SSR), and **MySQL** as the database.

---

## ⚙️ Requirements

Make sure the following are installed on your system:

- PHP 8.2
- Composer
- Node.js 22
- npm or pnpm
- MySQL
- Laravel Herd / Valet / Serve / Docker (optional)

---

## 📁 Project Structure


---

## 🛠️ Backend Setup (Laravel)

```bash
cd backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Update DB credentials in .env file:
# DB_DATABASE=your_database_name
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run database migrations
php artisan migrate

# (Optional) Seed the database
php artisan db:seed

# Start Laravel server
php artisan serve

```

## 🛠️ Frontend Setup (NuxtJs)

```bash
cd frontend

# Install Node.js dependencies
npm install  # or pnpm install

# Copy environment file
cp .env.example .env

# Update API base URL in .env file:
# NUXT_PUBLIC_API_BASE_URL=http://localhost:8000/api

# Run Nuxt development server
npm run dev

```

## 🛠️ Run

```bash
cd backend
php artisan serve


cd frontend
npm run dev

# Open your browser and go to http://localhost:3000
```

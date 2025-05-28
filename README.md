<img width="960" alt="Screenshot 2025-05-28 at 21 11 08" src="https://github.com/user-attachments/assets/51ba92c5-87bd-4e0e-a07f-56c988a8b316" /># Event Management System

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

## Uploads

<img width="960" alt="Screenshot 2025-05-28 at 21 10 55" src="https://github.com/user-attachments/assets/7f915fff-09fc-4fcf-9456-15cd6365ecba" />
<img width="960" alt="Screenshot 2025-05-28 at 21 11 16" src="https://github.com/user-attachments/assets/8f11a9c3-eb13-4b21-9bf9-ad618c4f128e" />
<img width="960" alt="Screenshot 2025-05-28 at 21 11 23" src="https://github.com/user-attachments/assets/e02e34d6-0896-4e1e-a657-ab5dfac1a493" />
<img width="960" alt="Screenshot 2025-05-28 at 21 11 34" src="https://github.com/user-attachments/assets/797764c8-6616-4e6d-8862-f5d0f9821548" />
<img width="960" alt="Screenshot 2025-05-28 at 21 11 55" src="https://github.com/user-attachments/assets/6b2fa256-23a0-421e-9dd9-667353f29131" />

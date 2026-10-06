# Building Materials Ecommerce

A full-stack e-commerce platform for construction materials using React, PHP, and MySQL.

## Features
- Product catalog and categories
- Product details page
- Search and filtering
- Shopping cart
- Checkout flow
- Login and registration
- Admin dashboard
- Order workflow
- MySQL database schema and seed data

## Tech Stack
- Frontend: React + Vite
- Backend: PHP REST API
- Database: MySQL
- Auth: JWT

## Project Structure
```text
project-root/
├── backend/
│   ├── api/
│   │   ├── config/
│   │   │   ├── database.php
│   │   │   └── jwt.php
│   │   ├── controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   └── OrderController.php
│   │   ├── middleware/
│   │   │   └── AuthMiddleware.php
│   │   ├── index.php
│   │   └── .htaccess
│   ├── .env.example
│   └── composer.json
├── frontend/
│   ├── src/
│   │   ├── api/
│   │   │   └── axios.js
│   │   ├── components/
│   │   │   └── ProductCard.jsx
│   │   ├── pages/
│   │   │   ├── HomePage.jsx
│   │   │   ├── LoginPage.jsx
│   │   │   ├── RegisterPage.jsx
│   │   │   ├── ProductDetailsPage.jsx
│   │   │   ├── CartPage.jsx
│   │   │   ├── CheckoutPage.jsx
│   │   │   └── DashboardPage.jsx
│   │   ├── App.jsx
│   │   ├── main.jsx
│   │   └── index.css
│   ├── .env.example
│   ├── index.html
│   ├── package.json
│   ├── vite.config.js
│   └── .gitignore
├── database/
│   ├── schema.sql
│   └── seed.sql
├── .gitignore
├── README.md
└── .env.example
```

## Setup

### 1) Database
```bash
mysql -u root -p
CREATE DATABASE construction_store;
USE construction_store;
SOURCE database/schema.sql;
SOURCE database/seed.sql;
```

### 2) Backend
```bash
cd backend
cp .env.example .env
php -S localhost:8000 api/index.php
```

### 3) Frontend
```bash
cd frontend
npm install
npm run dev
```

## Default admin
- Email: admin@constructionstore.com
- Password: admin123

## Environment
Frontend `.env`:
```env
VITE_API_URL=http://localhost:8000/api
```

Backend `.env`:
```env
DB_HOST=localhost
DB_NAME=construction_store
DB_USER=root
DB_PASS=
```

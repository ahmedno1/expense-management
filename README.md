# Expense Manager

A clean and modern **Expense Manager** built with **Laravel 12** and **PHP 8.2**.  
Track expenses, plan budgets, and generate month-specific AI recommendations.

**Tech Stack:** Laravel 12 · PHP 8.2 · Blade · Livewire · Fortify · SQLite / MySQL

---

## Features

- User authentication (Register / Login / Logout)
- Secure configuration and encryption (Laravel standards)
- Expense logging with categories
- Monthly budgeting by category
- Summary view (expected vs spent)
- AI Advisor recommendations (current month)
- Clean and maintainable project structure

---

## Prerequisites

- PHP 8.2+
- Composer
- Node.js + npm (for asset builds)
- A database (SQLite or MySQL)

---

## Installation

```bash
git clone https://github.com/ahmedno1/expense-management.git
cd expense-management
composer install
cp .env.example .env
php artisan key:generate
```

---

## Configuration

1. Update your database connection in `.env`.
2. Run migrations:

```bash
php artisan migrate
```

---

## Run the project

```bash
php artisan serve
```

Open: http://127.0.0.1:8000

---

## Front-end assets (optional)

```bash
npm install
npm run dev
```

---

## Testing

```bash
php artisan test
```

---

## Project Goals

This project is designed as a base for:

- Expense CRUD operations
- Categorization and reporting
- Future analytics and export features

---

## Author

Ahmed Yousef Almouqaid  
GitHub: https://github.com/ahmedno1

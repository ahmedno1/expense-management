# Expense Management System

A clean and modern **Expense Management System** built with **Laravel 12** and **PHP 8.2**.  
The project provides a solid foundation for tracking expenses, managing users, and building financial reports.

**Tech Stack:** Laravel 12 · PHP 8.2 · Blade · Livewire · Fortify · SQLite / MySQL

---

## Features

- User authentication (Register / Login / Logout)
- Secure configuration and encryption (Laravel standards)
- Ready-to-extend architecture for expense tracking
- Clean and maintainable project structure

---

## Installation

```bash
git clone https://github.com/ahmedno1/expense-management.git
cd expense-management
composer install
copy .env.example .env
php artisan key:generate

Database (SQLite – recommended)
php artisan migrate


When asked to create the database file, choose yes.

Run the project
php artisan serve


Open: http://127.0.0.1:8000

Project Goals

This project is designed as a base for:

Expense CRUD operations

Categorization and reporting

Future analytics and export features

Author

Ahmed Yousef Almouqaid
GitHub: https://github.com/ahmedno1

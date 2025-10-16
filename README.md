# 📝 Task Manager API

<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

A simple **Task Management REST API** built with Laravel.  
Supports authentication, role-based access control, task dependencies, and status validation.

---

## 🚀 Features

- 🔐 Authentication with Laravel Sanctum  
- 🧑‍💼 Role-based permissions (Manager / User)  
- 📝 Task CRUD operations  
- 🧭 Filtering by:
  - status
  - assigned user
  - due date range
- 🧩 Task dependencies (with circular/self dependency checks)
- ⚠️ Validation before marking tasks as completed
- 📬 Postman collection ready for testing

---

## 🧰 Tech Stack

- Laravel 12.x  
- PHP 8.2+  
- Sanctum for API authentication  
- MySQL

---

## ⚡ Installation Guide

```bash
# Clone the repository
git clone https://github.com/Ahmedheshamesmail/task-manager-api.git
cd task-manager-api

# Install dependencies
composer install

# Copy and configure .env
cp .env.example .env
php artisan key:generate

# Migrate and seed
php artisan migrate --seed

# Run the server
php artisan serve

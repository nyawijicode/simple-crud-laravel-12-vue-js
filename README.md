📦 Simple CRUD with Laravel 12 & Vue.js

This is a simple full-stack CRUD (Create, Read, Update, Delete) application built with Laravel 12 (backend API) and Vue.js (frontend). It demonstrates how to build a modern web application with clean separation between the frontend and backend, using RESTful APIs.

⚙️ Requirements

    PHP 8.2+

    Composer

    Node.js & npm

    Laravel 12

    MySQL / SQLite


📁 Folder Structure

    backend/ — Laravel 

    frontend/ — Vue.js 

Backend

cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

Frontend

cd frontend
npm install
npm run dev

📌 Notes

This project is intended as a starter template or learning resource. Feel free to clone and modify it to suit your needs.
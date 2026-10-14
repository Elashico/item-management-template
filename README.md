# Box & Item Management App

A full-stack web application built with a **Laravel (PHP)** REST API backend and a **Vue.js 3 (Composition API + Tailwind CSS)** frontend. The application features hierarchical data management (Boxes containing Items), soft deletion, restoration capabilities, and permanent deletion (force delete).

---

## Features

- **Collections / Boxes Management:** Create, update, soft-delete, restore, and permanently delete boxes.
- **Nested Items Management:** Add, update, soft-delete, restore, and permanently delete items within selected boxes.
- **Master-Detail Layout:** Clean, responsive split-screen UI powered by Tailwind CSS.
- **Soft Deletes:** Ability to trash records with timestamps and restore them if needed.
- **Database Transactions:** Safe force-deleting of parent records alongside their nested children.

---

## Tech Stack

- **Backend:** Laravel (PHP API)
- **Frontend:** Vue.js 3 (`<script setup>` syntax), Axios
- **Styling:** Tailwind CSS
- **Database:** MySQL / SQLite

---

## Project Structure

```text
├── backend (Laravel API)
└── frontend (Vue.js SPA)
```

---

## Getting Started / How to Run

Follow these steps to set up and run both the backend and frontend locally.

### Prerequisites

Make sure you have the following installed on your machine:
- **PHP** (>= 8.2 recommended)
- **Composer**
- **Node.js** & **npm**
- **Git**

---

### Step 1: Clone the Repository

```bash
git clone https://github.com/your-username/your-repository-name.git
cd your-repository-name
```

---

### Step 2: Setup the Laravel Backend

1. Navigate into your backend directory (or root if Laravel is at the root):
   ```bash
   cd backend
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Copy the environment configuration file:
   ```bash
   cp .env.example .env
   ```
4. Generate an application encryption key:
   ```bash
   php artisan key:generate
   ```
5. Configure your database settings inside the `.env` file (e.g., database name, username, password).
6. Run database migrations (and seeders if available):
   ```bash
   php artisan migrate
   ```
7. Start the local Laravel development server:
   ```bash
   php artisan serve
   ```
   *The backend API will typically run at `http://127.0.0.1:8000`.*

---

### Step 3: Setup the Vue Frontend

1. Open a new terminal window/tab and navigate to the frontend directory:
   ```bash
   cd frontend
   ```
2. Install Node dependencies:
   ```bash
   npm install
   ```
3. Configure your API base URL if necessary (usually set up via Axios base URL pointing to `http://127.0.0.1:8000/api`).
4. Start the Vite development server:
   ```bash
   npm run dev
   ```
5. Open the local development URL provided in your terminal (typically `http://localhost:5173`) in your browser.

---
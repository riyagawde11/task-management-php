# 📋 Task & Project Tracker (PHP & MySQL CRUD)

A clean, responsive web-based task management application built using **PHP**, **MySQL (PDO)**, and **Bootstrap 5**. The application implements full CRUD (Create, Read, Update, Delete) functionality with secure database interactions.

---

## 🚀 Features

- **Create Tasks:** Add tasks with title, description, and priority level (Low, Medium, High).
- **Read / Display:** List all tasks ordered by recent creation date with visual badges.
- **Update Status:** Easily toggle task completion state (`Pending` ↔ `Completed`).
- **Delete Tasks:** Remove tasks with confirmation dialogs.
- **SQL Injection Prevention:** Uses PHP Data Objects (PDO) with parameterized prepared statements.
- **Responsive UI:** Designed using Bootstrap 5 for seamless desktop and mobile view.

---

## 🛠️ Tech Stack

- **Backend:** PHP 7.4+ / 8.x
- **Database:** MySQL
- **Database Driver:** PHP Data Objects (PDO)
- **Frontend:** HTML5, CSS3, Bootstrap 5

---

## ⚙️ Local Setup Instructions

### 1. Prerequisites
- Install [XAMPP](https://www.apachefriends.org/) (or any local Apache + MySQL stack).

### 2. Database Configuration
1. Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. Open your browser and go to `http://localhost/phpmyadmin`.
3. Create a new database named: `task_manager`.
4. Import the `database.sql` file into this database.

### 3. Running the Project
1. Copy or clone this repository into your XAMPP `htdocs` directory:
   ```text
   C:\xampp\htdocs\task-management-php
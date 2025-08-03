Here’s your complete and beautifully **formatted `README.md`** file with the newly added **"Setup Instructions"** section from Canvas, integrated in a professional and clean layout:

---

````markdown
# ✅ Task Manager with Reminders & Activity Logs

A Laravel 10+ web application to manage tasks with user authentication, activity logs, and email reminders using queued jobs.

---

## 🚀 Features

### 🔐 Authentication & Roles
- Built with **Laravel Breeze**
- Roles: **Admin** and **User**
- Admins can view all users and tasks
- Users can manage **only their own tasks**

### 📋 Task Management
- **CRUD** operations (Create, Read, Update, Delete)
- Fields: `title`, `description`, `due_date`, `status`, `priority`
- Status: Pending, In Progress, Completed
- Priority: Low, Medium, High
- Filter by status, priority, due date
- Search by title or description
- **Paginated** task listings

### 📊 Dashboard Overview
- Task Summary: Total, Pending, Completed
- Last 5 activity logs
- Overdue task counter

---

## 🛠 Technical Requirements

### UI & Components
- Views: **Blade**
- Styling: **Tailwind CSS** (or Bootstrap 5)
- Use Blade Components for:
  - Task cards
  - Alerts
  - Layout

### 📜 Activity Logs
- Table: `task_activity_logs`
- Tracked Actions: Create, Update, Delete, Status Changes
- Fields: `task_id`, `action`, `user_id`, `timestamp`, `description`
- View logs on task detail page

### ⏰ Reminders via Queued Jobs
- Email reminders sent **1 day before `due_date`**
- Use `database` queue driver
- **Scheduled job**: `TaskReminderJob`

### 🧰 Custom Artisan Command
```bash
php artisan tasks:send-reminders
````

* Finds tasks due tomorrow (not completed)
* Dispatches `TaskReminderJob`
* Logs summary of actions taken

---

## 🗄 Database Requirements

* Use **migrations**, **seeders**, and **factories**
* Relationships:

  * `User` ➝ hasMany ➝ `Task`
  * `Task` ➝ hasMany ➝ `TaskActivityLog`

---

## ⚙️ Setup Instructions

### 1️⃣ Obtain the Project Code

#### • Clone the Repository (Recommended):

```bash
git clone <your-repo-url> task-manager
cd task-manager
```
Or
#### • Download as ZIP:

* Download the project as a `.zip` from the repository.
* Extract it and rename the folder to `task-manager` (if needed):

```bash
cd path/to/your/task-manager
```

---

### 2️⃣ Install PHP Dependencies

```bash
composer install
```

---

### 3️⃣ Install Laravel Breeze (if not already included)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
```

---

### 4️⃣ Copy `.env.example` to `.env`

```bash
cp .env.example .env
```

---

### 5️⃣ Generate Application Key

```bash
php artisan key:generate
```

---

### 6️⃣ Configure Database in `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager_db
DB_USERNAME=root
DB_PASSWORD=
```

*(Make sure the database exists with the correct credentials.)*

---

### 7️⃣ Manually Create the Database

* Use phpMyAdmin, MySQL CLI, or any GUI tool to create a database named:

```
task_manager_db
```

---

### 8️⃣ Run Migrations & Seeders

```bash
php artisan migrate:fresh --seed
```

Creates:

* **Admin User:** `admin@example.com` / `password`
* **Regular User:** `user@example.com` / `password`
* Sample tasks for both users

---

### 9️⃣ Configure Queue Driver

Ensure in your `.env`:

```env
QUEUE_CONNECTION=database
```

---

### 🔟 Run Queue Listener (in separate terminal)

```bash
php artisan queue:work
```

*(Keep this terminal open while testing queued jobs.)*

---

### 1️⃣1️⃣ Start the Laravel Development Server

```bash
php artisan serve
```

Visit: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

### 1️⃣2️⃣ Schedule Command (for automated reminders)

For production Cron job:

```bash
* * * * * cd /path/to/your-project && php artisan schedule:run >> /dev/null 2>&1
```

For manual testing:

```bash
php artisan tasks:send-reminders
```

---

## 📨 Job Queue Usage

### • Manually Trigger Reminders

```bash
php artisan tasks:send-reminders
```

### • Process Jobs

```bash
php artisan queue:work
```

* Simulated emails will be logged in:

```
storage/logs/laravel.log
```

---

## 📎 License

This project is open-source and free to use for educational and personal projects.

---

**Happy Building! 🧱🚀**

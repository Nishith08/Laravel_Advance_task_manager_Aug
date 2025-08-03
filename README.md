Task Manager with Reminders & Activity Logs
This is a Laravel 10+ web application for managing tasks, complete with user authentication, activity logging, and email reminders via queued jobs.

Features
Authentication & Roles:

Built with Laravel Breeze for secure login/registration.

Supports two roles: Admin and User.

Admins can view all users and their tasks.

Users can only manage their own tasks.

Task Management:

CRUD operations for tasks (Create, Read, Update, Delete).

Tasks include title, description, due_date, status (Pending, In Progress, Completed), and priority (Low, Medium, High).

Filtering by status, priority, and due date (Before/After Today).

Search tasks by title or description.

Pagination for task listing.

Dashboard:

Displays a task summary: total, pending, completed

Shows the last 5 recent activity logs.

Counts overdue tasks.

Technical Requirements
Blade + Tailwind UI:

Use Laravel Blade for views.

Use Tailwind CSS (preferred) or Bootstrap 5.

Use Blade Components for reusable elements like task card, alerts, etc.

Activity Logs:

Create a task_activity_logs table.

Track key changes: create, update, delete, status changes.

Each log should contain: task_id, action, user_id, timestamp, description.

Display log history on the task detail page.

Reminders via Queued Jobs:

Implement a queue job to send email reminders 1 day before task due_date.

Use database queue driver.

Schedule the job using TaskReminderJob, dispatched via a daily scheduled command.

Custom Artisan Console Command:

Create a command: php artisan tasks:send-reminders

Finds tasks due tomorrow with status ≠ Completed.

Dispatches TaskReminderJob for each matching task.

Logs summary: tasks found, emails dispatched.

Database Requirements
Use migrations, seeders, and factories.

Eloquent relationships:

User → hasMany Task

Task → hasMany TaskActivityLog

Setup Instructions
Obtain the Project Code:
You can get the project code in one of two ways:

Clone the Repository (Recommended):

git clone <your-repo-url> task-manager
cd task-manager

(Replace <your-repo-url> with the actual URL of this GitHub/GitLab repository.)

Download as ZIP:
Download the project as a .zip file from the repository page and extract it to your desired location. Rename the extracted folder to task-manager if it's not already. Then navigate into the directory:

cd path/to/your/task-manager

Install PHP Dependencies:
If you cloned or downloaded the project, you need to install its PHP dependencies:

composer install

Install Laravel Breeze (for Authentication & Tailwind CSS):
(This step is only needed if you are setting up a fresh Laravel project and copying files, or if Breeze wasn't included in the initial clone/download. If you cloned this completed project, Breeze's dependencies should be handled by composer install.)

composer require laravel/breeze --dev
php artisan breeze:install blade # Choose Blade for UI
npm install && npm run dev

Copy .env.example to .env:

cp .env.example .env

Generate application key:

php artisan key:generate

Configure your database in .env:
Update the database connection details in your .env file:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager_db
DB_USERNAME=root
DB_PASSWORD=

(Ensure DB_DATABASE matches the name of the database you will create.)

Manually Create the Database:
Before running migrations, you need to manually create an empty database named task_manager_db in your MySQL server (e.g., via phpMyAdmin, MySQL Workbench, or command line).

Run database migrations and seeders:

php artisan migrate:fresh --seed

This will create the necessary tables and seed the database with:

Admin User: admin@example.com / password

Regular User: user@example.com / password

Sample tasks for both users.

Configure Queue Driver:
Ensure QUEUE_CONNECTION=database in your .env file for queued jobs to work.

Run Queue Listener (in a separate terminal):

php artisan queue:work

(This command will typically show a blinking cursor, indicating it's listening for jobs. Keep this terminal open.)

Start the Laravel Development Server:
Open another terminal window and navigate to your project directory.

php artisan serve

(This will start the web server, usually accessible at http://127.0.0.1:8000.)

Schedule Command (for reminders - Optional for local testing):
For automated daily reminders in a production environment, add the following to your system's cron job (Linux/macOS) or Task Scheduler (Windows):

* * * * * cd /path/to/your-project && php artisan schedule:run >> /dev/null 2>&1

(For local testing, you can manually run php artisan tasks:send-reminders in a separate terminal to dispatch reminder jobs.)

Job Queue and Command Usage
Sending Reminders Manually:
To manually trigger the reminder dispatch, run the custom Artisan command:

php artisan tasks:send-reminders

This command will find tasks due tomorrow (not completed) and add TaskReminderJob to the queue.

Processing Queued Jobs:
Ensure your queue worker is running:

php artisan queue:work

The TaskReminderJob will be processed by the worker, and a simulated email reminder will be logged to storage/logs/laravel.log.

# Gemini Project Context: Volunteer Web Application

## Project Overview

This is a **PHP/MySQL-based Volunteer Management System**, designed for the **Philippine Red Cross**. It facilitates the management of volunteers, events, deployments, and activity reporting.

### Key Features
*   **User Management:** distinct roles for **Admins** and **Volunteers**.
*   **Event Management:** Creation and tracking of volunteer events.
*   **Deployment System:** Assigning volunteers to specific events with roles.
*   **Activity Reporting:** Volunteers submit post-deployment reports (hours, activities, files).
*   **SMS Integration:** Automated notifications for deployments.
*   **Mapping:** Geospatial visualization of events and volunteers.

## Architecture

*   **Type:** Traditional LAMP/XAMPP Web Application.
*   **Frontend:** PHP files rendering HTML (mixed), styled with CSS (`style/`) and interactive via vanilla JS (`script/`, `profile/js/`).
*   **Backend:** Procedural PHP scripts in `utility/` acting as API endpoints and business logic handlers.
*   **Database:** MySQL, accessed via `mysqli` with prepared statements.
*   **Authentication:** Standard PHP Session management (`session_start()`).

## Directory Structure

*   **Root (`/`)**: Main entry points (`index.php`, `login.php`) and primary pages (`homePage.php`, `volunteers.php`, `events.php`).
*   **`utility/`**: **Core Backend Logic.** Database connection (`db.php`) and API endpoints (e.g., `submitActivityReport.php`, `deployVolunteer.php`).
*   **`profile/`**: Volunteer-specific dashboard and pages (`profile.php`, `activityReport.php`).
*   **`style/` & `script/`**: Global assets.
*   **`profile/style/` & `profile/js/`**: Assets specific to the volunteer profile section.
*   **`uploads/`**: Storage for user-uploaded content (images, documents).

## Setup & Configuration

### Prerequisites
*   Web Server (Apache/Nginx)
*   PHP (7.4+)
*   MySQL/MariaDB

### Database Setup
1.  Import `volunteer-web (5).sql` (Main schema).
2.  Import `database_activity_reports.sql` (Reporting module schema).
3.  Configure credentials in **`utility/db.php`**.

### Database Config (`utility/db.php`)
```php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "volunteer-web";
```

## Key Workflows

### 1. Authentication
*   **Entry:** `login.php`
*   **Logic:** Verifies credentials against `account` table. Sets `$_SESSION['user_id']` and `$_SESSION['user_type']`.
*   **Redirect:** Admins -> `homepage.php`, Volunteers -> `profile/profile.php`.

### 2. Deployment
*   **Logic:** `utility/deployVolunteer.php`
*   **Action:** Admins select a volunteer and event.
*   **Effect:** Creates record in `deployment` table, updates user status, triggers SMS.

### 3. Activity Reporting (New Feature)
*   **UI:** `profile/activityReport.php` (Volunteer side), `adminActivityReports.php` (Admin side).
*   **Submission:** POST to `utility/submitActivityReport.php`. Validates input, uploads files, inserts to `activity_reports`.
*   **Review:** Admins approve/reject via `utility/reviewActivityReport.php`.

## Development Conventions

*   **Database Access:** Always use `include_once 'utility/db.php'` (or relative path).
*   **Security:**
    *   Use **Prepared Statements** (`$conn->prepare()`) for all SQL queries containing user input.
    *   Check `isset($_SESSION['user_id'])` at the top of protected pages.
    *   Sanitize inputs and validate file uploads (types, sizes).
*   **API Responses:** Backend scripts in `utility/` typically return JSON: `echo json_encode(["success" => true, ...]);`.
*   **Error Handling:** Use `try-catch` blocks for DB operations and return meaningful JSON error messages.

## Recent Updates
*   **Activity Reporting Fixes:** Corrected parameter binding in `submitActivityReport.php` and variable scoping in `profile/activityReport.php`.
*   **Admin Review:** Added `utility/reviewActivityReport.php` to handle report approval/rejection.

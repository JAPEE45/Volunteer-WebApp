# 🚑 Red Cross Volunteer Management Web Application
> **Connecting Compassion with Action — Streamlined Volunteer Deployment & Disaster Response Management.**

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Leaflet](https://img.shields.io/badge/Leaflet-Interactive%20Maps-199900?style=for-the-badge&logo=leaflet&logoColor=white)](https://leafletjs.com/)
[![Chart.js](https://img.shields.io/badge/Chart.js-Analytics-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)](https://www.chartjs.org/)
[![Status](https://img.shields.io/badge/Status-Completed%20(Oct%202025)-success?style=for-the-badge)]()

---

## 📖 Overview

The **Red Cross Volunteer Management Web Application** is a specialized, end-to-end platform engineered to simplify and accelerate humanitarian workforce administration. Designed with the operations of humanitarian organizations like the **Philippine Red Cross** in mind, the platform bridges the gap between aspiring community volunteers and disaster response coordinators.

From multi-step digital applicant vetting and automated credential generation to geospatial deployment tracking and post-incident reporting, this system eliminates manual paperwork bottlenecks and provides chapter coordinators with real-time operational clarity during critical relief efforts.

---

## 🎯 Why Use This App? (User Benefits)

### 👥 Target Users
* **Chapter Administrators & Disaster Response Coordinators:** Staff who oversee volunteer onboarding, organize humanitarian operations, assign personnel to municipalities, and coordinate disaster response efforts.
* **Community Volunteers & Students:** Civic-minded individuals, youth leaders, and medical/relief volunteers looking for a transparent, organized way to register, view deployments, and submit field reports.

### 💡 Main Problems Solved
* **Elimination of Paper-Heavy Vetting:** Traditional volunteer enrollment relies heavily on physical paper forms, manual filing, and lost certificates. The app provides comprehensive digital intake with automated tracking of skills, blood types, emergency contacts, and credential file uploads.
* **Geographic Blind Spots in Personnel Dispatch:** When a typhoon or emergency strikes, administrators often struggle to visualize where volunteers are positioned. The integrated interactive GIS mapping module allows pinpointing deployment locations across municipalities (e.g., Catanduanes chapter operations: Virac, San Andres, Pandan, Bato, etc.).
* **Frictionless Credential Provisioning:** Instead of manual communication overhead, the application automatically provisions secure system credentials upon applicant approval, allowing swift mobilization.
* **Accountability & Field Reporting:** Deployed volunteers have a direct digital avenue to submit official situational and post-action activity reports (`.pdf`, `.docx`, `.doc`) directly to coordinators.

---

## ✨ Key Features

### 🛡️ Administrative Command Center
* **Live Analytics Dashboard:** Real-time visual metrics powered by Chart.js showcasing volunteer distribution, active deployments, upcoming relief events, and system alerts.
* **Volunteer Application Vetting & Workflow:** Review pending volunteer records, inspect uploaded verification documents and certificates, and execute one-click approvals or rejections.
* **Automated Secure Credential Dispatch:** Automatically generates randomized usernames and high-entropy passwords upon application acceptance, granting instant portal access.
* **Geospatial Volunteer & Event Mapping:** Interactive OpenStreetMap/Leaflet visualization displaying municipal deployment zones, active emergency events, and personnel headcounts per locality.
* **Relief Event Coordination:** Create, schedule, geolocate (latitude/longitude), and manage community relief and blood donation drives with automated volunteer roster assignments.
* **SMS Alert Logging:** Dedicated monitoring interface for tracking outgoing urgent dispatch notices and notifications sent to field volunteers.

### 🧑‍🤝‍🧑 Volunteer Self-Service Portal
* **Comprehensive Registration:** Detailed digital intake covering personal background, emergency health/medication records, educational history, civic affiliations, and supporting document uploads.
* **Personalized Profile Management:** View membership status, update profile pictures, and maintain personal credentials.
* **Interactive Mission Map:** Explore upcoming event locations, deployment stations, and territorial relief coverage across chapters.
* **Field Report Submission Center:** Secure multi-file upload utility (`.pdf`, `.doc`, `.docx`) enabling deployed personnel to submit post-mission documentation and situational updates.

---

## 🛠️ Tech Stack

| Domain | Technology / Library | Purpose |
| :--- | :--- | :--- |
| **Frontend** | HTML5, Modern CSS3 | Responsive layouts, CSS grid systems, and custom modular styling |
| | Vanilla JavaScript (ES6+) | Asynchronous data fetching (`Fetch API`), DOM manipulation, dynamic modals |
| | [Leaflet.js](https://leafletjs.com/) | Interactive GIS mapping with custom markers, coordinates, and popups |
| | [Chart.js](https://www.chartjs.org/) | Dynamic charting for volunteer distribution and event analytics |
| | [Font Awesome 5/6](https://fontawesome.com/) | Consistent, modern iconography across navigation and interface components |
| **Backend** | PHP 8.2 | RESTful utility endpoints, session handling, authentication, and file upload processing |
| | Prepared Statements (`mysqli`) | SQL injection mitigation and reliable database transactions |
| **Database** | MySQL / MariaDB | Relational schema storing users, accounts, deployments, and scheduled events |
| **Environment / Tools** | XAMPP / Apache | Local web server and runtime environment |
| | phpMyAdmin | Visual database administration and SQL import management |
| | Git & GitHub | Distributed version control and source code repository |

---

## 🔄 How to Use the System (Workflow)

```
 [ Volunteer ]                        [ Chapter Admin ]
       |                                     |
 1. Submit Registration Form                 |
    (Bio, Skills, Documents)                 |
       │                                     |
       ▼                                     ▼
 2. Application Pending ─────────► 3. Review Application & Documents
                                             │
                                     4. Approve Application
                                        (Auto-generates Username/Password)
                                             │
 5. Sign In to Volunteer Portal ◄────────────┘
       │
 6. View Assigned Missions / Map
       │
 7. Submit Field & Incident Reports
       │
       ▼
 8. Operations Monitored via Admin Dashboard
```

1. **Volunteer Registration:** The applicant navigates to the public portal (`register.php`), fills in their personal details, emergency health data, skills, and attaches required identification or certification files.
2. **Administrator Evaluation:** The coordinator logs into the admin panel (`login.php`), accesses the **Volunteers** and **SMS/Applications** queue, and reviews applicant qualifications and attached credentials.
3. **Approval & Account Creation:** Upon administrator approval, the system triggers the internal credential generator (`utility/updateVolunteerStatus.php`), assigning unique user access credentials (`account` table).
4. **Volunteer Portal Login:** The volunteer logs into their personalized portal (`profile/profile.php`) to view verified credentials and chapter announcements.
5. **Event Scheduling & Deployment:** Admins schedule relief drives under **Events** (`events.php`) with specific GPS coordinates, and deploy available volunteers to targeted zones (`Virac`, `San Andres`, `Pandan`, etc.).
6. **Live GIS Monitoring:** Both coordinators and volunteers interact with Leaflet-powered maps to inspect active relief operations and assigned volunteer distributions.
7. **Post-Action Reporting:** Deployed volunteers upload incident and accomplishment reports through the **Report** section (`profile/report.php`) for administrative review and archiving.

---

## 🚀 Installation & Setup Instructions

Follow these instructions to set up and run the application on your local machine using **XAMPP** (or any AMP stack).

### Prerequisites
* [XAMPP](https://www.apachefriends.org/) (PHP >= 8.0, Apache, and MySQL / MariaDB)
* [Git](https://git-scm.com/) installed on your machine
* A modern web browser (Google Chrome, Mozilla Firefox, Microsoft Edge)

---

### Step-by-Step Installation

#### 1. Clone the Repository
Clone the repository directly into your local web server's root directory (`htdocs` for XAMPP):

```bash
# Navigate to your XAMPP htdocs directory
cd C:/xampp/htdocs

# Clone the project repository
git clone https://github.com/JAPEE45/Volunteer-WebApp.git
```

#### 2. Start Apache and MySQL Services
Open the **XAMPP Control Panel** and click **Start** next to:
* **Apache**
* **MySQL**

#### 3. Set Up the Database
1. Open your web browser and navigate to phpMyAdmin:
   ```
   http://localhost/phpmyadmin/
   ```
2. Create a new database named:
   ```sql
   volunteer-web
   ```
3. Click on the newly created `volunteer-web` database, go to the **Import** tab.
4. Click **Choose File** and select the SQL dump file located in the root of the project:
   ```
   volunteer-web (1).sql
   ```
5. Click **Import** (or **Go**) at the bottom of the page to execute and create the tables.

#### 4. Configure Database Connection (Optional)
If your local MySQL uses custom credentials, open `utility/db.php` in a text editor and update the connection parameters:

```php
<?php
$host = "localhost";      
$user = "root";          // Your MySQL username (default: root)
$pass = "";              // Your MySQL password (default: empty)
$dbname = "volunteer-web";  

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

#### 5. Launch the Web Application
Open your web browser and navigate to:
```
http://localhost/Volunteer-WebApp/
```
* **Landing Page:** `http://localhost/Volunteer-WebApp/index.html` or `index.php`
* **Sign In:** `http://localhost/Volunteer-WebApp/login.php`
* **Volunteer Registration:** `http://localhost/Volunteer-WebApp/register.php`

---

### 🔑 Default Demo Accounts (from database seed)

| Role | Username | Password | Notes |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin123` | `pass@123` | Full access to Admin Dashboard, Map, Events, and Volunteers |
| **Volunteer** | `userD9BBBB` | `TDC{89[S)oa#` | Access to Volunteer Profile, Map, and Report Uploads |

---

## 🎓 Academic Background & Project Credits

This application was conceptualized, designed, and developed as an academic capstone/software project by a college student from:

🏛️ **Osmeña Colleges**  
📍 *Masbate City, Masbate, Philippines*  
👤 **Developer:** [Jasper Fernandez / @JAPEE45](https://github.com/JAPEE45)  
📅 **Date Completed:** **October 11, 2025**  

> *"Dedicated to supporting volunteer organizations and frontline humanitarians who bring hope and relief to communities in times of disaster."*

---

## 📄 License & Disclaimer

This project was developed for educational and community service purposes under academic evaluation. Logos and trademarks of the **Philippine Red Cross** belong to their respective registered owners and are utilized in this project strictly in an academic and demonstrative context.

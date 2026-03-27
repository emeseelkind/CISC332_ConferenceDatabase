# Kingston Prosthetics Research & Innovation Forum 2026
### Conference Management System (CISC 332 Project)

**Author:** Emese Elkind  
**Student ID:** 20337572  
**Class:** CISC 332 

---

## 📌 Project Overview
This is a functional, web-based interface for managing the "Kingston Prosthetics Research & Innovation Forum" (an imaginary conference for the purpose of this class). The application is designed for conference organizers to manage attendees, hotel assignments, sponsors, job postings, and financial reporting.

Built using **PHP (PDO)** and **MySQL**, the system follows a clean, professional design as required by the project specifications.

## 🛠️ Tech Stack
* **Backend:** PHP (using PDO for DBMS compatibility)
* **Database:** MySQL
* **Frontend:** HTML5, CSS
* **Environment:** XAMPP 

---

## 🚀 Installation & Setup (How to Run)
To run this application "out of the box" on a local server, follow these steps:

1.  **Move Files:** Place the entire `CISC332_ConferenceDatabase` folder into your local server's root directory (e.g., `C:\xampp\htdocs\`).
2.  **Start Services:** Open the XAMPP Control Panel and start **Apache** and **MySQL**.
3.  **Setup Database:**
    * Open `http://localhost/phpmyadmin`.
    * Create a new database named `conferenceDB` (or simply go to the SQL tab).
    * Import/Paste the provided `database_setup.sql` script and click **Go**.
4.  **Access the Site:**
    * Open your browser and navigate to: `http://localhost/CISC332_ConferenceDatabase/conference.php`

---

## 📋 Functional Requirements Implemented
The application fulfills the following requirements:
* **Member Management:** List sub-committee members via dropdown selection.
* **Logistics:** List students housed in specific hotel rooms.
* **Scheduling:** View conference schedules by specific dates.
* **Sponsorship:** List sponsors by level and manage company job ads.
* **Attendee Tracking:** Display three distinct lists (Students, Professionals, Sponsors).
* **Data Entry:** Add new attendees (with automatic student hotel room assignment).
* **Financials:** Real-time intake breakdown (Registration vs. Sponsorship).
* **Admin Tools:** Add/Delete sponsoring companies (including cascading deletes of associated data).
* **Flexibility:** Switch session days, times, and locations via the web interface.

---

## 📂 File Structure
* `conference.php` - **Main Home Page** (entry point).
* `header.php` - Global navigation and branding/logo.
* `db_connect.php` - Centralized PDO connection logic.
* `style.css` - Professional styling for tables, forms, and layout.
* `database_setup.sql` - Full DB schema and sample data.
* `/images` - Contains the official "Kingston Prosthetics Forum" logo.

---

## 🎥 Video Demo
A 3-5 minute video demonstration walking through all features can be found here:  
**[Link to YouTube/ONQ Video Here]**
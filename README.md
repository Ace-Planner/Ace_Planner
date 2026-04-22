Ace Planner – Backend System
________________________________________
Project Description
Ace Planner is a web-based productivity application designed to help students manage academic responsibilities such as tasks, courses, schedules, notifications, and study preferences.
The backend system is implemented using PHP and MySQL and provides the core functionality for data storage, user authentication, and application logic.
________________________________________
Technologies Used
•	PHP (Backend Development) 
•	MySQL (Database) 
•	PDO (Database Connectivity) 
•	XAMPP (Local Server Environment) 
•	Apache (.htaccess for routing) 
________________________________________
Project Structure
ace_planner_backend/

config/
  db.php

controllers/
  AuthController.php
  TaskController.php
  CourseController.php
  CalendarController.php
  NotificationController.php
  PreferenceController.php

routes/
  auth.php
  tasks.php
  courses.php
  calendar.php
  notifications.php
  preferences.php

utils/
  auth.php
  response.php

index.php
.htaccess
________________________________________
Setup Instructions
1.	Install XAMPP 
2.	Start Apache and MySQL from the XAMPP Control Panel 
3.	Move the project folder to: 
C:\xampp\htdocs\ace_planner_backend
4.	Open phpMyAdmin: 
http://localhost/phpmyadmin
5.	Create a database named: 
ace_planner
6.	Import the database schema (all tables): 
•	Users 
•	Tasks 
•	Courses 
•	CalendarEvents 
•	Notifications 
•	Preferences 
7.	Open config/db.php and verify: 
$db_name = "ace_planner";
$username = "root";
$password = "";
8.	Ensure .htaccess is configured: 
RewriteEngine On
RewriteRule ^ index.php [QSA,L]
9.	Restart Apache if needed 
________________________________________
How to Run
Open a browser or Postman and use:
http://localhost/ace_planner_backend/
________________________________________
API Endpoints
Authentication
•	POST /auth?action=register 
•	POST /auth?action=login 
•	POST /auth?action=logout 
•	GET /auth?action=me 
Tasks
•	GET /tasks 
•	POST /tasks 
Courses
•	GET /courses 
•	POST /courses 
•	PUT /courses?id=1 
•	DELETE /courses?id=1 
Calendar Events
•	GET /calendar 
•	POST /calendar 
•	PUT /calendar?id=1 
•	DELETE /calendar?id=1 
Notifications
•	GET /notifications 
•	POST /notifications?action=create 
•	POST /notifications?action=read&id=1 
•	DELETE /notifications?id=1 
Preferences
•	GET /preferences 
•	POST /preferences 
•	PUT /preferences 
•	DELETE /preferences 
________________________________________
Security Features
•	Passwords are securely hashed using password_hash 
•	Prepared statements are used to prevent SQL injection 
•	Session-based authentication is implemented 
•	Users can only access their own data 
________________________________________
Notes
•	The backend is organized using controllers and routes for modularity 
•	Each controller corresponds to a specific system feature 
•	The application is designed for scalability and maintainability 
________________________________________
Author
•	Devin Rockwell
•	Harmony Brown 
•	Maxwell Ajala
•	Javion Whitfield 
•	Taylor Davis
________________________________________
Conclusion
The Ace Planner backend provides a structured and secure system for managing academic productivity data. It demonstrates proper backend design, database integration, and modular application architecture.


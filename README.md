# 🧠 Smart E-Notes Management System

A web-based platform developed to help students and teachers **upload, manage, and share notes** in digital form.  
This project is designed as a **college project** by *Akash (BCA Student)* using **PHP** and **MySQL**.

---

## 🚀 Features

✅ **User Registration & Login System**  
✅ **Upload & Download Notes** (PDF, DOCX, PPT, etc.)  
✅ **Search Notes by Subject, Title, or Author**  
✅ **Admin Panel** to manage users and uploaded files  
✅ **Responsive Frontend** (works on mobile & desktop)  
✅ **Secure Database Storage** using MySQL  
✅ **Simple & User-Friendly Interface**

---

## 🛠️ Technologies Used

| Layer | Technology |
|--------|-------------|
| **Frontend** | HTML, CSS, JavaScript, Bootstrap |
| **Backend** | PHP (Core PHP) |
| **Database** | MySQL |
| **Server Environment** | XAMPP / Apache |
| **Version Control** | Git + GitHub |

---

## ⚙️ Setup Instructions

Follow these steps to run the project locally 👇

### 1️⃣ Requirements
- [XAMPP](https://www.apachefriends.org/) installed on your system  
- Web browser (Chrome, Edge, or Firefox)  
- GitHub account (for project hosting)

### 3️⃣ Steps to Run
1. Copy this folder `Smart-E-Notes-Management-System` to `C:\xampp\htdocs\`
2. Open XAMPP Control Panel → Start Apache & MySQL
3. Go to phpMyAdmin and create a database named `smart_enotes`
4. Import the SQL file from `database/smart_enotes.sql`
5. Open `includes/config.php` and check your database connection details:
   ```php
   $conn = mysqli_connect("localhost", "root", "", "smart_enotes");
6.	Now open your browser and type:
              http://localhost/Smart-E-Notes-Management-System/

7.	✅ Done! Your project will open.


## 📸 Screenshots

### 🪪 Login Page
![Login Page](screenshots/login.png)

### 📚 Dashboard
![Dashboard](screenshots/dashboard.png)



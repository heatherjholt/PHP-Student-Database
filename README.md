# CSCI4410 Student Database Project

## Overview
This project is a dynamic PHP web application connected to a MySQL database for managing student records. It features a modern, clean "Control Panel" interface where users can view, insert, update, and delete student data.

[Live Demo]()

## Features
- **View Records:** Display all students, filter by gender, find students older than 21, or find students missing phone numbers.
- **Analytics:** Count distinct majors among the student body.
- **Manage Data:** Insert new student records, update existing phone numbers via BlueCard ID, or delete records.
- **Modern UI:** Styled with a clean, flat CSS grid layout for an organized, dashboard-like feel.

## Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) installed (Standard Windows path: `C:\xampp\`)
- Apache and MySQL services running via the XAMPP Control Panel.

## Installation & Setup

1. **Move Project Files:** Create a folder named `csci4410` inside your XAMPP web directory (e.g., `C:\xampp\htdocs\csci4410\`) and place the following files inside:
   - `index.php`
   - `ola6.css`
   - `Students.sql`

2. **Set Up the Database:**
   - Open your web browser and navigate to `http://localhost/phpmyadmin/`.
   - Click on the **Import** tab at the top.
   - Click **Choose File**, select the `Students.sql` file from your project folder, and click **Import** (or **Go**) at the bottom of the page. This will automatically build the `CSCI4410` database and insert the starter student data.

3. **Run the Application:**
   - Open a new browser tab and navigate to `http://localhost/csci4410/`.
   - The Student Database Control Panel should load immediately, ready to accept queries.
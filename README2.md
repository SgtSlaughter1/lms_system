# Library Management System (LMS)

## Overview
A comprehensive PHP-based Library Management System that enables students to borrow books, track their borrowing history, and manage returns. The system implements core OOP principles and follows modern PHP practices.

## Features

### Student Features
- Browse and search available books
- Borrow books with return date selection
- View currently borrowed books
- Track borrowing history
- Return books
- Overdue notifications
- Maximum 3 books borrowing limit

### Admin Features
- Manage books (CRUD operations)
- Track all transactions
- Monitor overdue books
- Student management
- Book inventory management

## Technical Architecture

### Core Components

1. **Authentication System**
   - Student and Admin roles
   - Session-based authentication
   - Secure login/logout functionality

2. **Transaction Management**
   - Borrowing validation
   - Return processing
   - History tracking
   - Overdue calculation

3. **Database Structure**



## Key Implementations

### 1. Borrowing System
- Validates student eligibility
- Checks book availability
- Implements 30-day return policy
- Tracks overdue status

### 2. Return System
- Updates book availability
- Records return timestamp
- Calculates overdue status
- Updates transaction status

### 3. Transaction History
- Complete borrowing records
- Status tracking
- Date management
- Student-specific history

## Security Features

1. **Input Validation**
   - Form data sanitization
   - SQL injection prevention
   - XSS protection

2. **Authentication**
   - Role-based access control
   - Session management
   - Secure password handling

## Installation

1. Clone the repository
2. Import the database schema
3. Configure database connection in `config/database.php`
4. Set up your web server (Apache/Nginx)
5. Access the system through the browser

## Usage

### Student Access
1. Login with student credentials
2. Browse available books
3. Borrow books (max 3)
4. View borrowing history
5. Return books

### Admin Access
1. Login with admin credentials
2. Manage books inventory
3. View all transactions
4. Monitor overdue books
5. Manage student accounts

## Dependencies
- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5.3.0
- Bootstrap Icons 1.7.2

## Future Enhancements
1. Email notifications for overdue books
2. Fine calculation system
3. Book reservation system
4. PDF generation for receipts
5. Advanced search filters

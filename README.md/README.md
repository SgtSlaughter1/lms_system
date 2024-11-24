### **School Library Management System**

#### **Project Overview**

You are tasked with building a **School Library Management System** to demonstrate your expertise in **Object-Oriented Programming (OOP)** using PHP. This project will test your understanding of core and advanced OOP concepts, including **classes**, **interfaces**, **namespaces**, **traits**, **inheritance**, **encapsulation**, and **polymorphism** etc.

## The system must include functionalities for managing books, students, and library transactions. Follow the provided folder structure and instructions carefully.

#### **Folder Structure**

Your project folder must follow this structure:

```plaintext
school-library-management/
├── config/
│   └── database.php         # Handles database connections.
├── database/
│   └── database.sql         # Contains the database schema and sample data.
├── models/
│   ├── Book.php             # Represents a Book entity.
│   ├── Student.php          # Represents a Student entity.
│   ├── Library.php          # Core library functions.
│   ├── LibraryTransaction.php # Tracks issuing/returning of books.
│   ├── Interfaces/          # Directory for all interfaces.
│   │   ├── Borrowable.php   # Interface for borrowable items.
│   ├── Traits/              # Directory for reusable traits.
│       ├── Timestamps.php   # Trait for managing timestamps.
├── controllers/
│   ├── BookController.php   # Handles book-related operations.
│   ├── StudentController.php# Handles student-related operations.
│   ├── LibraryController.php# Handles transactions and logic.
├── views/
│   ├── index.php            # Dashboard or homepage for the system.
│   ├── books.php            # Displays all books and their availability.
│   ├── students.php         # Displays all students and their borrowing history.
├── public/
│   └── index.php            # Entry point for the application.

├── README.md                # Task description and project documentation.
├── .gitignore               # Git ignore file.
```

---

#### **Requirements**

1. **Core Features**

   - **Book Management**: CRUD operations for books.
   - **Student Management**: CRUD operations for students.
   - **Library Transactions**: Borrowing and returning books.
   - **Availability Tracking**: Ensure books cannot be issued if unavailable.
   - **Borrow Limits**: Limit students to 3 books at a time.

2. **Use OOP Principles**

   - Implement **encapsulation**: Use private/protected properties and expose behavior via methods.
   - Leverage **inheritance**: Reuse logic where applicable.
   - Apply **polymorphism**: Use method overriding to customize functionality.
   - Use **abstraction** via interfaces for shared behavior.

3. **Advanced OOP Features**
   - **Namespaces**: Use namespaces to organize your code (e.g., `App\Models`, `App\Controllers`, etc.).
   - **Interfaces**: Define contracts for shared behavior (e.g., a `Borrowable` interface for items that can be borrowed).
   - **Traits**: Reuse common functionality (e.g., a `Timestamps` trait for handling creation and update times).

---

#### **Technical Guidelines**

1. **Interfaces**

   - Create a `Borrowable` interface with the following methods:
     ```php
     interface Borrowable {
         public function borrow($studentId);
         public function returnItem($studentId);
     }
     ```
   - Implement this interface in the `Book` and `LibraryTransaction` classes.

2. **Traits**

   - Create a `Timestamps` trait to handle `created_at` and `updated_at` properties:

     ```php
     trait Timestamps {
         private $created_at;
         private $updated_at;

         public function setTimestamps() {
             $this->created_at = date('Y-m-d H:i:s');
             $this->updated_at = date('Y-m-d H:i:s');
         }

         public function updateTimestamp() {
             $this->updated_at = date('Y-m-d H:i:s');
         }
     }
     ```

   - Use this trait in classes that need timestamps (e.g., `Book`, `LibraryTransaction`).

3. **Namespaces**

   - Use namespaces to organize your code. For example:
     - `App\Models\Book`
     - `App\Models\Student`
     - `App\Controllers\BookController`

4. **Database Schema**
   - **books**: `id`, `title`, `author`, `isbn`, `available_copies`, `created_at`, `updated_at`.
   - **students**: `id`, `name`, `email`, `phone`, `total_books_borrowed`, `created_at`, `updated_at`.
   - **transactions**: `id`, `student_id`, `book_id`, `borrow_date`, `return_date`, `status`, `created_at`, `updated_at`.

---

#### **Submission Instructions**

1. Create a GitHub repository for your project.
2. Ensure your repository is structured as specified.
3. Write a comprehensive `README.md` explaining:
   - How to set up the project locally.
   - How to configure the database.
   - How to test your application.
4. Push your code and share the repository link in the group.

**Deadline**: **[One Week]**

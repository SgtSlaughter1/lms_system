# MVC Implementation Details

## MVC Structure
MVC Flow:
├── Model (Student.php)
│ └── Database Operations
├── View (profile.php)
│ └── Display Logic
└── Controller (StudentController.php)
└── Business Logic


## 1. Model (Student.php)

php
class Student {
private $db;
public function construct($db) {
$this->db = $db;
}
public function getStudentById($id) {
$sql = "SELECT FROM students WHERE id = ?";
$stmt = $this->db->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
return $result->fetch_assoc();
}
}

**Purpose:**
- Handles all database interactions
- Contains data-related logic
- Manages student records
- Returns formatted data

**Responsibilities:**
1. Database queries
2. Data validation
3. Data formatting
4. Error handling

## 2. View (profile.php)

php
// Session and security
session_start();
require_once dirname(DIR) . "/config/database.php";
require_once dirname(DIR) . "/controllers/StudentController.php";
// Authentication
if (!isset($SESSION['student_id'])) {
header("location: /lms_system/Auth/login.php");
exit();
}
// Get data through controller
$studentController = new StudentController($connect);
$student = $studentController->getStudentProfile($SESSION['student_id']);
// Display HTML
<div class="container py-5">
<div class="row">
<!-- Display student data -->
<h4><?php echo htmlspecialchars($student['name']); ?></h4>
<!-- More display logic -->
</div>
</div>

**Purpose:**
- Displays data to user
- Handles presentation logic
- Manages user interface
- Formats data presentation

**Responsibilities:**
1. Data presentation
2. User interface
3. Display formatting
4. Basic input validation

## 3. Controller (StudentController.php)

php
class StudentController {
private $studentModel;
private $db;
public function construct($db) {
$this->db = $db;
$this->studentModel = new Student($db);
}
public function getStudentProfile($student_id) {
return $this->studentModel->getStudentById($student_id);
}
}

**Purpose:**
- Connects Model and View
- Handles business logic
- Manages data flow
- Controls application flow

**Responsibilities:**
1. Request handling
2. Data processing
3. Model interaction
4. View selection

## How They Connect

### 1. Database to Model Connection

php
// In config/database.php
$connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// In Student.php (Model)
class Student {
private $db;
public function construct($db) {
$this->db = $db; // Database connection passed to model
}
}

### 2. Model to Controller Connection

php
// In StudentController.php
class StudentController {
public function construct($db) {
$this->studentModel = new Student($db); // Model instantiated in controller
}
}


### 3. Controller to View Connection
php
// In profile.php (View)
$studentController = new StudentController($connect);
$student = $studentController->getStudentProfile($SESSION['student_id']);


## Data Flow Process
Complete Flow:
1. User Request
└── View receives request
View to Controller
└── Controller instantiated
└── Method called
Controller to Model
└── Data requested
└── Query prepared
Model to Database
└── Query executed
└── Data retrieved
Data Return Path
└── Model formats data
└── Controller processes
└── View displays


## MVC Interaction Example

php
// 1. User accesses profile page
// profile.php (View)
$studentController = new StudentController($connect);
// 2. Controller processes request
// StudentController.php
public function getStudentProfile($student_id) {
return $this->studentModel->getStudentById($student_id);
}
// 3. Model retrieves data
// Student.php
public function getStudentById($id) {
$sql = "SELECT FROM students WHERE id = ?";
$stmt = $this->db->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
return $stmt->get_result()->fetch_assoc();
}
// 4. View displays data
// profile.php
<h4><?php echo htmlspecialchars($student['name']); ?></h4>


## Benefits of This MVC Implementation
1. **Separation of Concerns**
   - Each component has specific responsibilities
   - Code is organized and maintainable
   - Easy to modify individual parts

2. **Security**
   - Centralized data validation
   - Prepared statements in Model
   - Sanitized output in View

3. **Maintainability**
   - Modular code structure
   - Easy to extend functionality
   - Clear data flow

4. **Scalability**
   - Easy to add new features
   - Simple to modify existing functionality
   - Clear structure for growth
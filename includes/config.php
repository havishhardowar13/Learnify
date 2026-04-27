<?php
// config.php - Database configuration and session management

class Database {
    private $host = "localhost";
    private $db_name = "lms_system";
    private $username = "root";
    private $password = "";
    public $conn;
    public $error;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", 
                $this->username, 
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $exception) {
            $this->error = "Connection error: " . $exception->getMessage();
            error_log($this->error); // Log error for debugging
        }
        return $this->conn;
    }

    // Generic function to execute prepared statements
    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->error = "Query error: " . $e->getMessage();
            error_log($this->error);
            return false;
        }
    }

    // Fetch single row
    public function fetchSingle($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt ? $stmt->fetch() : false;
    }

    // Fetch all rows
    public function fetchAll($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt ? $stmt->fetchAll() : false;
    }

    // Get row count
    public function rowCount($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt ? $stmt->rowCount() : 0;
    }

    // Get last inserted ID
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400, // 24 hours
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'secure' => isset($_SERVER['HTTPS']), // Use secure cookies in HTTPS
        'httponly' => true, // Prevent JavaScript access
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Debug: Log session state for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Backward compatibility: If user_role exists, copy to role
if (isset($_SESSION['user_role']) && !isset($_SESSION['role'])) {
    $_SESSION['role'] = $_SESSION['user_role'];
    error_log("Copied user_role to role: " . $_SESSION['role']);
}

// Or if role exists, copy to user_role for compatibility
if (isset($_SESSION['role']) && !isset($_SESSION['user_role'])) {
    $_SESSION['user_role'] = $_SESSION['role'];
    error_log("Copied role to user_role: " . $_SESSION['user_role']);
}

// Debug session if requested
if (isset($_GET['debug']) && $_GET['debug'] == 'session') {
    echo "<pre>";
    echo "Session ID: " . session_id() . "\n";
    echo "Session status: " . session_status() . "\n";
    echo "Session data:\n";
    print_r($_SESSION);
    echo "</pre>";
    exit;
}
// =============================================================

// Define BASE_URL for your local setup
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    // For local development
    define('BASE_URL', 'http://localhost/Learnify/');
} else {
    // For production (update with your actual domain)
    define('BASE_URL', 'https://yourdomain.com/');
}

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Timezone setting
date_default_timezone_set('UTC');

// Error reporting (disable in production)
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Global functions
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

function isInstructor() {
    return isLoggedIn() && $_SESSION['user_role'] === 'instructor';
}

function isStudent() {
    return isLoggedIn() && $_SESSION['user_role'] === 'student';
}

function checkAuth($required_role = null) {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect('login.php');
    }
    
    if ($required_role && $_SESSION['user_role'] !== $required_role) {
        $_SESSION['error'] = "You don't have permission to access this page.";
        redirect('dashboard.php');
    }
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Check if database connection failed
if ($database->error) {
    // Don't show detailed errors in production
    if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
        die("Database connection failed: " . $database->error);
    } else {
        error_log("Database connection failed: " . $database->error);
        die("System temporarily unavailable. Please try again later.");
    }
}

// Auto-create tables if they don't exist (for development)
function createTablesIfNotExist($db) {
    $tables = [
        "users" => "CREATE TABLE IF NOT EXISTS users (
            user_id INT PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(255) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            role ENUM('student', 'instructor', 'admin') NOT NULL,
            profile_picture VARCHAR(500),
            bio TEXT,
            date_joined DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_login DATETIME,
            is_active BOOLEAN DEFAULT TRUE
        )",
        
        "courses" => "CREATE TABLE IF NOT EXISTS courses (
            course_id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            instructor_id INT NOT NULL,
            thumbnail_url VARCHAR(500),
            price DECIMAL(10,2) DEFAULT 0.00,
            is_published BOOLEAN DEFAULT FALSE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (instructor_id) REFERENCES users(user_id) ON DELETE CASCADE
        )",
        
        "enrollments" => "CREATE TABLE IF NOT EXISTS enrollments (
            enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
            student_id INT NOT NULL,
            course_id INT NOT NULL,
            enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            completion_status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
            completed_at DATETIME,
            FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
            UNIQUE KEY unique_enrollment (student_id, course_id)
        )"
    ];

    foreach ($tables as $tableName => $createSQL) {
        try {
            $db->exec($createSQL);
        } catch (PDOException $e) {
            error_log("Error creating table $tableName: " . $e->getMessage());
        }
    }
}

// Only auto-create tables in development environment
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    createTablesIfNotExist($db);
    
    // Insert sample admin user if no users exist
    $userCount = $database->rowCount("SELECT COUNT(*) FROM users");
    if ($userCount == 0) {
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $database->executeQuery(
            "INSERT INTO users (email, password_hash, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)",
            ['admin@Learnify.com', $adminPassword, 'System', 'Administrator', 'admin']
        );
    }
}

?>

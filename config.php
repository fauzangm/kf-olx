<?php
/**
 * Database Configuration for KF OLX
 * Using PDO with MySQL
 */

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'kf_olx');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'KF OLX');
define('SITE_URL', 'http://localhost/kf-olx');
define('UPLOAD_PATH', 'uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

/**
 * Database connection class using PDO
 */
class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    /**
     * Get database connection
     * @return PDO
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

/**
 * User class for user management
 */
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Register new user
     * @param string $name
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function register($name, $email, $password) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:name, :email, :password)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));
        
        // Bind values
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    /**
     * User login
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function login($email, $password) {
        $query = "SELECT id, name, email, password FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['logged_in'] = true;
            
            return true;
        }
        
        return false;
    }

    /**
     * Check if user is logged in
     * @return boolean
     */
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Get user by ID
     * @param int $user_id
     * @return array
     */
    public function getUserById($user_id) {
        $query = "SELECT id, name, email, created_at FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if email exists
     * @param string $email
     * @return boolean
     */
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Logout user
     */
    public function logout() {
        session_unset();
        session_destroy();
    }
}

/**
 * Category class for category management
 */
class Category {
    private $conn;
    private $table_name = "categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get all categories
     * @return array
     */
    public function getAllCategories() {
        $query = "SELECT id, name, icon FROM " . $this->table_name . " ORDER BY name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get category by ID
     * @param int $category_id
     * @return array
     */
    public function getCategoryById($category_id) {
        $query = "SELECT id, name, icon FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $category_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Add new category
     * @param string $name
     * @param string $icon
     * @return boolean
     */
    public function addCategory($name, $icon = '') {
        $query = "INSERT INTO " . $this->table_name . " (name, icon) VALUES (:name, :icon)";
        
        $stmt = $this->conn->prepare($query);
        
        $name = htmlspecialchars(strip_tags($name));
        $icon = htmlspecialchars(strip_tags($icon));
        
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":icon", $icon);
        
        return $stmt->execute();
    }
}

/**
 * Ad class for advertisement management
 */
class Ad {
    private $conn;
    private $table_name = "ads";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create new ad
     * @param int $user_id
     * @param int $category_id
     * @param string $title
     * @param string $description
     * @param float $price
     * @param string $location
     * @return boolean
     */
    public function createAd($user_id, $category_id, $title, $description, $price, $location) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, category_id, title, description, price, location) 
                  VALUES (:user_id, :category_id, :title, :description, :price, :location)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $title = htmlspecialchars(strip_tags($title));
        $description = htmlspecialchars(strip_tags($description));
        $location = htmlspecialchars(strip_tags($location));
        
        // Bind values
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":location", $location);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }

    /**
     * Get all ads with pagination
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllAds($limit = 10, $offset = 0) {
        $query = "SELECT a.*, u.name as user_name, c.name as category_name, c.icon as category_icon
                  FROM " . $this->table_name . " a
                  LEFT JOIN users u ON a.user_id = u.id
                  LEFT JOIN categories c ON a.category_id = c.id
                  ORDER BY a.created_at DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get ad by ID
     * @param int $ad_id
     * @return array
     */
    public function getAdById($ad_id) {
        $query = "SELECT a.*, u.name as user_name, u.email as user_email, c.name as category_name
                  FROM " . $this->table_name . " a
                  LEFT JOIN users u ON a.user_id = u.id
                  LEFT JOIN categories c ON a.category_id = c.id
                  WHERE a.id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $ad_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get ads by user ID
     * @param int $user_id
     * @return array
     */
    public function getAdsByUserId($user_id) {
        $query = "SELECT a.*, c.name as category_name
                  FROM " . $this->table_name . " a
                  LEFT JOIN categories c ON a.category_id = c.id
                  WHERE a.user_id = :user_id
                  ORDER BY a.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search ads
     * @param string $keyword
     * @param int $category_id
     * @param string $location
     * @return array
     */
    public function searchAds($keyword = '', $category_id = 0, $location = '') {
        $query = "SELECT a.*, u.name as user_name, c.name as category_name
                  FROM " . $this->table_name . " a
                  LEFT JOIN users u ON a.user_id = u.id
                  LEFT JOIN categories c ON a.category_id = c.id
                  WHERE 1=1";
        
        $params = [];
        
        if(!empty($keyword)) {
            $query .= " AND (a.title LIKE :keyword OR a.description LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        
        if($category_id > 0) {
            $query .= " AND a.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }
        
        if(!empty($location)) {
            $query .= " AND a.location LIKE :location";
            $params[':location'] = '%' . $location . '%';
        }
        
        $query .= " ORDER BY a.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update ad
     * @param int $ad_id
     * @param string $title
     * @param string $description
     * @param float $price
     * @param string $location
     * @return boolean
     */
    public function updateAd($ad_id, $title, $description, $price, $location) {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, description = :description, price = :price, location = :location
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $title = htmlspecialchars(strip_tags($title));
        $description = htmlspecialchars(strip_tags($description));
        $location = htmlspecialchars(strip_tags($location));
        
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":location", $location);
        $stmt->bindParam(":id", $ad_id);
        
        return $stmt->execute();
    }

    /**
     * Delete ad
     * @param int $ad_id
     * @return boolean
     */
    public function deleteAd($ad_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $ad_id);
        
        return $stmt->execute();
    }
}

/**
 * AdImage class for managing ad images
 */
class AdImage {
    private $conn;
    private $table_name = "ad_images";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Add image to ad
     * @param int $ad_id
     * @param string $image_path
     * @return boolean
     */
    public function addImage($ad_id, $image_path) {
        $query = "INSERT INTO " . $this->table_name . " (ad_id, image_path) VALUES (:ad_id, :image_path)";
        
        $stmt = $this->conn->prepare($query);
        
        $image_path = htmlspecialchars(strip_tags($image_path));
        
        $stmt->bindParam(":ad_id", $ad_id);
        $stmt->bindParam(":image_path", $image_path);
        
        return $stmt->execute();
    }

    /**
     * Get images by ad ID
     * @param int $ad_id
     * @return array
     */
    public function getImagesByAdId($ad_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ad_id = :ad_id ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ad_id", $ad_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete image
     * @param int $image_id
     * @return boolean
     */
    public function deleteImage($image_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $image_id);
        
        return $stmt->execute();
    }

    /**
     * Delete all images for an ad
     * @param int $ad_id
     * @return boolean
     */
    public function deleteImagesByAdId($ad_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ad_id = :ad_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ad_id", $ad_id);
        
        return $stmt->execute();
    }
}

/**
 * Helper functions
 */

/**
 * Format price to Indonesian Rupiah
 * @param float $price
 * @return string
 */
function formatRupiah($price) {
    return "Rp " . number_format($price, 0, ',', '.');
}

/**
 * Format date to Indonesian format
 * @param string $date
 * @return string
 */
function formatDate($date) {
    $timestamp = strtotime($date);
    return date("d M Y, H:i", $timestamp);
}

/**
 * Get time ago from date
 * @param string $date
 * @return string
 */
function timeAgo($date) {
    $timestamp = strtotime($date);
    $difference = time() - $timestamp;
    
    $seconds = $difference;
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2629440);
    $years = round($seconds / 31557600);
    
    if ($seconds <= 60) {
        return "Baru saja";
    } else if ($minutes <= 60) {
        return "$minutes menit yang lalu";
    } else if ($hours <= 24) {
        return "$hours jam yang lalu";
    } else if ($days <= 7) {
        return "$days hari yang lalu";
    } else if ($weeks <= 4) {
        return "$weeks minggu yang lalu";
    } else if ($months <= 12) {
        return "$months bulan yang lalu";
    } else {
        return "$years tahun yang lalu";
    }
}

/**
 * Generate random string
 * @param int $length
 * @return string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Create upload directory if not exists
 */
function createUploadDirectory() {
    if (!file_exists(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0777, true);
    }
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize classes
$user = new User($db);
$category = new Category($db);
$ad = new Ad($db);
$adImage = new AdImage($db);

// Create upload directory
createUploadDirectory();
?>

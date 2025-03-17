<?php
// Require database connection file
require_once 'db_connect.php';

// Check if database connection is successful
if (!isset($db_connected) || $db_connected !== true) {
    die("Database connection failed");
}

/**
 * Function to get all users
 * @return array Array of users
 */
function getAllUsers() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM users ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error getting users: " . $e->getMessage());
        return [];
    }
}

/**
 * Function to get a single user by ID
 * @param int $id User ID
 * @return array|bool User data or false if not found
 */
function getUserById($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Error getting user: " . $e->getMessage());
        return false;
    }
}

/**
 * Function to add a new user
 * @param string $username Username
 * @param string $password Password
 * @param string $email Email
 * @param string $role Role (admin or user)
 * @return bool Success status
 */
function addUser($username, $password, $email, $role) {
    global $conn;
    try {
        // Hash password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, created_at) 
                                VALUES (:username, :password, :email, :role, NOW())");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Error adding user: " . $e->getMessage());
        return false;
    }
}

/**
 * Function to update an existing user
 * @param int $id User ID
 * @param string $username Username
 * @param string $password Password (optional)
 * @param string $email Email
 * @param string $role Role
 * @return bool Success status
 */
function updateUser($id, $username, $password, $email, $role) {
    global $conn;
    try {
        // If password is provided, update it. Otherwise, keep the existing password
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = :username, password = :password, 
                                   email = :email, role = :role WHERE id = :id");
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = :username, 
                                   email = :email, role = :role WHERE id = :id");
        }
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Error updating user: " . $e->getMessage());
        return false;
    }
}

/**
 * Function to delete a user and resequence IDs
 * @param int $id User ID
 * @return bool Success status
 */
function deleteUser($id) {
    global $conn;
    try {
        // First, delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $deleteResult = $stmt->execute();
        
        if (!$deleteResult) {
            return false;
        }
        
        // Now, update the IDs of all users with IDs greater than the deleted ID
        // This approach doesn't rely on transactions
        $stmt = $conn->prepare("SET @count = 0");
        $stmt->execute();
        
        $stmt = $conn->prepare("UPDATE users SET id = (@count:=@count+1) ORDER BY id ASC");
        $updateResult = $stmt->execute();
        
        // Reset auto increment value
        $stmt = $conn->prepare("ALTER TABLE users AUTO_INCREMENT = 1");
        $stmt->execute();
        
        return $updateResult;
    } catch(PDOException $e) {
        error_log("Error deleting user or resequencing IDs: " . $e->getMessage());
        return false;
    }
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form was submitted
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    switch ($action) {
        case 'add':
            // Add a new user
            if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['role'])) {
                $result = addUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['role']);
                if ($result) {
                    header('Location: admin_user_view.php?message=User added successfully');
                    exit;
                } else {
                    header('Location: admin_user_view.php?error=Failed to add user');
                    exit;
                }
            }
            break;
            
        case 'update':
            // Update an existing user
            if (isset($_POST['id']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['role'])) {
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                $result = updateUser($_POST['id'], $_POST['username'], $password, $_POST['email'], $_POST['role']);
                if ($result) {
                    header('Location: admin_user_view.php?message=User updated successfully');
                    exit;
                } else {
                    header('Location: admin_user_view.php?error=Failed to update user');
                    exit;
                }
            }
            break;
            
        case 'delete':
            // Delete a user
            if (isset($_POST['id'])) {
                $result = deleteUser($_POST['id']);
                if ($result) {
                    header('Location: admin_user_view.php?message=User deleted successfully');
                    exit;
                } else {
                    header('Location: admin_user_view.php?error=Failed to delete user');
                    exit;
                }
            }
            break;
    }
}

// Process GET actions
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $result = deleteUser($_GET['id']);
    if ($result) {
        header('Location: admin_user_view.php?message=User deleted successfully');
        exit;
    } else {
        header('Location: admin_user_view.php?error=Failed to delete user');
        exit;
    }
}
?>
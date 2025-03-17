<?php
require_once 'db_connect.php';
session_start();

// Đã loại bỏ kiểm tra đăng nhập admin

// Initialize variables
$title = $excerpt = $content = $author = '';
$image = 'news-bg-1.jpg'; // Default image
$news_id = 0;
$error = $success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Add new news article
    if (isset($_POST['add_news'])) {
        $title = trim($_POST['title']);
        $excerpt = trim($_POST['excerpt']);
        $content = trim($_POST['content']);
        $author = trim($_POST['author']);
        
        // Basic validation
        if (empty($title) || empty($excerpt) || empty($content) || empty($author)) {
            $error = "All fields are required";
        } else {
            // Handle image upload if provided
            if (!empty($_FILES['image']['name'])) {
                $target_dir = "assets/img/latest-news/";
                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $new_filename = "news-bg-" . time() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                // Check if valid image
                $valid_extensions = array("jpg", "jpeg", "png", "gif");
                if (in_array($file_extension, $valid_extensions)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                        $image = $new_filename;
                    } else {
                        $error = "Error uploading file. Using default image.";
                    }
                } else {
                    $error = "Only JPG, JPEG, PNG & GIF files are allowed. Using default image.";
                }
            }
            
            if (empty($error)) {
                // Insert into database using PDO instead of mysqli
                $current_date = date('Y-m-d H:i:s');
                $sql = "INSERT INTO news (title, excerpt, content, image, author, published_date, created_at) 
                        VALUES (:title, :excerpt, :content, :image, :author, :published_date, :created_at)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':excerpt', $excerpt);
                $stmt->bindParam(':content', $content);
                $stmt->bindParam(':image', $image);
                $stmt->bindParam(':author', $author);
                $stmt->bindParam(':published_date', $current_date);
                $stmt->bindParam(':created_at', $current_date);
                
                if ($stmt->execute()) {
                    $success = "Article added successfully";
                    // Clear form fields
                    $title = $excerpt = $content = $author = '';
                    $image = 'news-bg-1.jpg';
                } else {
                    $error = "Error: " . implode(", ", $stmt->errorInfo());
                }
            }
        }
    }
    
    // Update existing news article
    if (isset($_POST['update_news'])) {
        $news_id = (int)$_POST['news_id'];
        $title = trim($_POST['title']);
        $excerpt = trim($_POST['excerpt']);
        $content = trim($_POST['content']);
        $author = trim($_POST['author']);
        
        // Basic validation
        if (empty($title) || empty($excerpt) || empty($content) || empty($author)) {
            $error = "All fields are required";
        } else {
            // Check if image is being updated
            $update_image = false;
            if (!empty($_FILES['image']['name'])) {
                $target_dir = "assets/img/latest-news/";
                $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $new_filename = "news-bg-" . time() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                // Check if valid image
                $valid_extensions = array("jpg", "jpeg", "png", "gif");
                if (in_array($file_extension, $valid_extensions)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                        $image = $new_filename;
                        $update_image = true;
                    } else {
                        $error = "Error uploading file. Image not updated.";
                    }
                } else {
                    $error = "Only JPG, JPEG, PNG & GIF files are allowed. Image not updated.";
                }
            } else {
                // Keep existing image with PDO
                $image_query = "SELECT image FROM news WHERE id = :news_id";
                $image_stmt = $conn->prepare($image_query);
                $image_stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
                $image_stmt->execute();
                $image_row = $image_stmt->fetch();
                $image = $image_row['image'];
            }
            
            if (empty($error)) {
                // Update database using PDO
                if ($update_image) {
                    $sql = "UPDATE news SET title = :title, excerpt = :excerpt, content = :content, 
                            image = :image, author = :author WHERE id = :news_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':image', $image);
                } else {
                    $sql = "UPDATE news SET title = :title, excerpt = :excerpt, content = :content, 
                            author = :author WHERE id = :news_id";
                    $stmt = $conn->prepare($sql);
                }
                
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':excerpt', $excerpt);
                $stmt->bindParam(':content', $content);
                $stmt->bindParam(':author', $author);
                $stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
                
                if ($stmt->execute()) {
                    $success = "Article updated successfully";
                } else {
                    $error = "Error: " . implode(", ", $stmt->errorInfo());
                }
            }
        }
    }
    
    // Delete news article
    if (isset($_POST['delete_news'])) {
        $news_id = (int)$_POST['news_id'];
        
        // Delete associated comments first using PDO
        $delete_comments = "DELETE FROM comments WHERE news_id = :news_id";
        $comments_stmt = $conn->prepare($delete_comments);
        $comments_stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
        $comments_stmt->execute();
        
        // Delete news-tags relationships
        $delete_tags = "DELETE FROM news_tags WHERE news_id = :news_id";
        $tags_stmt = $conn->prepare($delete_tags);
        $tags_stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
        $tags_stmt->execute();
        
        // Delete the news article
        $sql = "DELETE FROM news WHERE id = :news_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $success = "Article deleted successfully";
        } else {
            $error = "Error: " . implode(", ", $stmt->errorInfo());
        }
    }
}

// Handle edit request (GET)
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $news_id = (int)$_GET['edit'];
    $sql = "SELECT * FROM news WHERE id = :news_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':news_id', $news_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() === 1) {
        $news = $stmt->fetch();
        $title = $news['title'];
        $excerpt = $news['excerpt'];
        $content = $news['content'];
        $image = $news['image'];
        $author = $news['author'];
    } else {
        $error = "News article not found";
        $news_id = 0;
    }
}

// Pagination setup for news listing
$results_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Get total number of records using PDO
$total_query = "SELECT COUNT(*) as total FROM news";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch();
$total_pages = ceil($total_row['total'] / $results_per_page);

// Get news data with pagination using PDO
$sql = "SELECT id, title, excerpt, image, author, published_date, created_at FROM news ORDER BY published_date DESC LIMIT :offset, :limit";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt;

// Include the view file
include 'admin_news_view.php';
?>
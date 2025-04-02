<?php
session_start();
include 'C:/xampp/htdocs/book/database_connection.php'; // Database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('User deleted successfully.'); window.location.href='manage_user.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . mysqli_error($conn) . "'); window.location.href='manage_user.php';</script>";
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "<script>alert('Invalid request.'); window.location.href='manage_user.php';</script>";
}
?>
<?php
session_start();
include 'database.php';

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$username = $_GET['id'];

// Delete user
$delete_query = "DELETE FROM users WHERE username = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("s", $username);
$stmt->execute();

header("Location: users.php");
exit();
?>
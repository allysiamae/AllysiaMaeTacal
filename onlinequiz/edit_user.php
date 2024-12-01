<?php
session_start();
include 'database.php';

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$username = $_GET['id'];

// Fetch user data
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: users.php");
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_initial = $_POST['middle_initial'];

    $update_query = "UPDATE users SET first_name = ?, last_name = ?, middle_initial = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssss", $first_name, $last_name, $middle_initial, $username);
    $update_stmt->execute();

    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="title">Edit User</h1>
    <form method="POST">
        <div class="field">
            <label class="label">First Name</label>
            <div class="control">
                <input class="input" type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
        </div>
        <div class="field">
            <label class="label">Last Name</label>
            <div class="control">
                <input class="input" type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
        </div>
        <div class="field">
            <label class="label">Middle Initial</label>
            <div class="control">
                <input class="input" type="text" name="middle_initial" value="<?php echo htmlspecialchars($user['middle_initial']); ?>">
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button is-primary" type="submit">Update User</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
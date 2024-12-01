<?php
session_start();
include 'database.php';

$query = "SELECT username, first_name, last_name, middle_initial FROM users ORDER BY last_name ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100%;
            position: fixed;
            width: 250px;
            background-color: #363636;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
        }
        .sidebar a:hover {
            background-color: #4a4a4a;
            border-radius: 5px;
        }
        .sidebar .is-active {
            background-color: #4a4a4a;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            max-width: calc(100% - 250px);
        }
        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .table td {
            vertical-align: middle; 
        }
        .table-row:nth-child(even) {
            background-color: #f9f9f9; 
        }
        .table-row:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h1 class="title is-4">Online Quiz System</h1>
    <ul>     
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="quizzes.html"><i class="fas fa-question-circle"></i> Quizzes</a></li>
        <li><a href="users.php" class="is-active"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="results.php"><i class="fas fa-chart-line"></i> Results</a></li>
        <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <h1 class="title">User  Management</h1>
    <table class="table is-fullwidth is-bordered is-striped is-hoverable">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="table-row">
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td class="has-text-centered">
                        <a href="edit_user.php?id=<?php echo $row['username']; ?>" class="button is-small is-info"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete_user.php?id=<?php echo $row['username']; ?>" class="button is-small is-danger" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
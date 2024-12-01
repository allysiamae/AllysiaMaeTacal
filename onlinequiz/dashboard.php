<?php
session_start();
include 'database.php';


$total_users_query = "SELECT COUNT(*) as total_users FROM users";
$total_users_result = $conn->query($total_users_query);
if (!$total_users_result) {
    die("Query failed: " . $conn->error);
}
$total_users_row = $total_users_result->fetch_assoc();
$total_users = $total_users_row['total_users'];

$new_quizzes_query = "SELECT COUNT(*) as new_quizzes FROM quizzes WHERE created_at >= CURDATE()";
$new_quizzes_result = $conn->query($new_quizzes_query);
if (!$new_quizzes_result) {
    die("Query failed: " . $conn->error);
}
$new_quizzes_row = $new_quizzes_result->fetch_assoc();
$new_quizzes = $new_quizzes_row['new_quizzes'];

$quizzes_completed_query = "SELECT COUNT(*) as quizzes_completed FROM quiz_attempts WHERE completed = 1"; 
$quizzes_completed_result = $conn->query($quizzes_completed_query);
if (!$quizzes_completed_result) {
    die("Query failed: " . $conn->error);
}
$quizzes_completed_row = $quizzes_completed_result->fetch_assoc();
$quizzes_completed = $quizzes_completed_row['quizzes_completed'];

$query = "SELECT username, first_name, last_name, middle_initial, total_score, quizzes_taken FROM users ORDER BY total_score DESC";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . $conn->error);
}
$recent_query = "SELECT u.username, u.first_name, u.last_name, u.middle_initial, qa.score, qa.attempt_date, qa.category 
                 FROM quiz_attempts qa 
                 JOIN users u ON qa.user_id = u.id 
                 ORDER BY qa.attempt_date DESC 
                 LIMIT 10";
$recent_result = $conn->query($recent_query);
if (!$recent_result) {
    die("Query failed: " . $conn->error);
}
$user_category_counts = [];
while ($recent_row = $recent_result->fetch_assoc()) {
    $username = $recent_row['username'];
    $category = $recent_row['category'];

    if (!isset($user_category_counts[$username])) {
        $user_category_counts[$username] = [
            'Math' => 0,
            'Geography' => 0,
            'Science' => 0,
            'History' => 0,
        ];
    }

    if (array_key_exists($category, $user_category_counts[$username])) {
        $user_category_counts[$username][$category]++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Online Quiz System Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        .panel-heading {
            background-color: #f5f5f5;
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }
        .notification {
            margin-bottom: 20px;
        }
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
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h1 class="title is-4">Online Quiz System</h1>
    <ul>     
        <li><a href="dashboard.php" class="is-active"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="quizzes.html"><i class="fas fa-question-circle"></i> Quizzes</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="results.php"><i class="fas fa-chart-line"></i> Results</a></li>
        <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h1 class="title">Dashboard</h1>

    <div class="columns">
        <div class="column is-3">
            <div class="notification is-primary">
                <p class="title"><?php echo $new_quizzes; ?></p>
                <p class="subtitle">New Quizzes!</p>
            </div>
        </div>
        <div class="column is-3">
            <div class="notification is-success">
                <p class="title"><?php echo $total_users; ?></p>
                <p class="subtitle">Total Users!</p>
            </div>
        </div>
        <div class="column is-3">
            <div class="notification is-warning">
                <p class="title"><?php echo $quizzes_completed; ?></p>
                <p class="subtitle">Quizzes Completed!</p>
            </div>
        </div>
    </div>
    <div class="columns">
        <div class="column is-8">
            <div class="box">
                <h2 class="subtitle">Quiz Results Overview</h2>
                <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Total Score</th>
                        <th>Quizzes Taken</th>
                        <th>Categories Answered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    while ($row = $result->fetch_assoc()): 
                        $categories_answered = [];
                        $username = $row['username'];
                        if (isset($user_category_counts[$username])) {
                            foreach ($user_category_counts[$username] as $category => $count) {
                                if ($count > 0) {
                                    $categories_answered[] = $category . " (" . $count . ")";
                                }
                            }
                        }
                    ?>
                        <tr>
                            <td><?php echo $rank++; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['total_score']; ?></td>
                            <td><?php echo $row['quizzes_taken']; ?></td>
                            <td><?php echo implode(", ", $categories_answered); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
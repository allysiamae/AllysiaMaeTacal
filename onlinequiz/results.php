<?php
session_start();
include 'database.php';

$query = "SELECT username, first_name, last_name, middle_initial, total_score, quizzes_taken FROM users ORDER BY total_score DESC";
$result = $conn->query($query);

$recent_query = "SELECT u.username, u.first_name, u.last_name, u.middle_initial, qa.score, qa.attempt_date 
                 FROM quiz_attempts qa 
                 JOIN users u ON qa.user_id = u.id 
                 ORDER BY qa.attempt_date DESC 
                 LIMIT 10";
$recent_result = $conn->query($recent_query);
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
        .box {
            margin-bottom: 20px;
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
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="results.php" class="is-active"><i class="fas fa-chart-line"></i> Results</a></li>
        <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="main-content">
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
                        <th>Average Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    while ($row = $result->fetch_assoc()): 
                        $average = $row['quizzes_taken'] > 0 ? 
                            round($row['total_score'] / $row['quizzes_taken'], 2) : 0;
                    ?>
                        <tr>
                            <td><?php echo $rank++; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['total_score']; ?></td>
                            <td><?php echo $row['quizzes_taken']; ?></td>
                            <td><?php echo $average; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
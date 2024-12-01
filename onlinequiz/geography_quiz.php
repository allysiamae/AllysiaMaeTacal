<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard");
    exit();
}
$quiz_data = [
['question' => "What is the capital of France?", "option_a" => "Berlin", "option_b" => "Madrid", "option_c" => "Paris", "option_d" => "Rome", "correct_option" => "C"],
['question' => "Which river is the longest in the world", "option_a" => "Amazon", "option_b" => "Nile", "option_c" => "Yangzte", "option_d" => "Mississippi", "correct_option" => "B"],
['question' => "What is the largest desert in the world?", "option_a" => "Antarctica", "option_b" => "Sahara", "option_c" => "Arabian", "option_d" => "Gabi", "correct_option" => "A"],
['question' => "What is the capital city of Japan?", "option_a" => "Seoul", "option_b" => "Tokyo", "option_c" => "Beijing", "option_d" => "Shanghai", "correct_option" => "B"],
['question' => "Which country has the largest population?", "option_a" => "United States", "option_b" => "Philippines", "option_c" => "India", "option_d" => "China", "correct_option" => "C"],
    
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    foreach ($quiz_data as $index => $quiz) {
        if (isset($_POST['question' . $index]) && $_POST['question' . $index] == $quiz['correct_option']) {
            $score++;
        }
    }
    
    // Update user's total score and quizzes taken
    $stmt = $conn->prepare("UPDATE users SET total_score = total_score + ?, quizzes_taken = quizzes_taken + 1 WHERE id = ?");
    $stmt->bind_param("ii", $score, $_SESSION['user_id']);
    $stmt->execute();
    
    // Record the quiz attempt
    $stmt = $conn->prepare("INSERT INTO quiz_attempts (user_id, score) VALUES (?, ?)");
    $stmt->bind_param("ii", $_SESSION['user_id'], $score);
    $stmt->execute();
    
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<body>
<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <a href="quizzes.html" class="button is-light">Back</a>
                </div>
                <div class="level-item">
                    <h1 class="title">Geography Quiz</h1>
                </div>
            </div>
        </div>
        <form method="POST">
            <?php foreach ($quiz_data as $index => $quiz): ?>
                <div class="box">
                    <p class="subtitle"><?php echo ($index + 1) . ". " . $quiz['question']; ?></p>
                    <div class="field">
                        <div class="control">
                            <label class="radio">
                                <input type="radio" name="question<?php echo $index; ?>" value="A">
                                <?php echo $quiz['option_a']; ?>
                            </label>
                        </div>
                        <div class="control">
                            <label class="radio">
                                <input type="radio" name="question<?php echo $index; ?>" value="B">
                                <?php echo $quiz['option_b']; ?>
                            </label>
                        </div>
                        <div class="control">
                            <label class="radio">
                                <input type="radio" name="question<?php echo $index; ?>" value="C">
                                <?php echo $quiz['option_c']; ?>
                            </label>
                        </div>
                        <div class="control">
                            <label class="radio">
                                <input type="radio" name="question<?php echo $index; ?>" value="D">
                                <?php echo $quiz['option_d']; ?>
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="field">
                <div class="control">
                    <button class="button is-primary" type="submit">Submit Quiz</button>
                </div>
            </div>
        </form>
    </div>
</section>
</body>
</html>
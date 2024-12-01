<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard");
    exit();
}

$quiz_data = [
    ['question' => "What is the value of x in the equation 2x + 5 = 11?", "option_a" => "2", "option_b" => "3", "option_c" => "4", "option_d" => "5", "correct_option" => "B"],
    ['question' => "What is 15% of 200?", "option_a" => "25", "option_b" => "30", "option_c" => "35", "option_d" => "40", "correct_option" => "B"],
    ['question' => "What is the square root of 144?", "option_a" => "10", "option_b" => "11", "option_c" => "12", "option_d" => "13", "correct_option" => "C"],
    ['question' => "What is 7 multiplied by 6?", "option_a" => "40", "option_b" => "42", "option_c" => "44", "option_d" => "46", "correct_option" => "B"],
    ['question' => "What is 3x + 10 if x=5?", "option_a" => "40", "option_b" => "45", "option_c" => "20", "option_d" => "25", "correct_option" => "D"],
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <a href="quizzes.html" class="button is-light">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="level-item">
                    <h1 class="title">Math Quiz</h1>
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
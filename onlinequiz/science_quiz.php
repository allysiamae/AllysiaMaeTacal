<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard");
    exit();
}
$quiz_data = [
['question' => "What is the chemical symbol for gold?",  "option_a" => "Au", "option_b" => "Ag", "option_c" => "C", "option_d" => "Go", "correct_option" => "A"],
['question' => "Which planet is known as the Red Planet?", "option_a" => "Mercury", "option_b" => "Mars", "option_c" => "Venus", "option_d" =>"Jupiter", "correct_option" => "B"],
['question' => "What is the main organ of the circulatory system?", "option_a" => "Brain", "option_b" => "Heart", "option_c" => "Lungs", "option_d" => "Liver", "correct_option" => "B"], 
['question' => "It is a branch of science that deals with living organisms and their vital processes.", "option_a" => "Physics", "option_b" => "Ecology", "option_c" => "Chemistry", "option_d" => "Biology", "correct_option" => "D"],
['question' => "What is the hardest natural substance on earth?", "option_a" => "Silver", "option_b" => "Gold", "option_c" => "Emerald", "option_d" => "Diamond", "correct_option" => "D"],
    
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
                    <h1 class="title">Science Quiz</h1>
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
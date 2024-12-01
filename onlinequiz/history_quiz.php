<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard");
    exit();
}
$quiz_data = [
    ['question' => "Who was the first President of the United States?", "option_a" => " George Washington", "option_b" => "Thomas Jefferson", "option_c" => "Abraham Lincoln", "option_d" => "John Adams", "correct_option" => "A"],
    ['question' => "What year did World War II begin?", "option_a" => "1935", "option_b" => "1939", "option_c" => "1941", "option_d" => "1945", "correct_option" => "A"],
    ['question' => "Who was the first woman to fly solo across the Atlantic Ocean?", "option_a" => "Amelia Earhart", "option_b" => "Bessie Coleman", "option_c" => "Harriet Quimby", "option_d" => "Jaqueline Cochran", "correct_option" => "A"],
    ['question' => "What was the main cause of the American Civil War?", "option_a" => "Taxation", "option_b" => "Slavery", "option_c" => "Land disputes", "option_d" => "Trade agreement", "correct_option" => "B"], 
    ['question' => "The Titanic sank in which year?", "option_a" => "1912", "option_b" => "1915", "option_c" => "1910", "option_d" => "1918", "correct_option" => "A"],
    
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
                    <h1 class="title">History Quiz</h1>
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
<?php

session_start();
include_once 'db.php';
function getAllRecords() {
    global $conn;
    $sql = "SELECT * FROM booking ORDER BY id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function searchUniqueUser($student_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM booking WHERE sid = ? order by date desc limit 1");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
if (isset($_POST['search'])) {
    $student_id = sanitize($_POST['search']);
    $user = searchUniqueUser($student_id);
    if ($user) {
        echo "ID: " . $user['id'] . ", Date: " . $user['date'] . ", Slot: " . $user['slot'] . "<br>";
    } else {
        echo "No user found with student ID: $student_id";
    }
}



if (isset($_POST['username']) && isset($_POST['password'])) {
    // Replace these lines with your own user authentication logic
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'abc1234') {
        $_SESSION['username'] = $_POST['username'];
        $records = getAllRecords();
        foreach ($records as $record) {
            echo "ID: " . $record['id'] . ", Date: " . $record['date'] . ", Slot: " . $record['slot'] . "<br>";
        }
    } else {
        echo 'Invalid username or password';
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

?>
<link rel="stylesheet" href="./styles/admin.css">
<?php if (isset($_SESSION['username'])): ?>

<h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
<form class="inside_form" method="POST">
    <label for="search">Search:</label>
    <input type="text" name="search" id="search" required><br>
    <input type="submit" value="Search">
    <button type="submit" name="logout">Logout</button>
</form>
<?php else: ?>
<form method="POST" class="login__form">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <input type="submit" value="Login">
</form>
<?php endif; ?>
<?php
    if (isset($error)) {
        echo "<p>$error</p>";
    }
?>
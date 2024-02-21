<?php
include_once 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Name
    if (empty($_POST["name"])) {
        $_SESSION['error'] = "Name is required";
        header('Location: reserve.php');
        exit();
    } else {
        $name = ucwords(strtolower($_POST["name"])); // Convert to proper letter case
    }

    // Student ID
    if (empty($_POST["student_id"])) {
        $_SESSION['error'] = "Student ID is required";
        header('Location: reserve.php');
        exit();
    } else {
        if (!preg_match("/^[0-9]{8}[A-Z]$/", $_POST["student_id"])) {
            $_SESSION['error'] = "Invalid Student ID format";
            header('Location: reserve.php');
            exit();
        } else {
            $student_id = $_POST["student_id"];
        }
    }

    // Email
    if (empty($_POST["email"])) {
        $_SESSION['error'] = "Email is required";
        header('Location: reserve.php');
        exit();
    } else {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format";
            header('Location: reserve.php');
            exit();
        } else {
            $email = $_POST["email"];
        }
    }

    // Campus
    if (empty($_POST["campus"])) {
        $_SESSION['error'] = "Campus is required";
        header('Location: reserve.php');
        exit();
    } else {
        if ($_POST["campus"] != "HHB" && $_POST["campus"] != "WK") {
            $_SESSION['error'] = "Invalid campus";
            header('Location: reserve.php');
            exit();
        } else {
            $campus = $_POST["campus"];
        }
    }

    // Date
    if (empty($_POST["date"])) {
        $_SESSION['error'] = "Date is required";
        header('Location: reserve.php');
        exit();
    } else {
        $date = $_POST["date"];
        if (strtotime($date) <= time()) {
            $_SESSION['error'] = "The day selected must be later than the current day";
            header('Location: reserve.php');
            exit();
        }
    }
    // Time slot
    if (empty($_POST["time_slot"])) {
        $_SESSION['error'] = "Time slot is required";
        header('Location: reserve.php');
        exit();
    } else {
        $time_slot = $_POST["time_slot"];

        // Check if the time slot is available
        $sql = "SELECT * FROM booking WHERE date = '$date' AND slot = '$time_slot'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Time slot is not available
            $_SESSION['error'] = "The selected time slot is not available";
            header('Location: reserve.php');
            exit();
        }
    }

        
    $insert_query = "INSERT INTO booking (fname, sid, email, campus, date, slot)
            VALUES ('$name', '$student_id', '$email', '$campus', '$date', '$time_slot')";

    if ($conn->query($insert_query) === TRUE) {
    echo "New record created successfully<br>";
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }
    function echoReservationDetails($name, $student_id, $email, $campus, $date, $time_slot) {
        echo "Name: " . $name . "<br>";
        echo "Student ID: " . $student_id . "<br>";
        echo "Email: " . $email . "<br>";
        echo "Campus: " . $campus . "<br>";
        echo "Date: " . $date . "<br>";
        echo "Time slot: " . $time_slot . "<br>";
        echo "successfully reserved";
    }

    // Call the function
    echoReservationDetails($name, $student_id, $email, $campus, $date, $_POST["time_slot"]);
    }
?>
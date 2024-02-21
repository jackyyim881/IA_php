<?php 
session_start();
include_once 'db.php';
if (isset($_SESSION['error'])) {
    echo '<p>' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}
if (isset($_POST['date'])) {
    $date = $_POST['date'];
    $stmt = $conn->prepare("SELECT slot FROM booking WHERE date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $booked_slots = $result->fetch_all(MYSQLI_ASSOC);
}

?>
<link rel="stylesheet" href="./styles/main.css">

<body>
    <h1 class="title">Study Room Booking System</h1>
    <form action="validateForm.php" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="student_id">Student ID:</label><br>
        <input type="text" id="student_id" name="student_id"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <div class="campus__hall">
            <label for="campus" class="campus">Campus:</label><br>
            <input type="radio" id="HHB" name="campus" value="HHB">
            <label for="HHB">HHB</label><br>
            <input type="radio" id="WK" name="campus" value="WK">
            <label for="WK">WK</label><br>
        </div>
        <label for="date">Date:</label><br>
        <input type="date" id="date" name="date"><br>
        <label for="time_slot">Time slot:</label><br>
        <?php 
$booked_slots = $booked_slots ?? array();
        $time_slots = array("1" => "09:00 - 12:00", "2" => "12:00 - 15:00", "3" => "15:00 - 18:00", "4" => "18:00 - 21:00");
    echo '<select id="time_slot" name="time_slot">';   
    foreach ($time_slots as $i => $time) {
            if (in_array($i, array_column($booked_slots, 'slot'))) {
                echo "<option value='$i' disabled>$time</option>";
            } else {
                echo "<option value='$i'>$time</option>";
            }
        }
        ?>
        <input type="reset" value="Reset">
        <input type="submit" value="Submit">
    </form>
</body>

<?php
        // $booked_slots = array();
        // $time_slots = array("1" => "09:00 - 12:00", "2" => "12:00 - 15:00", "3" => "15:00 - 18:00", "4" => "18:00 - 21:00");
        // echo '<select id="time_slot" name="time_slot">';
        // foreach ($time_slots as $i => $time) {
        //     if (in_array($i, $booked_slots)) {
        //         echo "<option value='$i' disabled>$time</option>";
        //     } else {
        //         echo "<option value='$i'>$time</option>";
        //     }
        // }
        // echo '</select>';
        ?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
document.getElementById('date').addEventListener('change', function() {
    var date = this.value;
    fetch('reserve.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'date=' + encodeURIComponent(date),
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log the response data
            document.getElementById('time_slot').innerHTML = data;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});
</script> -->
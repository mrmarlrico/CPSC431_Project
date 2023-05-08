<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Update the appointment status to accepted
    $update_appointment_sql = "UPDATE appointments_messages SET status = 'rejected' WHERE id = '$appointment_id'";
    if ($conn->query($update_appointment_sql) === TRUE) {
        echo "Appointment rejected successfully";
    } else {
        echo "Error updating appointment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<form method="post" action="../office-landing.php">
            <button type="submit" class="btn-back">Go Back</button>
            </div>
        </form>
</html>
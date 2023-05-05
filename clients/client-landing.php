<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.html');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

// Prepare the SQL statement
$sql = "SELECT * FROM providers";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
</head>

<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
        <button class="edit-profile" onclick="location.href='../clients/edit-profile/edit-profile.php'">Edit
            Profile</button>
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert success-message">' . $_SESSION['success_message'] . '</div>';

            // Unset the success message so it doesn't keep showing up on refresh
            unset($_SESSION['success_message']);
        }
        ?>
    </header>
    <div class="sidebar">
        <a href="..//clients/pending/pending.php">Pending</a>
        <a href="../clients/accepted/accepted.php">Accepted</a>
        <a href="../clients/clients/rejected.php">Rejected</a>
    </div>

    <!-- Display the providers in a list -->
    <div class="main-content">
        <div class="filter-search-bar">
            <input type="text" id="searchBar" name="searchBar" placeholder="Search Providers...">
            <select id="filterDropdown" name="filterDropdown">
                <option value="" selected>Filter By</option>
                <option value="location">Location</option>
                <option value="occupation">Occupation</option>
                <option value="availability">Availability</option>
            </select>
        </div>

        <!-- Display the providers in a list -->
        <h2>Providers:</h2>
        <div class="providers-container">
            <?php
        if ($result->num_rows > 0) {
            // Display the providers in a list
            while ($row = $result->fetch_assoc()) {
                echo "<div class='provider'>";
                echo "<ul>";

                echo "<li><i class='fas fa-user'></i> Name: " . $row["first_name"] . " " . $row["last_name"] . "</li>";
                echo "<li><i class='fas fa-briefcase'></i> Occupation: " . $row["occupation"] . "</li>";
                echo "<li><i class='fas fa-map-marker-alt'></i> Zipcode: " . $row["zipcode"] . "</li>";
                echo "<li><i class='fas fa-utensils'></i> Food Preference: " . $row["food_preference"] . "</li>";

                // Fetch availabilities for the current provider
                $provider_id = $row["id"];
                $availabilities_sql = "SELECT * FROM availabilities WHERE provider_id = $provider_id";
                $availabilities_result = $conn->query($availabilities_sql);

                echo "<li><i class='fas fa-clock'></i> Availability:";
                if ($availabilities_result->num_rows > 0) {
                    echo "<ul>";
                    while ($availability = $availabilities_result->fetch_assoc()) {
                        echo "<li>" . $availability["day_of_week"] . ": " . $availability["start_time"] . " - " . $availability["end_time"] . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo " No availability.";
                }
                echo "</li>";

                echo "</ul>";
                echo "<a href='../clients/appointment/schedule.php?provider_id=" . $row["id"] . "'><button class='schedule-btn'><i class='fas fa-calendar-plus'></i> Schedule Appointment</button></a>";
                echo "</div>";
            }
        } else {
            echo "No providers found.";
        }

      
      ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="alert.js"></script>

</body>

</html>
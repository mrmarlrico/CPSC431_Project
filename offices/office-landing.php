<?php
session_start();
// to check array names: print_r($_SESSION);
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.html');
    exit();
    
}
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
</head>
<body>
    <header>
    <!--Keep it as 'name' because when I printed out the $_SESSION array,
    it showed name not office_name bc it's set as name in signup.php-->
    <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
    <button class="add-provider" onclick="location.href='../offices/add-provider/add-provider.php'" style="float:right;margin-top:20px;margin-right:20px;">Add Provider</button>
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert success-message">' . $_SESSION['success_message'] . '</div>';

            // Unset the success message so it doesn't keep showing up on refresh
            unset($_SESSION['success_message']);
        }
        ?>
    </header>
    <!-- Add more content here -->
    <main>
        <h2>List of Providers</h2>
        <div class="providers-container">
            <?php
      if ($result->num_rows > 0) {
          // Output data of each row
          while($row = $result->fetch_assoc()) {
              echo "<div class='provider'>";
              echo "<ul>";
              echo "<li>Name: " . $row["first_name"]. " " . $row["last_name"] . "</li>";
              echo "<li>Occupation: " . $row["occupation"]. "</li>";
              echo "<li>Zipcode: " . $row["zipcode"]. "</li>";
              echo "<li>Food Preference: " . $row["food_preference"]. "</li>";
              echo "</ul>";
              echo "<a href='../clients/appointment/schedule.php?provider_id=" . $row["id"] . "'><button class='schedule-btn'>Schedule Appointment</button></a>";
              echo "</div>";
          }
      } else {
          echo "No providers found.";
      }
      
      ?>
        </div>
    </main>
</body>

</html>
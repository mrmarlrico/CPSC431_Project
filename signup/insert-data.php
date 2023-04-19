<?php
  session_start();

  // Connect to the database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "project";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get the form data
  $name = "";
  $officeName = "";
  $occupation = "";
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm-password'];
  $userType = $_POST['user-type'];

  // Check if the confirm password matches the password
  if ($password !== $confirmPassword) {
    $_SESSION['signup_result'] = 'error';
    $_SESSION['signup_error_message'] = "Error: Passwords do not match";
    header('Location: signup.php');
    exit();
  }

  // Check if the email already exists in the database
  $email_check_sql = "SELECT * FROM providers WHERE email = '$email' UNION SELECT * FROM clients WHERE email = '$email'";
  $email_check_result = $conn->query($email_check_sql);

  if ($email_check_result->num_rows > 0) {
    $_SESSION['signup_result'] = 'error';
    $_SESSION['signup_error_message'] = "Error: An account with this email already exists";
    header('Location: signup.php');
    exit();
  }

  // Insert the data into the appropriate table based on user type
  if ($userType === 'provider') {
    $name = $_POST['Name'];
    $officeName = $_POST['office-name'];
    $sql = "INSERT INTO providers (name, office_name, email, password) VALUES ('$name', '$officeName', '$email', '$password')";
  } else if ($userType === 'client') {
    $name = $_POST['Name'];
    $occupation = $_POST['occupation'];
    if($occupation === "Other") {
      $occupation = $_POST['custom-occupation'];
    }
    $sql = "INSERT INTO clients (name, email, password, occupation) VALUES ('$name', '$email', '$password', '$occupation')";
  } else {
    die("Error: Invalid user type");
  }

  if ($conn->query($sql) === TRUE) {
    $_SESSION['signup_result'] = 'success';
    header('Location: signup.php');
  } else {
    $_SESSION['signup_result'] = 'error';
    $_SESSION['signup_error_message'] = "Error: " . $sql . "<br>" . $conn->error;
    header('Location: signup.php');
  }

  // Close the database connection
  $conn->close();
?>
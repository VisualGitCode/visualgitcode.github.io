<?php 
include('inc_header.php');

// Initialize variables
$username = $nama = $password = $nohp = $email = $error = "";

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

    // Retrieve form data and trim spaces
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama     = trim($_POST['nama']);
    $nohp     = trim($_POST['nohp']);
    $email    = trim($_POST['email']);

    // Validate username (no symbols allowed)
    if (preg_match('/[^a-zA-Z0-9]+/', $username)) {
        $error .= "Username cannot have a symbol. ";
    }

    // Check if all fields are filled
    if (empty($nama) || empty($username) || empty($password) || empty($nohp) || empty($email)) {
        $error .= "Please fill in all fields in the registration form. ";
    }

    // Validate username length (between 4 and 15 characters)
    $id_length = strlen($username);
    if ($id_length > 15) {
        $error .= "Username is too long. Maximum 15 characters. ";
    }
    if ($id_length < 4) {
        $error .= "Username is too short. Minimum 4 characters. ";
    }

    // Validate password length (at least 6 characters, max 12)
    $password_length = strlen($password);
    if ($password_length < 6) {
        $error .= "Password is too short. Minimum 6 characters. ";
    }
    if ($password_length > 12) {
        $error .= "Password is too long. Maximum 12 characters. ";
    }

    // Phone number validation
    $nohp_length = strlen($nohp);
    if (!preg_match('/^[0-9]+$/', $nohp)) {
        $error .= "Phone number must contain only digits. ";
    }
    if ($nohp_length < 10) {
        $error .= "Phone number is too short. Minimum 10 digits. ";
    }
    if ($nohp_length > 15) {
        $error .= "Phone number is too long. Maximum 15 digits. ";
    }

    // Email validation
    if (empty($email)) {
        $error .= "Email is required. ";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "Invalid email format. ";
    }

    // If no errors, proceed with registration
    if (empty($error)) {
        // Escape input for SQL safety
        $username_sql = mysqli_real_escape_string($db, $username);
        $password_sql = password_hash($password, PASSWORD_DEFAULT); // hashed password
        $nama_sql     = mysqli_real_escape_string($db, $nama);
        $nohp_sql     = mysqli_real_escape_string($db, $nohp);
        $email_sql    = mysqli_real_escape_string($db, $email);

        // Check if username already exists
        $check_sql = "SELECT * FROM pengguna WHERE username='$username_sql' LIMIT 1";
        $check_result = mysqli_query($db, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $error .= "Username ($username) exists, please choose another username.";
        } else {
            // Insert new user
            $insert_sql = "INSERT INTO pengguna (username, password, nama, nohp, email, level) 
                           VALUES ('$username_sql', '$password_sql', '$nama_sql', '$nohp_sql', '$email_sql', 'user')";
            if (mysqli_query($db, $insert_sql)) {
                exit("<script>alert('Registration successful. Please log in using Username ($username).');
                       window.location.replace('login.php'); </script>");
            } else {
                $error .= "Database error: " . mysqli_error($db);
            }
        }
    }

    // If there are errors, show them
    if (!empty($error)) {
        echo "<script>alert('$error');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register Account</title>
  <style>
  .signup-form {
      max-width: 400px;
      margin: 40px auto;
      padding: 24px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background: #fafafa;
      box-shadow: 0 2px 16px rgba(0,0,0,0.7);
  }
  .signup-form h2 {
      text-align: center;
      margin-bottom: 24px;
  }
  .signup-form label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
  }
  .signup-form input[type="text"],
  .signup-form input[type="password"] {
      width: 100%;
      padding: 8px 10px;
      margin-bottom: 16px;
      border: 1px solid #aaa;
      border-radius: 4px;
      font-size: 1em;
      box-sizing: border-box;
      background-color: #F5DEB3 !important;
  }
  .signup-form button[type="submit"] {
      width: 100%;
      padding: 10px;
      background: #28a745;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 1.1em;
      cursor: pointer;
      transition: background 0.2s;
  }
  .signup-form button[type="submit"]:hover {
      background: #218838;
  }
  </style>
</head>
<body>
  <form method="POST" action="signup.php" class="signup-form">

    <h2>Register Account</h2>

    <label>Username</label>
    <input type="text" placeholder="Choose a unique username (4-15 characters)" name="username"
           title="Username for system login."
           value='<?php echo htmlspecialchars($username); ?>' required>

    <label>Password</label>
    <input type="password" placeholder="Create a strong password (6-12 characters)" name="password" value='' required>

    <label>Name</label>
    <input type="text" placeholder="Your short name (e.g. John Doe)" name="nama" value='<?php echo htmlspecialchars($nama); ?>' required>

    <label>Phone Number</label>
    <input type="text" placeholder="e.g. 60123456789 (without +)" name="nohp" value='<?php echo htmlspecialchars($nohp); ?>' required>

    <label>Email</label>
    <input type="text" placeholder="you@example.com" name="email" value='<?php echo htmlspecialchars($email); ?>' required>

    <button type="submit">Sign Up</button>

  </form>
</body>
</html>
<?php include('inc_footer.php'); ?>

<?php include('inc_header.php');

// --- LOGIN LOGIC ---
$username = "";
$password = "";

if( isset($_POST['username']) && isset($_POST['password']) ){
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $sql = "SELECT * FROM pengguna WHERE username='$username' AND password='$password' LIMIT 1";
  $result = query($db, $sql);

  if( mysqli_num_rows($result) > 0 ){
    $row = mysqli_fetch_array($result);
    $_SESSION['idpengguna'] = $row['idpengguna'];
    $_SESSION['nama'] = $row['nama'];
    $_SESSION['level'] = $row['level'];
    echo "<script>
            alert('Login successful');
            window.location.replace('index.php');
          </script>";
    exit;
  } else {
    echo "<script>alert('Invalid username or password');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    .login-form {
      max-width: 400px;
      margin: 60px auto;
      padding: 28px 24px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.7);
    }
    .login-form h2 {
      text-align: center;
      margin-bottom: 28px;
    }
    .login-form label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }
    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 9px 10px;
      margin-bottom: 18px;
      border: 1px solid #aaa;
      border-radius: 4px;
      font-size: 1em;
      box-sizing: border-box;
    }
    .login-form button[type="submit"] {
      width: 100%;
      padding: 11px;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 1.1em;
      cursor: pointer;
      transition: background 0.2s;
    }
    .login-form button[type="submit"]:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <form method="POST" action="login.php" class="login-form">
    <h2>Login</h2>
    <label>Username</label>
    <input type="text" placeholder="Enter your username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
    <label>Password</label>
    <input type="password" placeholder="Enter your password" name="password" required>
    <button type="submit">Login</button>
  </form>
</body>
</html>
<?php include('inc_footer.php'); ?>

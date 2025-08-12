<?php include('inc_header.php'); ?>

<?php
$username = $password = $nama = $nohp = $email = $level = "";
$edit_data = 0;

// --- DELETE USER ---
if (isset($_GET['delete'])) {
    $idpengguna = $_GET['delete'];
    $sql = "DELETE FROM pengguna WHERE idpengguna = '$idpengguna' ";
    $result = query($db, $sql);
    echo "<script>
            alert('User account successfully deleted.');
            window.location.replace('admin_pengguna_senarai.php');
          </script>";
    exit();
}

// --- EDIT USER ---
if (isset($_GET['idpengguna'])) {
    $idpengguna = (int)$_GET['idpengguna'];
    $sql = "SELECT * FROM pengguna WHERE idpengguna = $idpengguna LIMIT 1";
    $result = query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $edit_data = mysqli_fetch_array($result);
        $username = $edit_data['username'];
        $password = $edit_data['password'];
        $nama = $edit_data['nama'];
        $nohp = $edit_data['nohp'];
        $email = $edit_data['email'];
        $level = $edit_data['level'];
    } else {
        echo "<script>alert('ID not found.');</script>";
    }
}

// --- SUBMIT FORM ---
if (isset($_POST['username'])) { 
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama = $_POST['nama'];
    $nohp = $_POST['nohp'];
    $email = $_POST['email'];
    $level = $_POST['level'];

    if ($edit_data) {
        $sql = "UPDATE IGNORE pengguna SET username='$username', password='$password', nama='$nama', nohp='$nohp', email='$email', level='$level' WHERE idpengguna=$idpengguna";
    } else {
        $sql = "INSERT IGNORE INTO pengguna (username, password, nama, nohp, email, level) 
                VALUES ('$username', '$password', '$nama', '$nohp', '$email', '$level')";
    }

    $result = query($db, $sql);
    echo "<script>
            alert('Saved successfully.');
            window.location.replace('admin_pengguna_senarai.php');
          </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Form</title>
    <style>
        .container-main {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 28px 22px 22px 22px;
        }
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        h2 {
            margin: 0;
        }
        .delete-btn {
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 14px;
            font-size: 0.97em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .delete-btn:hover {
            background: #b21f2d;
        }
        form {
            margin-top: 10px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #aaa;
            border-radius: 4px;
            font-size: 1em;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background: #28a745;
            color: #fff;
            padding: 10px 22px;
            border: none;
            border-radius: 4px;
            font-size: 1.07em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        @media (max-width: 600px) {
            .container-main { padding: 8px; }
        }
    </style>
    <script>
    function deletethis(id) {
        if (confirm('Are you sure you want to delete this account?')) {
            window.location.href = '?delete=' + id;
        }
    }
    </script>
</head>
<body>
<div class="container-main">
    <div class="header-row">
        <h2>User Form</h2>
        <?php 
        if ($edit_data && $level != 'admin') { 
            echo "<button class='delete-btn' onclick='deletethis($idpengguna)'>Delete User Account</button>";
        }
        ?>
    </div>
    <form method="POST" action="">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

        <label>Password</label>
        <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>

        <label>Name</label>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>

        <label>Phone No</label>
        <input type="text" name="nohp" value="<?php echo htmlspecialchars($nohp); ?>" required>

        <label>Email</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label>Level</label>
        <select name="level">
            <option <?php echo $level == 'user' ? 'selected' : ''; ?> value="user">User</option>
            <option <?php echo $level == 'admin' ? 'selected' : ''; ?> value="admin">Admin</option>
        </select>

        <input type="submit" value="Save">
    </form>
</div>
</body>
</html>
<?php include('inc_footer.php'); ?>

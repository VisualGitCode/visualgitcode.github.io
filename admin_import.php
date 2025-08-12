<?php include('inc_header.php'); ?>

<?php
semaklevel('admin');

// --- HANDLE FILE UPLOAD ---
if (isset($_FILES["import"])) {
    if (!file_exists($_FILES['import']['tmp_name'])) {
        echo "<script>
                alert('Please select a file.');
                window.location.replace('urus_import.php');
              </script>";
        exit();
    }
    $success = $failed = 0;
    $file = fopen($_FILES["import"]["tmp_name"], 'rb');
    while (($line = fgetcsv($file, 50, ",")) !== FALSE) {
        if (count($line) >= 3) {
            $username = trim($line[0]);
            $password = trim($line[1]);
            $nama  = trim($line[2]);
            $nohp = isset($line[3]) ? trim($line[3]) : "";
            $email = isset($line[4]) ? trim($line[4]) : "";
            $sql = "INSERT IGNORE INTO pengguna (username, password, nama, nohp, email, level) 
                    VALUES ('$username', '$password', '$nama', '$nohp', '$email', 'user')";
            $result = query($db, $sql);
            if (mysqli_insert_id($db)) {
                $success += 1;
            } else {
                $failed += 1;
            }
        }
    }
    fclose($file);
    $total = $success + $failed;
    echo "<script>
            alert('$total rows processed. $success new records. $failed records ignored.');
            window.location.replace('admin_import.php');
          </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import User Data</title>
    <style>
        .container-main {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.7);
            padding: 22px 0px 22px 22px;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 18px;
        }
        form {
            margin-top: 18px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }
        input[type="file"] {
            padding: 8px 20px;
            margin-bottom: 16px;
            font-size: 1em;
        }
        button[type="submit"] {
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
        button[type="submit"]:hover {
            background: #218838;
        }
        @media (max-width: 600px) {
            .container-main { padding: 8px; }
        }
    </style>
</head>
<body>
<div class="container-main">
    <h1>Import User Data</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="import">Select file to import (TXT or CSV format only)</label>
        <input type="file" name="import" accept='.csv, .txt' required>
        <button type="submit" value="submit">Import Data</button>
    </form>
</div>
</body>
</html>
<?php include('inc_footer.php'); ?>

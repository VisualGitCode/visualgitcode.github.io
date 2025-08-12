<?php include('inc_header.php'); ?>

<?php
$edit_data = 0;
$namakumpulan = "";

// --- DELETE CATEGORY ---
if(isset($_GET['delete'])){
    $idkumpulan = $_GET['delete'];
    $sql = "DELETE FROM kumpulan WHERE idkumpulan = $idkumpulan";
    $result = query($db, $sql);
    exit("<script>alert('Category successfully deleted.');
           window.location.replace('admin_kategori_senarai.php');</script>");
}

// --- EDIT CATEGORY ---
if(isset($_GET['idkumpulan'])){
    $idkumpulan = (int)$_GET['idkumpulan'];
    $sql = "SELECT * FROM kumpulan WHERE idkumpulan = $idkumpulan LIMIT 1";
    $result = query($db, $sql);
    if(mysqli_num_rows($result) > 0){
        $edit_data = mysqli_fetch_array($result);
        $namakumpulan = $edit_data['namakumpulan'];
    } else {
        echo "<script>alert('ID not found.');</script>";
    }
}

// --- SAVE CATEGORY ---
if(isset($_POST['namakumpulan']) && !empty($_POST['namakumpulan'])){
    $namakumpulan = mysqli_real_escape_string($db, $_POST['namakumpulan']);
    if($edit_data){
        $sql = "UPDATE IGNORE kumpulan SET namakumpulan='$namakumpulan' WHERE idkumpulan=$idkumpulan";
    } else {
        $sql = "INSERT IGNORE INTO kumpulan (namakumpulan) VALUES ('$namakumpulan')";
    }
    $result = query($db, $sql);
    echo "<script>alert('Saved successfully.');
           window.location.replace('admin_kategori_senarai.php');</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category Form</title>
    <style>
        .container-main {
            max-width: 400px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.7);
            padding: 28px 22px 22px 22px;
        }
        .header-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
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
            text-decoration: none;
        }
        .delete-btn:hover {
            background: #b21f2d;
        }
        h2 {
            margin-bottom: 18px;
            text-align: center;
        }
        form {
            margin-top: 10px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #aaa;
            border-radius: 4px;
            font-size: 1em;
            box-sizing: border-box;
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
            width: 100%;
        }
        button[type="submit"]:hover {
            background: #218838;
        }
        @media (max-width: 500px) {
            .container-main { padding: 8px; }
        }
    </style>
    <script>
    function deletethis(id) {
        if (confirm('Are you sure you want to delete this category?')) {
            window.location.href = '?delete=' + id;
        }
    }
    </script>
</head>
<body>
<div class="container-main">
    <div class="header-row">
        <?php if($edit_data): ?>
            <a class="delete-btn" href="javascript:void(0);" onclick="deletethis(<?php echo $idkumpulan; ?>)">Delete Category</a>
        <?php endif; ?>
    </div>
    <h2>Category Form</h2>
    <form method="POST" action="">
        <label>Category Name</label>
        <input type="text" placeholder="Name down the new Category" name="namakumpulan" value="<?php echo htmlspecialchars($namakumpulan); ?>" required>
        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>
<?php include('inc_footer.php'); ?>

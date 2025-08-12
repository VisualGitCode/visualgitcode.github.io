<?php include('inc_header.php'); ?>

<?php
// --- SEARCH & SORT LOGIC ---
$keyword = $q = $sort = "";
$order_by = ' p.nama ASC ';
if(isset($_POST['search'])){
    $keyword = $_POST['keyword'];
    if(!empty($keyword)){
        $q .= " WHERE p.username LIKE '%$keyword%' ";
    }
    if(isset($_POST['sort'])){
        $sort = $_POST['sort'];
        if($sort == 'asc'){
            $order_by = 'ordercount ASC';
        } elseif($sort == 'desc'){
            $order_by = 'ordercount DESC';
        }
    }
}
if(isset($_POST['reset'])){
    $keyword = '';
    $q = '';
    $sort = '';
    $order_by = ' p.nama ASC ';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <style>
        .container-main {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.7);
            padding: 24px;
        }
        h2 {
            margin-bottom: 18px;
        }
        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-bottom: 18px;
        }
        .search-form input[type="text"] {
            padding: 8px;
            border: 1px solid #aaa;
            border-radius: 4px;
            font-size: 1em;
            min-width: 180px;
        }
        .search-form input[type="submit"], .search-form a.add-btn {
            background: #007bff;
            /* color: #708090; */
            border: solid;
            border-radius: 4px;
            padding: 8px 16px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            margin-right: 4px;
            text-decoration: underline;
            transition: background 0.2s;
        }
        .search-form input[type="submit"]:hover, .search-form a.add-btn:hover {
            background: #0056b3;
        }
        .search-form label {
            margin-right: 6px;
            font-weight: bold;
        }
        .search-form .radio-group {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-left: 12px;
        }
        .search-form input[type="radio"] {
            margin-left: 8px;
            accent-color: #007bff;
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
        }
        .user-table th, .user-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }
        .user-table th {
            background: #f0f0f0;
            font-weight: bold;
        }
        .user-table tr:nth-child(even) {
            background: #fafafa;
        }
        .user-table td.center {
            text-align: center;
        }
        .user-table td.right {
            text-align: right;
        }
        .action-btn {
            background: #17a2b8;
            color: #fff;
            padding: 6px 14px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.97em;
            font-weight: bold;
            margin-right: 4px;
            transition: background 0.2s;
            display: inline-block;
        }
        .action-btn:hover {
            background: #117a8b;
        }
        .add-btn {
            background: #28a745;
            color: #fff;
            margin-left: 12px;
            padding: 8px 18px;
        }
        .add-btn:hover {
            background: #218838;
        }
        @media (max-width: 700px) {
            .container-main { padding: 8px; }
            .search-form { flex-direction: column; gap: 8px; align-items: flex-start; }
            .user-table th, .user-table td { padding: 7px 4px; font-size: 0.97em; }
        }
    </style>
</head>
<body>
<div class="container-main">
    <h2>Manage Users</h2>
    <form method="POST" action="" class="search-form">
        <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Username">
        <input type="submit" name="search" value="Search">
        <input type="submit" name="reset" value="Reset">
        <span class="radio-group">
            <label>Order Count:</label>
            <input type="radio" id="asc" name="sort" value="asc" <?php if($sort == 'asc') echo 'checked'; ?>>
            <label for="asc">Ascending</label>
            <input type="radio" id="desc" name="sort" value="desc" <?php if($sort == 'desc') echo 'checked'; ?>>
            <label for="desc">Descending</label>
        </span>
        <a class="add-btn" href="admin_pengguna_borang.php">Add New User</a>
    </form>
    <hr>
    <?php
    $sql = "SELECT p.*, COUNT(pe.idpengguna) as ordercount 
            FROM pengguna p 
            LEFT JOIN pesanan pe ON p.idpengguna = pe.idpengguna 
            $q
            GROUP BY idpengguna 
            ORDER BY $order_by";
    $result = query($db, $sql);
    $total = mysqli_num_rows($result);

    if ($total > 0) {
        echo "<div>Total: $total</div>";
        echo "<table class='user-table'>
                <tr>
                    <th>No.</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Phone No</th>
                    <th>Email</th>
                    <th>Order Count</th>
                    <th class='right'>Action</th>
                </tr>";
        $counter = 0;
        while ($row = mysqli_fetch_array($result)) {
            $counter += 1;
            $idpengguna = $row['idpengguna'];
            $username = $row['username'];
            $nama = $row['nama'];
            $nohp = $row['nohp'];
            $email = $row['email'];
            $ordercount = $row['ordercount'];
            echo "<tr>
                    <td>$counter</td>
                    <td>$username</td>
                    <td>$nama</td>
                    <td>$nohp</td>
                    <td>$email</td>
                    <td class='center'>$ordercount</td>
                    <td class='right'>
                        <a class='action-btn' href='akaun.php?idpengguna=$idpengguna'>Report</a>
                        <a class='action-btn' href='admin_pengguna_borang.php?idpengguna=$idpengguna'>Edit</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<div>No user records found.</div>";
    }
    ?>
</div>
</body>
</html>
<?php include('inc_footer.php'); ?>

<?php include('inc_header.php'); ?>

<?php
// --- GET USER ID ---
if ($level == 'user') {
    $idpengguna = $_SESSION['idpengguna'];
} elseif (isset($_GET['idpengguna'])) {
    $idpengguna = $_GET['idpengguna'];
} else {
    echo "<script>alert('Parameter incomplete.'); window.location.replace('admin_pengguna_senarai.php');</script>";
    exit;
}

// --- FETCH USER DETAILS ---
$sql = "SELECT * FROM pengguna WHERE idpengguna = $idpengguna LIMIT 1";
$result = query($db, $sql);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $nama = $row['nama'];
    $username = $row['username'];
    $nohp = $row['nohp'];
    $email = $row['email'];
} else {
    exit("User information not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Users</title>
    <style>
        .container-main {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.7);
            padding: 24px;
        }
        h2 {
            margin-bottom: 10px;
        }
        .user-info {
            margin-bottom: 24px;
            color: #444;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.4);
        }
        .order-table th, .order-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }
        .order-table th {
            background: #f0f0f0;
            font-weight: bold;
        }
        .order-table tr:nth-child(even) {
            background: #fafafa;
        }
        .order-table .center {
            text-align: center;
        }
        .view-btn {
            background: #17a2b8;
            color: #fff;
            padding: 6px 14px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.97em;
            font-weight: bold;
            transition: background 0.2s;
            display: inline-block;
        }
        .view-btn:hover {
            background: #117a8b;
        }
        @media (max-width: 700px) {
            .container-main { padding: 8px; }
            .order-table th, .order-table td { padding: 7px 4px; font-size: 0.97em; }
        }
    </style>
</head>
<body>
<div class="container-main">
    <h2>Account: <?= htmlspecialchars($nama) ?></h2>
    <div class="user-info">
        <?= htmlspecialchars($username) ?>, <?= htmlspecialchars($nohp) ?>, <?= htmlspecialchars($email) ?>
    </div>
    <?php
    // --- FETCH ORDER HISTORY ---
    $sql = "SELECT pe.*, SUM(pi.quantity) AS quantity  
            FROM pesanan AS pe
            JOIN pesanan_item AS pi ON pe.idpesanan = pi.idpesanan
            WHERE pe.idpengguna = $idpengguna
            GROUP BY pe.idpesanan
            ORDER BY masa DESC";
    $result = query($db, $sql);
    $total = mysqli_num_rows($result);

    if ($total > 0) {
        echo "<div>Order List: $total</div>";
        echo "<table class='order-table'>
                <tr>
                    <th width='32'>No.</th>
                    <th>Date</th>
                    <th class='center'>Status</th>
                    <th>Order Item</th>
                </tr>";
        $counter = 1;
        while ($row = mysqli_fetch_array($result)) {
            $idpesanan = $row['idpesanan'];
            $quantity = $row['quantity'];
            $masa = date('j M Y, g:i A', strtotime($row['masa']));
            $status_pesanan = semakstatus($row['status_pesanan']);
            echo "<tr>
                    <td>$counter</td>
                    <td>$masa : $quantity item(s)</td>
                    <td class='center'>$status_pesanan</td>
                    <td><a class='view-btn' href='order.php?idpesanan=$idpesanan'>View</a></td>
                  </tr>";
            $counter++;
        }
        echo "</table>";
    } else {
        echo "<div>No record orders yet.</div>";
    }
    ?>
</div>
</body>
</html>
<?php include ('inc_footer.php'); ?>

<?php include('inc_header.php'); ?>
<?php semaklevel('admin'); ?>

<?php
// Determine active filter for highlighting
if (isset($_GET['all'])) {
    $active_filter = 'all';
    $filter_status = '';
} elseif (isset($_GET['paid'])) {
    $active_filter = 'paid';
    $filter_status = " WHERE pe.status_pesanan = 'paid' "; 
} elseif (isset($_GET['pending'])) {
    $active_filter = 'pending';
    $filter_status = " WHERE pe.status_pesanan = 'pending' ";
} elseif (isset($_GET['cancel'])) {
    $active_filter = 'cancel';
    $filter_status = " WHERE pe.status_pesanan = 'cancel' ";
} elseif (isset($_GET['ready'])) {
    $active_filter = 'ready';
    $filter_status = " WHERE pe.status_pesanan = 'ready' ";
} else {
    $active_filter = 'all';
    $filter_status = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <style>
        .container-main {
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
            padding: 24px;
        }
        .header-row {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 18px;
        }
        h2 { margin: 0; }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
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
        .order-table tr:nth-child(even) { background: #fafafa; }
        .order-table td.center { text-align: left; }
        .action-btn {
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
        .action-btn:hover { background: #117a8b; }
        .row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .col.left { flex: 1; }
        .col.right { text-align: center; }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
            margin-left: 6px;
            transition: background-color 0.2s ease;
        }
        .btn:hover { background-color: #0056b3; }
        .btn.active { background-color: #0056b3; }
        @media (max-width: 700px) {
            .container-main { padding: 8px; }
            .order-table th, .order-table td { padding: 7px 4px; font-size: 0.97em; }
        }
    </style>
</head>
<body>
<div class="container-main">
    <div class="header-row">
        <h2>Manage Orders</h2>
    </div>
    <div class="col right">
        <a class="btn <?=($active_filter == 'all' ? 'active' : '')?>" href="?all">All</a>
        <a class="btn <?=($active_filter == 'ready' ? 'active' : '')?>" href="?ready">Done</a>
        <a class="btn <?=($active_filter == 'pending' ? 'active' : '')?>" href="?pending">Pending</a>
        <a class="btn <?=($active_filter == 'paid' ? 'active' : '')?>" href="?paid">Paid</a>
        <a class="btn <?=($active_filter == 'cancel' ? 'active' : '')?>" href="?cancel">Cancelled</a>
    </div>

    <?php
    $sql = "SELECT pe.*, SUM(pi.quantity) AS quantity, 
            GROUP_CONCAT(i.namaitem ORDER BY i.iditem SEPARATOR ', ') AS items
            FROM pesanan AS pe
            JOIN pesanan_item AS pi ON pe.idpesanan = pi.idpesanan
            JOIN item AS i ON pi.iditem = i.iditem
            $filter_status
            GROUP BY pe.idpesanan ORDER BY idpesanan DESC;";

    $result = query($db, $sql);
    $total = mysqli_num_rows($result);

    if ($total > 0) {
        echo "<div>Order List: $total</div>";
        echo "<table class='order-table'>
                <tr>
                  <th width='32'>No.</th>
                  <th>Order</th>
                  <th>Status</th>
                  <th>Check</th>
                </tr>";
        $counter = 1;
        while ($row = mysqli_fetch_array($result)) {
            $idpesanan = $row['idpesanan'];
            $item = $row['items'];
            $quantity = $row['quantity'];
            $masa = date('j M Y, g:i A', strtotime($row['masa']));
            $status_pesanan = semakstatus($row['status_pesanan']);
            echo "<tr>
                    <td>$counter</td>
                    <td>$masa : $quantity - $item</td>
                    <td class='center'>$status_pesanan</td>
                    <td><a class='action-btn' href='order.php?idpesanan=$idpesanan'>View</a></td>
                  </tr>";
            $counter++;
        }
        echo "</table>";
    } else {
        echo "<div>No order records found.</div>";
    }
    ?>
    <?php include('inc_footer.php'); ?>
</div>
</body>
</html>

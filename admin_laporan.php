<?php include('inc_header.php'); ?>

<?php
semaklevel('admin');

// --- DATE RANGE LOGIC ---
if (isset($_POST['date_from']) && isset($_POST['date_to'])) {
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
} else {
    $date_from = date("Y-m-d", strtotime('-1 week'));
    $date_to = date("Y-m-d", strtotime('now'));
}
$range = " BETWEEN '$date_from' AND '$date_to'";

// --- SALES DATA ---
$sql = "SELECT COUNT(pi.idpesanan) AS item_count, SUM(pi.price) AS total_sales,
(SELECT COUNT(DISTINCT idpesanan) FROM pesanan WHERE masa $range) AS order_count
FROM pesanan p
JOIN pesanan_item pi ON p.idpesanan = pi.idpesanan
WHERE masa $range";
$result = query($db, $sql);
$row = mysqli_fetch_array($result);
$order_count = $row['order_count'];
$item_count = $row['item_count'];
$total_sales = $row['total_sales'];

// --- BEST-SELLING ITEMS ---
$sql = "SELECT pi.iditem, i.namaitem, COUNT(pi.iditem) AS sold_count
FROM pesanan AS p
JOIN pesanan_item AS pi ON p.idpesanan = pi.idpesanan
JOIN item AS i ON i.iditem = pi.iditem
WHERE masa $range
GROUP BY pi.iditem
ORDER BY sold_count DESC";
$best_sellers = query($db, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        .container-main {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.7);
            padding: 28px 22px 22px 22px;
        }
        h2, h3 {
            margin-bottom: 18px;
        }
        .date-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin-bottom: 18px;
        }
        .date-form label {
            font-weight: bold;
            margin-right: 4px;
        }
        .date-form input[type="date"] {
            padding: 7px;
            border: 1px solid #aaa;
            border-radius: 4px;
            font-size: 1em;
        }
        .date-form button {
            background: #6c757d;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .date-form button:hover {
            background: #495057;
        }
        .stats-row {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }
        .stat-card {
            flex: 1 1 220px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.4);
            text-align: center;
            padding: 18px 8px;
            margin-bottom: 12px;
        }
        .stat-title {
            font-size: 1em;
            color: #555;
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: #222;
        }
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.4);
        }
        table.report-table th, table.report-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }
        table.report-table th {
            background: #f0f0f0;
            font-weight: bold;
        }
        table.report-table tr:nth-child(even) {
            background: #fafafa;
        }
        .print-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 18px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            margin-top: 16px;
            transition: background 0.2s;
        }
        .print-btn:hover {
            background: #0056b3;
        }
        @media (max-width: 700px) {
            .container-main { padding: 8px; }
            .stats-row { flex-direction: column; gap: 10px; }
        }
    </style>
    <script>
    function printcontent(elid) {
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById(elid).innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
    }
    </script>
</head>
<body>
<div id="kandungan" class="container-main">
    <h2>Sales Report</h2>
    <form method="POST" action="" class="date-form">
        <label>From</label>
        <input type="date" name="date_from" value="<?php echo $date_from; ?>" required>
        <label>To</label>
        <input type="date" name="date_to" value="<?php echo $date_to; ?>" required>
        <button type="submit">Search</button>
    </form>
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-title">Total Orders</div>
            <div class="stat-value"><?php echo $order_count; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Items Sold</div>
            <div class="stat-value"><?php echo $item_count; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Sales Value</div>
            <div class="stat-value">RM <?php echo number_format($total_sales, 2); ?></div>
        </div>
    </div>
    <hr>
    <h3>Best-Selling Items</h3>
    <table class="report-table">
        <tr>
            <th>Item Name</th>
            <th>Sold Quantity</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_array($best_sellers)) {
            echo "<tr><td>" . htmlspecialchars($row['namaitem']) . "</td><td>" . $row['sold_count'] . "</td></tr>";
        }
        ?>
    </table>
    <button class="print-btn" onclick="printcontent('kandungan')">Print</button>
</div>
</body>
</html>
<?php include('inc_footer.php'); ?>

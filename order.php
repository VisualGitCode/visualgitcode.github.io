<?php include('inc_header.php'); ?>

<?php
// --- GET ORDER ID ---
if (isset($_GET['idpesanan'])) {
    $idpesanan = $_GET['idpesanan'];
} else {
    exit("<script>alert('ID required.'); window.location.replace('index.php');</script>");
}

// --- FETCH ORDER DETAILS ---
$sql = "SELECT * FROM pesanan
        LEFT JOIN pengguna ON pengguna.idpengguna = pesanan.idpengguna
        WHERE idpesanan = $idpesanan LIMIT 1";
$result = query($db, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $idpengguna = $row['idpengguna'];
    $nama = $row['nama'];
    $nohp = $row['nohp'];
    $note = $row['note'];
    $notable = $row['notable'];
    $masa = date("j M Y, g:i A", strtotime($row['masa']));
    $status_pesanan = semakstatus($row['status_pesanan']);
    // $nama_sistem and $cukai are already set in inc_header.php
} else {
    exit("<script>alert('Order ID does not exist.'); window.location.replace('index.php');</script>");
}

// --- USER LEVEL CHECK ---
if ($level == 'user' && $idpengguna != $_SESSION['idpengguna']) {
    exit("<script>alert('Order does not exist for your account.'); window.location.replace('akaun.php');</script>");
}

// --- ADMIN: HANDLE STATUS CHANGE ---
if ($level == 'admin') {
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        if (in_array($status, ['ready', 'pending', 'paid', 'cancel'])) {
            $sql = "UPDATE pesanan SET status_pesanan = '$status' WHERE idpesanan = $idpesanan";
            query($db, $sql);
        }
        echo "<script>window.location.replace('order.php?idpesanan=$idpesanan');</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <style>
        .container-main {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.7);
            padding: 24px;
        }
        .actions {
            text-align: center;
            margin-bottom: 24px;
        }
        .actions a {
            display: inline-block;
            margin: 4px 6px;
            padding: 8px 18px;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 1em;
            transition: background 0.2s;
        }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-primary { background: #007bff; }
        .btn-primary:hover { background: #0056b3; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-warning:hover { background: #e0a800; color: #222; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #b21f2d; }
        .card {
            background: #fafafa;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 16px;
            padding: 18px 24px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.3);
        }
        .card-header {
            text-align: center;
            margin-bottom: 16px;
        }
        .card-header h4 {
            margin: 0;
            font-size: 1.3em;
            font-weight: bold;
        }
        .order-info ul {
            list-style: none;
            padding: 0;
            margin: 0 0 14px 0;
        }
        .order-info li {
            margin-bottom: 6px;
            font-size: 1em;
        }
        .order-items {
            margin-bottom: 10px;
        }
        .order-item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .order-item-row p {
            margin: 0;
        }
        .summary {
            text-align: right;
            margin-top: 10px;
            font-size: 1em;
        }
        .summary strong {
            font-size: 1.1em;
        }
        .print-btn {
            display: inline-block;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 18px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 8px;
        }
        .print-btn:hover {
            background: #0056b3;
        }
        .thankyou {
            text-align: center;
            margin-top: 18px;
        }
        .btn-active {
            outline: 3px solid #333; /* visible border */
            box-shadow: 0 0 8px rgba(0,0,0,0.4);
        }
        .backagain {
    display: inline-block;
    float: right; /* Move it to the right */
    margin-top: 10px;
    padding: 8px 14px;
    background: #6c757d;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}
.backagain:hover {
    background: #5a6268;
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
<?php if ($level == 'admin'): ?>
<div class="actions">
    <h4>Actions:</h4>
    <a class="btn-success <?= ($row['status_pesanan'] == 'ready') ? 'btn-active' : '' ?>" 
       href="?idpesanan=<?= $idpesanan ?>&status=ready">Done</a>

    <a class="btn-primary <?= ($row['status_pesanan'] == 'paid') ? 'btn-active' : '' ?>" 
       href="?idpesanan=<?= $idpesanan ?>&status=paid">Paid</a>

    <a class="btn-warning <?= ($row['status_pesanan'] == 'pending') ? 'btn-active' : '' ?>" 
       href="?idpesanan=<?= $idpesanan ?>&status=pending">Pending</a>

    <a class="btn-danger <?= ($row['status_pesanan'] == 'cancel') ? 'btn-active' : '' ?>" 
       href="?idpesanan=<?= $idpesanan ?>&status=cancel">Cancelled</a>
</div>
<?php endif; ?>

    <div class="card" id="kandungan">
        <div class="card-header">
            <h4><?= $status_pesanan ?></h4>
        </div>
        <div class="card-body">
            <div class="order-info">
                <p style="font-size: 20px;"><?= $nama_sistem ?></p>
                <ul>
                    <li>Order No.: <?= $idpesanan ?> (<?= $notable ?>)</li>
                    <li>Time: <?= $masa ?></li>
                    <li>Customer: <?= $nama ?> (<?= $nohp ?>)</li>
                    <li><b>Note:</b> <?= $note ?></li>
                </ul>
            </div>
            <hr>
            <div class="order-items">
                <?php
                // Fetch order items
                $sql = "SELECT pi.*, item.namaitem FROM pesanan_item AS pi
                        LEFT JOIN item ON item.iditem = pi.iditem
                        WHERE idpesanan = $idpesanan";
                $result = query($db, $sql);
                $subtotal = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $namaitem = $row['namaitem'];
                    $price = $row['price'];
                    $quantity = $row['quantity'];
                    $item_total = $price * $quantity;
                    $subtotal += $item_total;
                ?>
                    <div class="order-item-row">
                        <p><?= $namaitem ?> x <?= $quantity ?></p>
                        <p>RM <?= number_format($item_total, 2) ?></p>
                    </div>
                <?php } 
                $jumlah_cukai = $subtotal * $cukai;
                $final = $subtotal + $jumlah_cukai;
                ?>
            </div>
            <div class="summary">
                <p>Subtotal: RM <?= number_format($subtotal, 2) ?><br>
                Tax: RM <?= number_format($jumlah_cukai, 2) ?></p>
                <hr style="border: 1px solid #bbb;">
                <strong>Final Total: RM <?= number_format($final, 2) ?></strong>
                <hr style="border: 1px solid #bbb;">
            </div>
        </div>
    </div>

    <div class="thankyou">
        <p>Thank you. Please come again.</p>
        <button class="print-btn" onclick="printcontent('kandungan')">Print</button>
    </div>
    <a href="admin_order.php" class="backagain">Back</a>
</div>
<!-- Footer can be added here if needed -->
</body>
</html>
<?php include('inc_footer.php'); ?>

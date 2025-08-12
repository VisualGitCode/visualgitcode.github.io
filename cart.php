<?php include('inc_header.php'); ?>

<?php
// --- HANDLE ADD/MINUS ACTIONS ---
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (isset($_GET['iditem']) && isset($_GET['action'])) {
    $iditem = $_GET['iditem'];
    $action = $_GET['action'];

    if ($action == 'add') {
        $sql = "SELECT * FROM item WHERE iditem = $iditem AND status_item != 'habis' ";
        $result = query($db, $sql);
        if (mysqli_num_rows($result) > 0) {
            if (isset($cart[$iditem])) {
                $cart[$iditem] += 1;
            } else {
                $cart[$iditem] = 1;
                echo "<script>alert('Item successfully added to the cart');</script>";
            }
        } else {
            exit("<script> alert('Please choose a different item.'); window.history.go(-1);</script>");
        }
    } elseif ($action == 'minus') {
        $cart[$iditem] -= 1;
        if ($cart[$iditem] <= 0) {
            unset($cart[$iditem]);
            echo "<script>alert('Item successfully removed from the cart');</script>";
        }
    }
    $_SESSION['cart'] = $cart;
    echo "<script>window.location.replace('cart.php'); </script>";
    exit;
}


$cukai = 0.06; // 6% tax
$meja_list = ['Take-away', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']; // Example table list
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order</title>
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
            text-align: center;
            margin-bottom: 24px;
        }
        .cart-card {
            border-radius: 16px;
            background: #DDE1C8;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 24px 18px;
            margin-bottom: 24px;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            background-color: #eeeee4;
            border-radius: 10px;
        }
        .cart-table th, .cart-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        .cart-table img {
            width: 90px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 12px;
        }
        .item-info {
            display: flex;
            align-items: center;
        }
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qty-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            font-size: 1.2em;
            text-align: center;
            line-height: 28px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .qty-btn:hover {
            background: #0056b3;
        }
        .qty-display {
            min-width: 32px;
            text-align: center;
            font-weight: bold;
            display: inline-block;
        }
        .checkout-section {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            margin-top: 18px;
        }
        .checkout-form, .summary {
            flex: 1 1 300px;
            min-width: 280px;
        }
        .checkout-form label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }
        .checkout-form select, .checkout-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 14px;
            border: 1px solid #aaa;
            border-radius: 4px;
            font-size: 1em;
        }
        .checkout-form button {
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
        .checkout-form button:hover {
            background: #218838;
        }
        .summary {
            background: #f0f0f0;
            border-radius: 8px;
            padding: 18px 14px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .summary-row.final {
            font-size: 1.1em;
            margin-top: 12px;
            margin-bottom: 0;
        }
        @media (max-width: 700px) {
            .container-main { padding: 8px; }
            .cart-card { padding: 10px 4px; }
            .checkout-section { flex-direction: column; gap: 10px; }
        }
    </style>
</head>
<body>
<div class="container-main">
    <h2>Order</h2>
    <div class="cart-card">
        <?php
        if (!empty($cart)) {
            $all_iditem = implode(',', array_keys($cart));
            $sql = "SELECT * FROM item WHERE iditem IN ($all_iditem)";
            $result = query($db, $sql);
        ?>
        <div class="table-responsive mb-2">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align:left; padding-left:29px;">Quantity</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $subtotal = 0;
                while($row = mysqli_fetch_array($result)) {
                    $iditem = $row['iditem'];
                    $namaitem = $row['namaitem'];
                    $price = $row['price'];
                    $quantity = $cart[$row['iditem']];
                    $item_total = $price * $quantity;
                    $subtotal += $item_total;
                    $image = $row['image'];

                    $img = !empty($image) ? $image_folder.'/'.$image : $image_folder.'/item_placeholder.jpg';
                    echo "<tr>
                        <td>
                            <div class='item-info'>
                                <img src='$img' alt='image'>
                                <div>
                                    <div>$namaitem</div>
                                    <div style='font-size: 0.97em; color: #555;'>RM $price</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class='qty-controls'>
                                <a href='?iditem=$iditem&action=minus' class='qty-btn' title='Subtract'>-</a>
                                <span class='qty-display'>$quantity</span>
                                <a href='?iditem=$iditem&action=add' class='qty-btn' title='Add'>+</a>
                            </div>
                        </td>
                        <td style='text-align:right;'>
                            <span style='font-weight: 500;'>RM ".number_format($item_total, 2)."</span>
                        </td>
                    </tr>";
                }
                $tax_amount = $subtotal * $cukai;
                $final = $subtotal + $tax_amount;
                ?>
                </tbody>
            </table>
        </div>
        <div class="checkout-section">
            <div class="checkout-form">
                <form action="checkout.php" method="POST">
                    <label>Take-away / Dine-in :</label>
                    <select name="notable" required title="Select either Take-away or Table number">
                        <option disabled selected value></option>
                        <?php foreach ($meja_list as $value) { echo "<option value='$value'>$value</option>"; } ?>
                    </select>
                    <label>Note / Remark:</label>
                    <textarea name="note" rows="3" placeholder="Additional order notes"></textarea>
                    <button type="submit">Submit Order</button>
                </form>
            </div>
            <div class="summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>RM <?=number_format($subtotal,2)?></span>
                </div>
                <div class="summary-row">
                    <span>Tax (6%)</span>
                    <span>RM <?=number_format($tax_amount,2)?></span>
                </div>
                <hr>
                <div class="summary-row final">
                    <span>Final</span>
                    <span>RM <?=number_format($final,2)?></span>
                </div>
            </div>
        </div>
        <?php
        } else {
            echo '<div style="text-align:center; color:#888; font-size:1.1em;">The cart is empty. Please visit the Menu page to make a selection.</div>';
        }
        ?>
    </div>
</div>
</body>
</html>
<?php include('inc_footer.php'); ?>

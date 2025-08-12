<?php 
include('inc_setup.php');  // Include setup file for initial configurations
semaklevel('user-admin');  // Check if the user has admin privileges

// Check if the cart is empty
if(empty($cart)) {
    // If the cart is empty, show an alert and redirect to the cart page
    exit("<script>alert('The cart is empty.'); window.location.replace('cart.php');</script>");
}

// Get values from the form submitted by the user
$note = $_POST['note'];        // Additional note or remark
$notable = $_POST['notable'];  // Table number or take-away option
$idpengguna = $_SESSION['idpengguna'];  // User's ID stored in session

// SQL query to insert the order into the 'pesanan' table
$sql = "INSERT INTO pesanan (idpengguna, note, notable, status_pesanan) 
        VALUES ($idpengguna, '$note', '$notable', 'pending')";
$result = query($db, $sql);

// Get the ID of the newly inserted order
$idpesanan = mysqli_insert_id($db);

// Loop through each item in the cart to insert order details into 'pesanan_item'
foreach ($cart as $iditem => $quantity) {
    // Check if the item is available (not out of stock)
    $sql = "SELECT * FROM item WHERE iditem = $iditem AND status_item != 'habis' ";
    $result = query($db, $sql);

    // Fetch item details
    $row = mysqli_fetch_array($result);
    $price = $row['price'];  // Item price

    // Insert the order item into 'pesanan_item' table
    $sql = "INSERT INTO pesanan_item (idpesanan, iditem, price, quantity) 
            VALUES ($idpesanan, $iditem, $price, $quantity)";
    $result = query($db, $sql);
}

// Clear the cart from the session after successful order submission
$_SESSION['cart'] = array();

// Show a success message and redirect to the user's account page
echo "<script>alert('Your order has been sent. Thank you.'); 
      window.location.replace('akaun.php?idpengguna=$idpengguna');</script>";
?>

<?php 
$nama_sistem = "Otaku Eats Order Up";

# Database information
$dbname = "cafedelight"; 
$dbuser = "root";
$dbpass = "";
$dbhost = "localhost";
# Open connection to the database 
$db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) OR exit(mysqli_connect_error());

# Make sure this matches the image folder name in your project directory
$image_folder = "images";

// tax value in decimal: 6% = 0.06
$cukai = 0.06;

# List of table labels at the premises
$meja_list = array(
  'Take-away',
  'Table 1', 
  'Table 2', 
  'Table 3', 
  'Table 4', 
  'Table 5', 
  'Table 6',
);

# function to return order status label
function semakstatus($status){
  if($status == 'ready'){
    return 'Ready for pickup';
  }elseif($status == 'pending'){
    return 'On the way';
  }elseif($status == 'cancel'){
    return 'Cancelled / Rejected';
  }elseif($status == 'paid'){
    return 'Paid';
  }else{
    return $status;
  }
}

# Start session
session_start();
// session stores items in cart
if(!isset($_SESSION['cart'])){
  $_SESSION['cart'] = array();
}
$cart = $_SESSION['cart'];

// session stores user level
if(isset($_SESSION['level'])){
  $level = $_SESSION['level'];
}else{
  $level = $_SESSION['level'] = 'guest';
}

# function to check user level and access permission
function semaklevel($akses){
  $level = $_SESSION['level'];
  $error = "";
 
  if($level == 'guest'){ 
    $error = 'You need to log in to access this page.';
  }elseif($level == 'user'  &&  $akses == 'admin'){
   $error = 'Only admins are able to access this page.';
  }elseif($level == 'admin'  &&  $akses == 'user'){
   $error = 'Only customers are able to access this page.';
  }
 
  if(!empty($error)){
   echo "<script> alert('$error'); window.location.replace('index.php'); </script>";
   exit();
  }
}

// DO NOT EDIT the code below. This function is to display MySQLi errors
function query($db, $sql = ''){
  $result = mysqli_query($db, $sql);
  if (!$result) {
    $error = mysqli_error($db);
    $debugger = "https://sk.jomgeek.com/debugger?msg=".base64_encode($error);
    $file = __FILE__;
    $line = __LINE__;
    $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    $msg = "<div class='alert alert-danger w-100 shadow'>
     <p class='alert-heading h5'><i class='bi bi-bug'></i> Error Detected <a class='btn btn-outline-secondary btn-sm' href='$debugger' target='_blank'><i class='bi bi-question-circle'></i> Explanation</a></p> 
     <hr><b>Message:</b> <mark>$error</mark><br><br>
      <b>SQL Statement</b>: $sql<br><br>URL: $url</div>";
    exit($msg);
  }
  return $result;
}


// session stores font size
if(!isset($_SESSION['saizfont'])){
  $_SESSION['saizfont'] = 100;
}
$saizfont = $_SESSION['saizfont'];

// session stores font type
if(!isset($_SESSION['jenisfont'])){
  $_SESSION['jenisfont'] = 'Arial';
}
$jenisfont = $_SESSION['jenisfont'];

// session stores cursor type
if(!isset($_SESSION['cursor'])){
  $_SESSION['cursor'] = "";
}
$cursor = $_SESSION['cursor'];

# set Malaysia timezone for the system
date_default_timezone_set('Asia/Kuala_Lumpur');
?>

<?php include('inc_header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Special Menu</title>
    <style>
        .banner {
            background: #222;
            color: #fff;
            border-radius: 8px;
            padding: 36px 18px 24px 18px;
            text-align: center;
            margin: 32px auto 24px auto;
            max-width: 900px;
            background-image: url(images/promo.jpg);
            background-size: cover;
            background-position: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
        }
        .banner h1, p {
            text-shadow: 5px 5px 6px rgba(0,0,0,0.9);
        }
        .banner h1 {
            font-size: 2.3em;
            font-weight: bold;
            margin-bottom: 12px;
            color: #e0e0e0;
        }
        .banner p {
            font-size: 1.15em;
            color: #e0e0e0;
        }
        .container-main {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 12px;
        }
        .menu-title {
            margin: 18px 0 18px 0;
            font-size: 1.5em;
            font-weight: bold;
        }
        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 22px;
            margin-bottom: 32px;
        }
        .item-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.7);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .item-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: #eee;
        }
        .item-card-body {
            padding: 18px 14px 14px 14px;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-row span {
            font-weight: bold;
            font-size: 1.08em;
        }
        .add-btn {
            background: #28a745;
            color: #fff;
            padding: 7px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.98em;
            font-weight: bold;
            transition: background 0.2s;
        }
        .add-btn:hover {
            background: #218838;
        }
        .no-items {
            grid-column: 1/-1;
            text-align: center;
            color: #888;
            font-size: 1.1em;
            padding: 32px 0;
        }
        .special-footer {
            text-align: center;
            border-bottom: 1px solid #ddd;
            margin-top: 36px;
            padding-bottom: 18px;
        }
        .special-footer h5 {
            font-weight: bold;
            margin-bottom: 18px;
        }
        .view-all-btn {
            background: #007bff;
            color: #fff;
            padding: 11px 32px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1.12em;
            font-weight: bold;
            transition: background 0.2s;
            display: inline-block;
            margin-top: 12px;
        }
        .view-all-btn:hover {
            background: #0056b3;
        }
        @media (max-width: 700px) {
            .banner, .container-main { padding: 8px; }
            .item-card img { height: 120px; }
            .item-card-body { padding: 10px 6px 8px 6px; }
        }
    </style>
</head>
<body>
    <div class="banner">
        <h1>One Bite & You'll Be Hooked</h1>
        <p>Don't rely on hearsay, try it and judge for yourself.</p>
    </div>

    <div class="container-main">
        <div class="menu-title">Special Menu</div>
        <div class="item-grid">
        <?php 
        $sql = "SELECT * FROM item WHERE status_item = 'istimewa' ORDER BY iditem DESC LIMIT 6";
        $result = query($db, $sql);

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                $iditem = $row['iditem'];
                $namaitem = $row['namaitem'];
                $price = $row['price'];
                $image = $row['image'];
            
        if (!empty($image) ){
            $img = $image_folder . "/" . $image;
        } else {
            $img = $image_folder . "/item_placeholder.jpg";
        }
            echo "
            <div class='item-card'>
                <img src='$img' alt='Item Image'>
                <div class='item-card-body'>
                    <div class='item-row'>
                        <span>$namaitem</span>
                        <a class='add-btn' href='cart.php?iditem=$iditem&amp;action=add'>Add to Cart</a>
                    </div>
                </div>
            </div>
            ";
            }
        } else {
            echo "<div class='no-items'>No special menu items added yet.</div>";
        }
        ?>
        </div>
        <div class="special-footer">
            <h5>Tempting in every way</h5>
            <a href="item.php" class="view-all-btn">All Menu</a>
        </div>
    </div>
</body>
</html>
<?php include('inc_footer.php'); ?>

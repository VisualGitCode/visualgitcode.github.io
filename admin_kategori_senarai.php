<?php include('inc_header.php'); ?>
<?php
semaklevel('admin'); 

$sql = "SELECT k.*, COUNT(i.iditem) as item_count 
        FROM kumpulan AS k 
        LEFT JOIN item AS i ON k.idkumpulan = i.idkumpulan 
        GROUP BY k.idkumpulan 
        ORDER BY namakumpulan";
$result = query($db, $sql);
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Category List</title>
        <style>
        .container-main {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.7);
            padding: 24px;
        }
        h2 {
            margin: 0;
        }
        .kumpulan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .kumpulan-table th, .kumpulan-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
        }
        .kumpulan-table th {
            background: #f0f0f0;
            font-weight: bold;
        }
        .kumpulan-table tr:nth-child(even) {
            background: #fafafa;
        }
        .kumpulan-table td.right {
            text-align: right;
        }
        .add-btn {
            background: #007bff;
            color: #fff;
            padding: 7px 18px;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            display: inline-block;
        }
        .add-btn:hover {
            background: #0056b3;
        }
        .edit-btn {
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
        .edit-btn:hover {
            background: #117a8b;
        }
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            gap: 10px;
        }
        .col {
            flex: 1;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        @media (max-width: 600px) {
            .container-main { padding: 8px; }
            .kumpulan-table th, .kumpulan-table td { padding: 7px 4px; font-size: 0.97em; }
            .row { flex-direction: column; gap: 8px; align-items: flex-start; }
            .text-end { text-align: left; }
            .text-center { text-align: left; }
        }
        </style>
</head>
<body>

<div class="container-main">
    <div class="row header-row">
        <div class="col"></div> <!-- Empty col for balancing -->
        <div class="col text-center">
            <h2>Category List</h2>
        </div>
        <div class="col text-end">
            <a class="add-btn" href="admin_kategori_borang.php">+ Add Category</a>
        </div>
    </div>

<?php
if($total > 0){
    echo "<div>Total: $total</div><br>";
    echo "<table class='kumpulan-table'>
            <tr>
                <th>Category Name</th>
                <th width='150'>Action</th>
            </tr>";
    while($row = mysqli_fetch_array($result)) {
        $idkumpulan = $row['idkumpulan'];
        $namakumpulan = $row['namakumpulan'];
        $item_count = $row['item_count'];
        echo "<tr>
                <td>$namakumpulan ($item_count item)</td>
                <td class='right'>
                  <a class='edit-btn' href='admin_kategori_borang.php?idkumpulan=$idkumpulan'>Edit</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No categories yet.";
}
?>
</div>
</body>
</html>
<?php include('inc_footer.php'); ?>

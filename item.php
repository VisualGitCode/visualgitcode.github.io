<?php 
include('inc_header.php');

$namamenu = '';
$active_category = 'all';
$where = [];
$params = [];
$param_types = '';

// Handle category filter
if (!empty($_GET['idkumpulan'])) {
    $active_category = strtolower(trim($_GET['idkumpulan']));
    $where[] = "LOWER(item.idkumpulan) LIKE ?";
    $params[] = '%' . $active_category . '%';
    $param_types .= 's';
} elseif (isset($_GET['all'])) {
    $active_category = 'all';
}

// Handle search filter
if (!empty($_POST['namamenu'])) {
    $namamenu = trim($_POST['namamenu']);
    if ($namamenu !== '') {
        $where[] = "item.namaitem LIKE ?";
        $params[] = '%' . $namamenu . '%';
        $param_types .= 's';
        // Keep category active while searching, so comment out the line below
        // $active_category = 'all'; 
    }
}

$where_clause = '';
if ($where) {
    $where_clause = 'WHERE ' . implode(' AND ', $where);
}

// Fetch categories
$sql_kumpulan = "SELECT * FROM kumpulan ORDER BY namakumpulan ASC";
$res_kumpulan = mysqli_query($db, $sql_kumpulan);

// Prepare items query
$sql_items = "SELECT item.*, kumpulan.namakumpulan FROM item
              LEFT JOIN kumpulan ON item.idkumpulan = kumpulan.idkumpulan
              $where_clause
              ORDER BY item.namaitem ASC";

$stmt = mysqli_prepare($db, $sql_items);
if ($params) {
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
}
mysqli_stmt_execute($stmt);
$res_items = mysqli_stmt_get_result($stmt);
?>

<style>
.container-main {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.7);
    padding: 24px;
    font-family: Arial, sans-serif;
}
.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}
.add-btn {
    background: #007bff;
    color: #fff;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.2s;
}
.add-btn:hover { background: #0056b3; }
.search-form {
    display: flex;
    gap: 8px;
    margin-bottom: 18px;
}
.search-form input[type="text"] {
    flex: 1;
    padding: 8px;
    border: 1px solid #aaa;
    border-radius: 4px;
    font-size: 1em;
}
.btn-search {
    background: #28a745;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 4px;
    font-size: 1em;
    cursor: pointer;
}
.btn-search:hover { background: #218838; }
.categories-row {
    display: flex;
    gap: 8px;
    margin-bottom: 18px;
    overflow-x: auto;
    white-space: nowrap;
}
.category-btn {
    background: #fff;
    color: #007bff;
    border: 1px solid #007bff;
    border-radius: 30px;
    padding: 6px 14px;
    text-decoration: none;
    font-size: 0.97em;
    transition: background 0.2s, color 0.2s;
}
.category-btn:hover,
.category-btn.active {
    background: #007bff;
    color: #fff;
}
.menu-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.menu-table td {
    padding: 12px 8px;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: top;
}
.menu-table img {
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
}
.item-info { margin-left: 18px; }
.item-name { font-weight: bold; margin-bottom: 4px; }
.item-description { color: #555; font-size: 0.98em; }
.item-category { font-size: 0.93em; color: #888; }
.edit-link {
    color: #28a745;
    font-size: 0.95em;
    margin-left: 8px;
    text-decoration: underline;
}
.edit-link:hover { color: #155724; }
.order-btn {
    background: #28a745;
    color: #fff;
    padding: 6px 14px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.2s;
}
.order-btn:hover { background: #218838; }
.soldout {
    color: #fff;
    background: #dc3545;
    font-weight: bold;
    padding: 6px 14px;
    border-radius: 4px;
    display: inline-block;
    font-size: 1em;
    letter-spacing: 1px;
}
@media (max-width: 700px) {
    .container-main { padding: 8px; }
    .menu-table img { width: 80px; height: 55px; }
}
</style>

<div class="container-main">
    <div class="header-row">
        <h2>All Menu</h2>
        <?php if ($level == 'admin'): ?>
            <a class="add-btn" href="admin_item_borang.php">Add Item</a>
        <?php endif; ?>
    </div>

    <form method="POST" class="search-form" action="?<?= $active_category != 'all' ? 'idkumpulan=' . urlencode($active_category) : 'all' ?>">
        <input type="text" name="namamenu" placeholder="Menu name" value="<?= htmlspecialchars($namamenu) ?>">
        <button class="btn-search" type="submit" name="search" value="Search">Search</button>
    </form>

    <div class="categories-row">
        <a href="?all" class="category-btn <?= $active_category == 'all' ? 'active' : '' ?>">All</a>
        <?php while ($row = mysqli_fetch_assoc($res_kumpulan)): 
            $cat_id = strtolower($row['idkumpulan']);
            $cat_name = htmlspecialchars($row['namakumpulan']);
        ?>
            <a href="?idkumpulan=<?= urlencode($cat_id) ?>" class="category-btn <?= $active_category == $cat_id ? 'active' : '' ?>">
                <?= $cat_name ?>
            </a>
        <?php endwhile; ?>
    </div>

    <table class="menu-table">
        <tbody>
        <?php if (mysqli_num_rows($res_items) > 0): ?>
            <?php while ($item = mysqli_fetch_assoc($res_items)): 
                $img = !empty($item['image']) ? htmlspecialchars($image_folder . '/' . $item['image']) : htmlspecialchars($image_folder . '/item_placeholder.jpg');
                $status = $item['status_item'];
            ?>
            <tr>
                <td>
                    <div style="display: flex; align-items: flex-start;">
                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($item['namaitem']) ?>">
                        <div class="item-info">
                            <div class="item-name">
                                <?= htmlspecialchars($item['namaitem']) ?>
                                <?php if ($level == 'admin'): ?>
                                    <a href="admin_item_borang.php?iditem=<?= (int)$item['iditem'] ?>" class="edit-link">Edit</a>
                                <?php endif; ?>
                            </div>
                            <div class="item-category"><?= htmlspecialchars($item['namakumpulan'] ?: '(No category)') ?></div>
                            <div class="item-description"><?= htmlspecialchars($item['description']) ?></div>
                        </div>
                    </div>
                </td>
                <td style="text-align: right; vertical-align: middle;">RM <?= number_format($item['price'], 2) ?></td>
                <td style="text-align: right; vertical-align: middle;">
                    <?php if ($status === 'habis'): ?>
                        <span class="soldout">Sold Out</span>
                    <?php else: ?>
                        <a class="order-btn" href="cart.php?iditem=<?= (int)$item['iditem'] ?>&amp;action=add">Order</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3" style="text-align:center;">No items found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('inc_footer.php'); ?>

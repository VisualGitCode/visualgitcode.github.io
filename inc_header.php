<?php include('inc_setup.php'); ?>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
function active($page, $current) {
    return $page === $current ? 'active' : '';
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?= $nama_sistem ?></title>
<link rel="icon" href="images/favicon.png" type="image/png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
:root {
    --main-green: #28a745;
    --main-red: #dc3545;
    --bg-light: #fff;
}
* {
    font-family: <?= htmlspecialchars($jenisfont) ?>;
    font-size: <?= $saizfont ?>%;
    cursor: <?= htmlspecialchars($cursor) ?>;
}
body {
    margin: 0;
    padding: 0;
    background: #0e0b22 url('images/background.png') no-repeat center/cover;
}
input, textarea, select {
    background-color: #F5DEB3;
    border: 2px solid #0c95fe;
    border-radius: 8px;
}
input:focus, textarea:focus { background-color: #add8e6; }
.wrapper {
    border-radius: 16px;
    background: var(--bg-light);
    max-width: 1000px;
    min-height: 100vh;
    margin: 0 auto;
    padding: 1px 50px 0 50px;
    box-shadow: 0 1px 6px #222;
    transition: padding-left 0.3s;
}
.sidebar {
    position: fixed;
    top: 0; left: 0;
    width: 160px; height: 100vh;
    background: var(--bg-light);
    box-shadow: 2px 0 6px #ccc;
    display: flex; flex-direction: column;
    align-items: stretch;
    padding-top: 24px;
    transition: transform 0.3s ease;
}
.sidebar.closed { transform: translateX(-100%); }
.sidebar img {
    height: 120px;
    margin: 0 auto 10px;
    border-radius: 10px;
}
#sidebarClose, #sidebarToggle {
    border: none; cursor: pointer;
    color: #fff;
}
#sidebarClose {
    position: absolute; top: 10px; right: 10px;
    background: var(--main-red);
    border-radius: 50%;
    width: 30px; height: 30px;
    font-size: 1.2em;
}
#sidebarToggle {
    position: fixed; top: 18px; left: 18px;
    background: var(--main-green);
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 1.4em;
    display: none;
}
.navbar {
    display: flex; flex-direction: column;
    gap: 10px; margin: 0 12px;
}
.navbar a {
    display: block;
    padding: 10px 14px;
    border-radius: 6px;
    font-weight: bold;
    text-decoration: none;
    border: 2px solid var(--main-green);
    background: var(--bg-light);
    color: var(--main-green);
    transition: background 0.2s, color 0.2s;
}
.navbar a:hover, .navbar a.active {
    background: var(--main-green);
    color: #fff;
}
.navbar a.danger {
    border-color: var(--main-red);
    color: var(--main-red);
}
.navbar a.danger:hover, .navbar a.danger.active {
    background: var(--main-red);
    color: #fff;
}
@media (max-width: 700px) {
    .sidebar { width: 80vw; min-width: 180px; }
    .wrapper { padding-left: 0; }
}
</style>
</head>
<body>

<button id="sidebarToggle">&#9776;</button>

<div class="sidebar" id="sidebar">
    <button id="sidebarClose">×</button>
    <img src="images/logo.png" alt="Logo">
    <nav class="navbar">
        <a href="index.php" class="<?= active('index.php', $current_page) ?>">Home</a>
        <a href="item.php" class="<?= active('item.php', $current_page) ?>">Menu</a>
        <a href="cart.php" class="<?= active('cart.php', $current_page) ?>">
            Cart <?= !empty($cart) ? '['.array_sum($cart).']' : '' ?>
        </a>
        <a href="ketetapan.php" class="<?= active('ketetapan.php', $current_page) ?>">Settings</a>

        <?php if ($level == 'user'): ?>
            <a href="akaun.php" class="<?= active('akaun.php', $current_page) ?>">Account</a>
        <?php endif; ?>

        <?php if ($level == 'admin'): ?>
            <a href="admin_order.php" class="<?= active('admin_order.php', $current_page) ?>">Manage Orders</a>
            <a href="admin_kategori_senarai.php" class="<?= active('admin_kategori_senarai.php', $current_page) ?>">Manage Categories</a>
            <a href="admin_pengguna_senarai.php" class="<?= active('admin_pengguna_senarai.php', $current_page) ?>">Manage Users</a>
            <a href="admin_import.php" class="<?= active('admin_import.php', $current_page) ?>">Import</a>
            <a href="admin_laporan.php" class="<?= active('admin_laporan.php', $current_page) ?>">Reports</a>
        <?php endif; ?>

        <?php if ($level == 'guest'): ?>
            <a href="login.php" class="danger <?= active('login.php', $current_page) ?>">Login</a>
            <a href="signup.php" class="danger <?= active('signup.php', $current_page) ?>">Sign Up</a>
        <?php else: ?>
            <a href="logout.php" class="danger <?= active('logout.php', $current_page) ?>">Log Out</a>
        <?php endif; ?>
    </nav>
</div>

<div class="wrapper">
<!-- Page content starts here -->

<script>
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarClose = document.getElementById('sidebarClose');

function toggleSidebar(open) {
    sidebar.classList.toggle('closed', !open);
    sidebarToggle.style.display = open ? 'none' : 'block';
}

function setInitialSidebar() {
    toggleSidebar(window.innerWidth > 700);
}
setInitialSidebar();

window.addEventListener('resize', setInitialSidebar);
sidebarToggle.addEventListener('click', () => toggleSidebar(true));
sidebarClose.addEventListener('click', () => toggleSidebar(false));
</script>

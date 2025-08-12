<?php
include('inc_header.php');

// Font size change
if (isset($_GET['font'])) {
    $saizfont = $_SESSION['saizfont'] ?? 100;

    if ($_GET['font'] == 'plus') {
        $saizfont += 3;
    } elseif ($_GET['font'] == 'minus') {
        $saizfont -= 1;
    } else {
        $saizfont = 100;
    }

    $_SESSION['saizfont'] = $saizfont;
    exit('<script>window.history.go(-1);</script>');
}

// Available fonts & cursors
$senarai_fonts = ['Arial', 'Arial Black', 'Courier New', 'cursive', 'Times New Roman'];
$senarai_cursors = ['default', 'pointer', 'crosshair', 'text', 'wait', 'help', 'move'];

// Save settings
if (isset($_POST['jenisfont']) && isset($_POST['cursor'])) {
    if (in_array($_POST['jenisfont'], $senarai_fonts)) {
        $_SESSION['jenisfont'] = $_POST['jenisfont'];
    }

    if (in_array($_POST['cursor'], $senarai_cursors)) {
        $_SESSION['cursor'] = $_POST['cursor'];
    }

    echo "<script>alert('Settings saved.'); window.history.go(-1);</script>";
    exit;
}

// Current settings
$jenisfont = $_SESSION['jenisfont'] ?? 'Arial';
$cursor = $_SESSION['cursor'] ?? 'default';
$saizfont = $_SESSION['saizfont'] ?? 100;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Settings</title>
    <style>
        body {
            font-family: <?= htmlspecialchars($jenisfont) ?>;
            font-size: <?= $saizfont ?>%;
            cursor: <?= htmlspecialchars($cursor) ?>;
            overflow: hidden;
        }
        .settings-container {
            max-width: 400px;
            margin: 32px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.7);
            padding: 28px 22px 22px 22px;
        }
        h2, h4 {
            text-align: center;
        }
        .font-size-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 22px;
        }
        .font-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            font-weight: bold;
            padding: 7px 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .font-btn:hover {
            background: #0056b3;
        }
        form {
            margin-top: 18px;
        }
        label, select, button {
            display: block;
            width: 100%;
            margin-bottom: 16px;
            font-size: 1em;
        }
        select {
            padding: 8px;
            border: 1.5px solid #0c95fe;
            border-radius: 7px;
            background: #eafff6;
        }
        button[type="submit"] {
            background: #28a745;
            color: #fff;
            padding: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 1.07em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        button[type="submit"]:hover {
            background: #218838;
        }
    </style>
</head>
<body>
<div class="settings-container">
    <h2>Settings</h2>

    <h4>Font Size</h4>
    <div class="font-size-controls">
        <a class="font-btn" href="?font=plus">+</a>
        <a class="font-btn" href="?font=minus">-</a>
        <a class="font-btn" href="?font=reset">Reset</a>
    </div>

    <form method="POST">
        <h4>Font Type</h4>
        <select name="jenisfont">
            <?php foreach ($senarai_fonts as $font): ?>
                <option value="<?= $font ?>" <?= $font == $jenisfont ? 'selected' : '' ?>><?= $font ?></option>
            <?php endforeach; ?>
        </select>

        <h4>Cursor Effects</h4>
        <select name="cursor">
            <?php foreach ($senarai_cursors as $cur): ?>
                <option value="<?= $cur ?>" <?= $cur == $cursor ? 'selected' : '' ?>><?= $cur ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Save Settings</button>
    </form>
</div>
</body>
</html>
<?php include('inc_footer.php'); ?>

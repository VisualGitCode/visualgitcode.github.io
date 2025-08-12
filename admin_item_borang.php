<?php 
include('inc_header.php');
semaklevel('admin');

$namaitem = $description = $price = $status_item = $image = $idkumpulan = "";
$edit_data = null; // null means no edit

// --- DELETE ITEM ---
if (isset($_GET['delete'])) {
    $iditem = (int)$_GET['delete'];
    $sql = "DELETE FROM item WHERE iditem = $iditem";
    query($db, $sql);
    exit("<script>alert('Item has been deleted.'); window.location.replace('item.php');</script>");
}

// --- EDIT ITEM ---
if (isset($_GET['iditem'])) {
    $iditem = (int)$_GET['iditem'];
    $sql = "SELECT * FROM item WHERE iditem = $iditem LIMIT 1";
    $result = query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        $edit_data = mysqli_fetch_assoc($result);
        $namaitem = $edit_data['namaitem'];
        $description = $edit_data['description'];
        $image = $edit_data['image'];
        $price = $edit_data['price'];
        $idkumpulan = $edit_data['idkumpulan'];
        $status_item = $edit_data['status_item'];
    } else {
        echo "<script>alert('Item not found.'); window.location.replace('item.php');</script>";
        exit();
    }
}

// --- SAVE ITEM ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['namaitem']) && !empty(trim($_POST['namaitem']))) {
    $namaitem = mysqli_real_escape_string($db, trim($_POST['namaitem']));
    $description = mysqli_real_escape_string($db, trim($_POST['description']));
    $price = floatval($_POST['price']);
    $idkumpulan = (int)$_POST['idkumpulan'];
    $status_item = $_POST['status_item'];

    // Validate price is positive
    if ($price < 0) {
        echo "<script>alert('Price must be a positive number.');</script>";
        $price = 0;
    }

    // Handle image upload if any
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $i = $_FILES['image'];
        $file_ext = strtolower(pathinfo($i['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpeg','jpg','png','bmp','gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $location = __DIR__ . '/' . $image_folder . '/';

            // Delete old image if exists and is different
            if (!empty($image) && file_exists($location . $image)) {
                unlink($location . $image);
            }

            $newname = uniqid('item_') . '.' . $file_ext;
            if (move_uploaded_file($i['tmp_name'], $location . $newname)) {
                $image = $newname;
            } else {
                echo "<script>alert('Failed to upload image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image file type.');</script>";
        }
    }

    if ($edit_data) {
        // Update existing item
        $sql = "UPDATE item 
                SET namaitem='$namaitem', description='$description', image='$image', price=$price, idkumpulan=$idkumpulan, status_item='$status_item' 
                WHERE iditem=$iditem";
    } else {
        // Insert new item with default status 'ada'
        $sql = "INSERT INTO item (namaitem, description, image, price, idkumpulan, status_item) 
                VALUES ('$namaitem', '$description', '$image', $price, $idkumpulan, 'ada')";
    }

    query($db, $sql);
    echo "<script>alert('Item saved successfully.'); window.location.replace('item.php');</script>";
    exit();
}
?>

<style>
/* ... your CSS remains unchanged ... */
.item-form-container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.7);
    padding: 28px 22px 22px 22px;
}
.form-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}
.form-header-row h2 {
    margin: 0;
}
.delete-btn {
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 14px;
    font-size: 0.97em;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
}
.delete-btn:hover {
    background: #b21f2d;
}
.item-form {
    display: flex;
    flex-wrap: wrap;
    gap: 28px;
    margin-top: 10px;
}
.form-section {
    flex: 2 1 340px;
    min-width: 270px;
}
.img-section {
    flex: 1 1 200px;
    min-width: 180px;
    text-align: center;
}
label {
    font-weight: bold;
    display: block;
    margin-bottom: 4px;
}
input[type="text"], input[type="number"], select, textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 14px;
    border: 1px solid #aaa;
    border-radius: 4px;
    font-size: 1em;
    box-sizing: border-box;
}
textarea {
    resize: vertical;
}
button[type="submit"] {
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
button[type="submit"]:hover {
    background: #218838;
}
.img-preview {
    width: 100%;
    max-width: 220px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
}
.img-label {
    font-weight: bold;
    margin-bottom: 4px;
    display: block;
}
@media (max-width: 900px) {
    .item-form-container { padding: 8px; }
    .item-form { flex-direction: column; gap: 10px; }
    .img-section { text-align: left; }
}
</style>

<script>
function deletethis(iditem) {
    if (confirm("Are you sure you want to delete this item?")) {
        window.location.href = "admin_item_borang.php?delete=" + iditem;
    }
}
</script>

<div class="item-form-container">
    <div class="form-header-row">
        <h2>Item Form</h2>
        <?php if ($edit_data) : ?>
            <a class="delete-btn" href="javascript:void(0);" onclick="deletethis(<?= $iditem ?>)">Delete Item</a>
        <?php endif; ?>
    </div>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF'] . (isset($iditem) ? "?iditem=$iditem" : '')) ?>" enctype="multipart/form-data" class="item-form">
        <div class="form-section">
            <label>Item / Menu Name</label>
            <input type="text" placeholder="Name down the new Item / Menu" name="namaitem" value="<?= htmlspecialchars($namaitem) ?>" required>

            <label>Description</label>
            <textarea name="description" placeholder="Details about the new Item / Menu" rows="2"><?= htmlspecialchars($description) ?></textarea>

            <label>Price</label>
            <input type="number" step=".01" min="0" name="price" placeholder="Set the price for the new Item / Menu" value="<?= htmlspecialchars($price) ?>">

            <label>Category</label>
            <select name="idkumpulan" required>
                <?php 
                $sql = "SELECT * FROM kumpulan";
                $result = query($db, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $kumpulan = $row['idkumpulan'];
                    $namakumpulan = $row['namakumpulan'];
                    $selected = ($kumpulan == $idkumpulan) ? "selected" : "";
                    echo "<option value='$kumpulan' $selected>$namakumpulan</option>";
                }
                ?>
            </select>

            <label>
                Item Status 
                <span title="Special status items will be displayed on the homepage." style="cursor:help; color:#007bff;">&#x2753;</span>
            </label>
            <select name="status_item" required>
                <option value="ada" <?= $status_item == 'ada' ? 'selected' : '' ?>>Available</option>
                <option value="istimewa" <?= $status_item == 'istimewa' ? 'selected' : '' ?>>Special</option>
                <option value="habis" <?= $status_item == 'habis' ? 'selected' : '' ?>>Sold Out</option>
            </select>
        </div>
        <div class="img-section">
            <?php if (!empty($image) && file_exists(__DIR__ . "/$image_folder/$image")) : ?>
                <img src="<?= $image_folder . '/' . htmlspecialchars($image) ?>" class="img-preview" alt="Item Image">
            <?php else : ?>
                <img src="<?= $image_folder . '/item_placeholder.jpg' ?>" class="img-preview" alt="Item Image">
            <?php endif; ?>
            <label class="img-label" for="image">Upload Image</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>
        <div style="flex-basis:100%;"></div>
        <button type="submit">Save</button>
    </form>
</div>

<?php include('inc_footer.php'); ?>

<?php
session_start();
include('connection.php');

// Set user ID
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

$sql = "SELECT * FROM product";

if (!empty($_POST['Searchtext'])) {
    $search = "%" . $_POST['Searchtext'] . "%";
    $sql = "SELECT * FROM product WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search);
} elseif (isset($_POST['category']) && $_POST['category'] != 'all') {
    $category = $_POST['category'];
    $sql = "SELECT * FROM product WHERE category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$all_product = $stmt->get_result();

if ($all_product->num_rows > 0) {
    while ($row = $all_product->fetch_assoc()) {
        echo '
        <form action="" method="post" class="item">
            <a href="view_page.php?pid=' . $row["product_id"] . '"><img src="assets/product_img/' . $row["image1"] . '.png" alt=""></a>
            <div class="con">
                <h2>' . $row["name"] . '<span>(' . $row["quantity"] . 'kg)</span></h2>
                <button type="submit" class="wish-btn" name="add_to_wishlist"><img class="svg" src="assets/img/love.png" width="30px"></button>
            </div>
            <input type="hidden" name="product_id" value="' . $row["product_id"] . '">
            <input type="hidden" name="product_price" value="' . $row["price"] . '">
            <div class="price">' . $row["price"] . 'Tk</div>
            <button type="submit" name="add_to_cart" class="addcart">Add to Cart</button>
        </form>';
    }
} else {
    echo '<p>No products found</p>';
}
?>

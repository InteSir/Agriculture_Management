<?php
session_start();
include('connection.php');


$response = [];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 1;
}

if (isset($_POST['action']) && isset($_POST['product_id'])) {

    $product_id = $_POST['product_id'];
    $sql="SELECT price FROM product WHERE product_id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_price = $stmt->get_result();

    if ($_POST['action'] == 'cart') {

        $qty = 1;
        $sql_cart = "SELECT product_id, user_id FROM cart WHERE product_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql_cart);
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $response['status'] = 'success';
            $response['message'] = 'Product already exists in the wishlist';
            

        } else {
            $sql = "INSERT INTO cart (user_id, product_id, price, quantity) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiii", $user_id, $product_id, $product_price, $qty);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Product added to cart successfully';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Failed to add product to cart';
            }
        }
    } elseif ($_POST['action'] == 'wishlist') {
        $sql_wish = "SELECT product_id, user_id FROM wishlist WHERE product_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql_wish);
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $response['status'] = 'warning';
            $response['message'] = 'Product already exists in the wishlist';
        } else {
            $sql = "INSERT INTO wishlist (user_id, product_id, price) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $user_id, $product_id, $product_price);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Product added to wishlist successfully';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Failed to add product to wishlist';
            }
        }
    }
} 

// else {
//     $response['status'] = 'error';
//     $response['message'] = 'Invalid product details';
// }

echo json_encode($response);

?>



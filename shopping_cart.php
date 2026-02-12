<?php

error_reporting(E_ALL ^ E_NOTICE);
session_start();
include('connection.php');


if (isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
}else{
    $user_id='';

}


if (isset($_POST['action']) && isset($_POST['product_id'])) {

    $product_id = $_POST['product_id'];

    $action = $_POST['action'];

    if ($action == 'plus') {
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
    } elseif ($action == 'minus') {
        $sql = "UPDATE cart SET quantity = quantity - 1 WHERE user_id = '$user_id' AND product_id = '$product_id' AND quantity > 1";
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    }

    if (mysqli_query($conn, $sql)) {
        $sql2 = "SELECT SUM(price * quantity) AS total_price FROM cart WHERE user_id = '$user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $total_price = $row2['total_price'];

        echo json_encode(['success' => true, 'total_price' => $total_price]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="product.css">

</head>
<body>
    

    <div class="carTab">
        <h1>Shopping Cart</h1>

        <form method="post" class="listCart">
            <?php 
                $sql1="SELECT * FROM product WHERE product_id IN (SELECT product_id FROM cart WHERE user_id = '$user_id')";
                $all_cart=mysqli_query($conn,$sql1);

                if (mysqli_num_rows($all_cart) > 0) {
                    while ($row = mysqli_fetch_assoc($all_cart)) {                           
            ?>
            <div class="item"  id="item-<?php echo $row['product_id']; ?>">
                <div class="img"><img src="assets/product_img/<?php echo $row["image1"];?>.png" alt=""></div>
                <div class="cart-content">
                    <div class="name"><?php echo $row["name"];?></div>
                    <div class="totalPrice"><?php echo $row["price"];?>Tk</div>
                </div>

                <div class="quantity">
                    <button type="button" class="minus" onclick="updateCart('minus', '<?php echo $row['product_id']; ?>')">-</button>
                    <span id="quantity-<?php echo $row['product_id']; ?>"><?php 
                        $product_id = $row['product_id'];
                        $sql2 = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
                        $result2 = mysqli_query($conn, $sql2);
                        $row2 = mysqli_fetch_assoc($result2);
                        echo $row2["quantity"]; 
                        ?>
                    </span>
                    <button type="button" class="plus" onclick="updateCart('plus', '<?php echo $row['product_id']; ?>')">+</button>
                </div>

                <div>
                    <button type="submit" name="delete"  onclick="updateCart('delete', '<?php echo $row['product_id']; ?>')" style="background: none;border:none;      padding: 0; cursor: pointer;">
                        <img src="assets/img/bin.png" alt="Delete" style="width: 30px; height: 30px;">
                    </button>
                    
                </div>
            </div>
            <?php 
                    }
                } else {
                    echo 'No product added in cart';
                }
            ?>
        </form>

        <div class="total">Total: <span id="total-price">
            <?php
                $sql2 = "SELECT SUM(price * quantity) AS total_price FROM cart WHERE user_id = '$user_id'";
                $total = mysqli_query($conn, $sql2);
                if ($total) {
                    $price = mysqli_fetch_assoc($total);
                    echo $price['total_price'];
                } else {
                    echo "0";
                }
            ?>
        </span> Tk
        </div>
        
        <div class="btn">
            <button  type="button" class="close">CLOSE</button>
            <button class="checkout" onclick="window.location.href='checkout.php';">CHECK OUT</button>
            
        </div>
    </div>

    <script>

        function updateCart(action, product_id) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "shopping_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        if (action === 'delete') {
                            document.getElementById('item-' + product_id).remove();
                        } else {
                            var quantitySpan = document.getElementById('quantity-' + product_id);
                            var quantity = parseInt(quantitySpan.innerText);
                            if (action === 'plus') {
                                quantitySpan.innerText = quantity + 1;
                            } else if (action === 'minus' && quantity > 1) {
                                quantitySpan.innerText = quantity - 1;
                            }
                        }
                        document.getElementById('total-price').innerText = response.total_price;
                    }
                }
            };

            xhr.send("action=" + action + "&product_id=" + product_id);
        }
    </script>


    <script src="script.js"></script>
</body>




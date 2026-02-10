<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include('connection.php');


if (isset($_SESSION['user_id'])){

    $user_id=$_SESSION['user_id'];
    }else{
        $user_id='';

    }
    if (isset($_POST['logout'])){
        session_destroy();
        header('location: login.php');
    }

    
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['edit_address'])){
                $address= $_POST['address'].', '.$_POST['city'].', '.$_POST['district'];
                $name=$_POST['name'];
                $number=$_POST['number'];
                $address_type=$_POST['address_type'];

        
        }else{
                $sql = "SELECT * FROM users WHERE user_id='$user_id'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                $name = $_SESSION['username'];
                $number = $row['number'];
                $address = $row['address'];
                $address_type ='Home';
        }


        if(isset($_POST['place-order'])){

            $method=$_POST['method'];

            $card_details = '';
            if ($method == 'Credit or debit card' || $method == 'Net Banking') {
                $card_details = $_POST['card_name'] . ', ' . $_POST['card_number'] . ', ' . $_POST['expiry'] . ', ' . $_POST['cvv'];
            }

            

            $sql2 = "SELECT SUM(price * quantity) AS total_price FROM cart WHERE user_id = '$user_id'";
            $result=mysqli_query($conn,$sql2);
            $row = mysqli_fetch_assoc($result);
            $total_amount =$row['total_price'];


            $sql="SELECT * FROM cart where user_id='$user_id'";
            $verify=mysqli_query($conn,$sql);



            if(mysqli_num_rows($verify)>0){

                $sql_order="INSERT INTO `order` (user_id,name,number,address,address_type,method,card_details,total_amount) VALUES ('$user_id','$name','$number','$address','$address_type','$method','$card_details','$total_amount')";

                $result = mysqli_query($conn,$sql_order);

                if ($result){
                    $order_id = mysqli_insert_id($conn);
                    
                    $get_products="SELECT * FROM cart where user_id='$user_id'";
                    $products_result = mysqli_query($conn, $get_products);
                    

                    if(mysqli_num_rows($products_result)>0){
                        while($product = mysqli_fetch_assoc($products_result)) {

                            $product_id=$product['product_id'];
                            $quantity=$product['quantity'];
                            $price=$product['price'];

                            $sql_orderitems="INSERT INTO order_items (order_id,product_id,quantity,price) VALUES ('$order_id','$product_id','$quantity','$price')";

                            $result=mysqli_query($conn,$sql_orderitems);
                        }

                            
                        if (empty($warning_msg)) {
                            $sql_delete_cart = "DELETE FROM cart WHERE user_id = '$user_id'";
                            mysqli_query($conn, $sql_delete_cart);
    
                            $success_msg[] = "Order placed successfully!";
                            header('location:order.php');
                        }
                    }
                    else{
                        $warning_msg[] = 'No products found in the cart';
                    }

                } else{
                    $warning_msg[]='something went wrong';
                }
            }else{
                $warning_msg[] = 'No items in the cart';
            }
        }
    }




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INAR-CheckOut page</title>
    <link rel='stylesheet' href='product.css'>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <?php include('header.php'); ?>
    <?php include 'shopping_cart.php' ?>

    <main>
        <div class="product_first">
            <div class="product_b">
                <img src="assets/img/Banner-3.jpg" alt="">
                <h1 class='p-title'>CheckOut Summary</h1>
            </div>

            
        </div>
        <section class='shop_checkout'>
            <div class='shopping_cart'>

                                

                <h1 class=title>Shopping Cart</h1>

                <form method="post" class="order_cart">
                    <?php 
                        $sql1="SELECT * FROM product WHERE product_id IN (SELECT product_id FROM cart WHERE user_id = '$user_id')";
                        $all_cart=mysqli_query($conn,$sql1);

                        if (mysqli_num_rows($all_cart) > 0) {
                            while ($row = mysqli_fetch_assoc($all_cart)) {                           
                    ?>
                    <div class="item">
                        <div class="img"><img src="assets/product_img/<?php echo $row["image1"];?>.png" alt=""></div>
                       
                        <div class="name"><?php echo $row["name"];?> </div>
                        <div class="totalPrice"><?php echo $row["price"];?>Tk<span style="padding:0 10px">X</span><span id="quantity-<?php echo $row['product_id']; ?>"><?php 
                                $product_id = $row['product_id'];
                                $sql2 = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
                                $result2 = mysqli_query($conn, $sql2);
                                $row2 = mysqli_fetch_assoc($result2);
                                echo $row2["quantity"]; 
                                ?>
                            </span>
                    
                    </div>

                        <!-- <div class="quantity">
                            <button type="button" class="minus" onclick="updateCart('minus', '<?php echo $row['product_id']; ?>')">-</button>

                            <button type="button" class="plus" onclick="updateCart('plus', '<?php echo $row['product_id']; ?>')">+</button>
                        </div> -->

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


                <div class="line"></div>

                <div class="total">
                    <div>
                        SUBTOTAL
                    </div>
                    <div>
                        <span id="total-price">
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


                </div>
                

            </div>



            <div class='payment'>
                <div class="profile"> 
                    <h1 class=title>Profile</h1>
                    <form method="post" class="">
                        <?php
                            $sql="SELECT * FROM users where user_id='$user_id'";
                            $profile=mysqli_query($conn,$sql);
                            while($row = mysqli_fetch_assoc($profile)){
                        ?>

                        <div class="detail">
                            <div class="profile-img">
                                <img class='grid-row-span-3' src="assets/img/user.png" id="user-btn" width="30px">
                            </div>
                            <div class="profile-details">
                                <h2><?php echo $_SESSION['username'];?>
                                <h2><img src="assets/img/icons8-telephone-50.png" alt="" style='width:30px;'><?php echo $row['number']; ?></h2>
                                <p><img src="assets/img/icons8-home-50.png" alt="" style='width:30px;'><?php echo $row['address']; ?></p>
                            </div>

                        </div>

                        <button type="button" id="add-address-btn" ><img src="assets/img/icons8-plus-24.png" alt="">Add Address</button>
                            
                        <div class="address">
                            <div class="input-field">
                                <p>Recipient's Name<sup>*</sup></p>
                                <input type="text" name="name" placeholder="Enter Your Address" class='input'>
                            </div>
                            <div class="address-detail">
                                <div class="input-field phone">
                                    <p>Phone Number<sup>*</sup></p>
                                    <input type="text" name="number" placeholder="Enter Your Address" class='input'>
                                </div>
                                <div class="add_cat">
                                    <p>Address category<sup></sup></p>
                                    <select name="method" class="cat-box">
                                        <option value="Home">Home</option>
                                        <option value="Office">Office</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="address-detail">
                                <div class="input-field region">
                                    <p>Region/District<sup>*</sup></p>
                                    <input type="text" name="district" placeholder="Dhaka/Chittagong/Khulna/Sylhet" class='input'>
                                </div>
                                <div class="input-field city">
                                    <p>City<sup>*</sup></p>
                                    <input type="text" name="city" placeholder="Gazipur/Savar/Tongi/..." class='input'>
                                </div>
                            </div>
                            <div class="input-field">
                                <p>Address<sup>*</sup></p>
                                <input type="text" name="number" placeholder="House no./building/street/area" class='input'>
                            </div>
                            

                            <button type="submit" name="edit_address" class='add_sav'>Save</button>

                        </div>
                        <?php 
                            }
                        ?>
                        

                    </form>



                </div>
                <form method='post' class='payment-form'>
                    <h1 class=title>Payment Details</h1>

                    <input type="hidden" name="method" id="payment-method" value="Cash On Delivery">

                    <div class="pay_method">
                        <button type='button' class='method' onclick="selectMethod(this, 'Cash On Delivery')">
                            <span>Cash On Delivary</span>
                            <ion-icon class='checkmark fill' name="radio-button-off-outline" ></ion-icon>
                        </button>
                        <button type='button' class='method' onclick="selectMethod(this, 'Credit or debit card')">
                            <span>Credit or debit card</span>
                            <ion-icon class='checkmark' name="radio-button-off-outline"></ion-icon>
                        </button>
                        <button type='button' class='method' onclick="selectMethod(this, 'Net Banking')">
                            <span>Net Banking</span>
                            <ion-icon class='checkmark' name="radio-button-off-outline"></ion-icon>
                        </button>
                    </div>

                    <div class="card_details">
                        <div class="cardholder-name">
                            <label for="carholder-name" class='label-default'>Cardholder name</label>
                            <input type="text" name="card_name" placeholder="Mr john karim" class='input-default'>
                        </div>
                        <div class="card-number">
                            <label for="card-number" class='label-default'>Card number</label>
                            <input type="text" name="card_number" placeholder="Enter Your Number" class='input-default'>
                        </div>
                        <div class="input-flex">

                            <div class="expire-date">
                                <label for="expire-date" class='label-default'>Expiration date</label>

                                <input type="text" name="day" placeholder="01/01/24" class='input-default'>
                            </div>
                            <div class="cvv">
                                <label for="cvv" class='label-default'>CVV</label>
                                <input type="text" name="cvv" placeholder="1234" class='input-default'>
                            </div>
                        </div>

                    </div>
                    <div class="total">
                        <div class="total_quantity">
                            <div >Total Quantity</div>
                            <div>
                                <span id="total-quantity">
                                <?php
                                    $sql2 = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = '$user_id'";
                                    $total = mysqli_query($conn, $sql2);
                                    if ($total) {
                                        $price = mysqli_fetch_assoc($total);
                                        echo $price['total_quantity'];
                                    } else {
                                        echo "0";
                                    }
                                ?>
                                </span>
                            </div>


                        </div>
                        <div class="total_price">
                            <div>
                                TOTAL Price
                            </div>
                            <div>
                                <span id="total-price">
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

                        </div>



                    </div>

                    <hr class='line'>

                    <button type="submit" name="place-order" class=pay-btn>Place Order</button>
                </form>





            </div>





                            



                                    



        </section>

       





   


    </main>

    <!-- <script>
        $(document).ready(function(){
            $('.wish-btn').click(function(e){
                e.preventDefault();
                var product_id = $(this).data('product-id');
                var product_price = $(this).data('product-price');
                
                $.ajax({
                    url: 'cart.php',
                    type: 'POST',
                    data: {
                        add_to_wishlist: true,
                        product_id: product_id,
                        product_price: product_price
                    },
                    success: function(response){
                        var res = JSON.parse(response);
                        alert(res.message);
                    }
                });
            });

            $('.addcart').click(function(e){
                e.preventDefault();
                var product_id = $(this).data('product-id');
                var product_price = $(this).data('product-price');
                
                $.ajax({
                    url: 'cart.php',
                    type: 'POST',
                    data: {
                        add_to_cart: true,
                        product_id: product_id,
                        product_price: product_price
                    },
                    success: function(response){
                        var res = JSON.parse(response);
                        alert(res.message);
                    }
                });
            });
        });
    </script> -->




    <script src="script.js"></script>

    <script>
        let addaddress=document.querySelector('#add-address-btn');

        addaddress.addEventListener('click',function(){
        let addressbox=document.querySelector('.address');
        addressbox.classList.toggle('active');
        })


        function selectMethod(element, method) {
            // Remove the selected class from all buttons
            document.querySelectorAll('.pay_method .method').forEach(function(button) {
                button.classList.remove('selected');
            });

            // Add the selected class to the clicked button
            element.classList.add('selected');

            // Update the hidden input value
            document.getElementById('method_input').value = method;
        }


        function selectMethod(button,method){
            const buttons = document.querySelectorAll('.pay_method .method');
            buttons.forEach(btn => {
            btn.classList.remove('selected');
            const icon = btn.querySelector('ion-icon');
            icon.setAttribute('name', 'radio-button-off-outline');
            });
            button.classList.add('selected');
            const icon=button.querySelector('ion-icon');
            icon.setAttribute('name',"radio-button-on-outline");

            document.getElementById('payment-method').value = method;
            const cardDetails = document.querySelector('.card_details');
            if (method === 'Credit or debit card' || method === 'Net Banking') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }

        }
        document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('.card_details').style.display = 'none';
        });
    </script>

    <?php include('alert.php'); ?>

    <?php include ('footer.php')?>
    
</body>
</html>



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
                $sql = "SELECT * FROM farmer WHERE farmer_id='$user_id'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                $name = $_SESSION['username'];
                $number = $row['number'];
                $address = $row['address1'];
                $address_type ='Home';
        }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INAR-Order page</title>
    <link rel='stylesheet' href='product.css'>
</head>
<body>
    <?php include('header.php'); ?>
    <?php include 'shopping_cart.php' ?>

    <main>
        <div class="product_first">
            <div class="product_b">
                <img src="assets/img/Banner-3.jpg" alt="">
                <h1 class='p-title'>My Order</h1>
            </div>

            
        </div>
        <section class='shop_order'>
            <div class="box-container">
                <?php 
                    $sql="SELECT * FROM order WHERE user_id='$user_id' ORDER BY date DESC";
                    $result=mysqli_query($conn,$sql);
                    
                    if(mysqli_num_rows($result)>0){
                        while($order = mysqli_fetch_assoc($result)) {
                            if(mysqli_num_rows($order)>0){
                                $order_id=$order['order_id'];
                                $order_sql="SELECT * FROM order_items WHERE order_id='$order_id'";
                                $order_result=mysqli_query($conn,$order_sql);
                                while($order = mysqli_fetch_assoc($order_result)) {
                ?>
                <div class=""></div>






                <?php

                                }
                            }
                        }
                    }
                ?>

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
    </script>

    <?php include('alert.php'); ?>

    <?php include ('footer.php')?>
    
</body>
</html>



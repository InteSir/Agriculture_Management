<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include('connection.php');


if (isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
    }else{
        $user_id=2;
    }
    if (isset($_POST['logout'])){
        session_destroy();
        header('location: login.php');
    }
    //adding products in wishlist
    if (isset($_POST['add_to_wishlist'])){
        if ($user_id==2){
            header("Location:login.php");
            exit();
        }else{
            $product_id=$_POST['product_id'];
            $product_price=$_POST['product_price'];
    
    
            $sql_wish = "SELECT product_id,user_id FROM wishlist WHERE product_id = '$product_id' and user_id = '$user_id'";
    
        
    
            $result1 = mysqli_query($conn,$sql_wish);
            
    
            if (mysqli_num_rows($result1) == 1){
                $warning_msg[]='product already exist in whislist';
            
            }else{
                
    
                $sql = "INSERT INTO wishlist (user_id,product_id,price) values ('$user_id','$product_id',$product_price)";
    
                mysqli_query($conn,$sql);
    
                $success_msg[]='Product added to wishlist successfully';
              
    
            }
        }
        
       

    }
    
    //adding products in cart

    if (isset($_POST['add_to_cart'])){
        


        if ($user_id==2){
            header("Location:login.php");
            exit();
        }else{
            $product_id=$_POST['product_id'];
            $product_price=$_POST['product_price'];
    
    
            $qty=1;
    
    
            $sql_cart = "SELECT product_id,user_id FROM cart WHERE product_id = '$product_id' and user_id = '$user_id'";
    
            $result= mysqli_query($conn,$sql_cart);
            
    
            if (mysqli_num_rows($result) == 1){
                $warning_msg[]= 'product already exist in  cart';
            
            }else{
    
                $sql = "INSERT INTO cart (user_id,product_id,price,quantity) values ('$user_id','$product_id','$product_price','$qty')";
    
                mysqli_query($conn,$sql);
    
                $success_msg[]='Product added to cart successfully';
    
            }
    
        }

        }
       

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <?php include('header.php'); ?>
    <?php include('shopping_cart.php'); ?>

    <main>
        <div class="product_first">
            <div class="product_b">
                <img src="assets/img/Banner-3.jpg" alt="">
                <h1 class='p-title'>Product</h1>
            </div>
            
        </div>
        <div class="container">
            <div class="cart-header">
                <h1>PRODUCT LIST</h1>
                <form method="post" class="side-body" id="searchForm">
                    <div class="searchbar">
                        <input placeholder="Search..." name="Searchtext" id="searchbar" type="text">
                        <button type="submit" id="search_submit" style="background: none; border: none; padding: 0; cursor: pointer;">
                            <img src="assets/img/icons8-search-50 (1).png" alt="Search">
                        </button>
                    </div>
                    <div id="filter_btn">
                        <button type="button" data-category="all" class="button-value active filter_btn">ALL</button>
                        <button type="button" data-category="fruit" class="button-value filter_btn">Fruits</button>
                        <button type="button" data-category="vegetable" class="button-value filter_btn">Vegetable</button>
                        <button type="button" data-category="meat" class="button-value filter_btn">Meat</button>
                        <button type="button" data-category="fish" class="button-value filter_btn">Fish</button>
                    </div>
                </form>
            </div>

            <div class="listProduct" id="product_list">
                <!-- Products will be dynamically loaded here -->
            </div>
        </div>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // $(document).on('click', '.action-btn', function(e) {
            //     e.preventDefault();

            //     var action = $(this).data('action');
            //     var product_id = $(this).data('product-id');

            //     $.ajax({
            //         url: 'action_handler.php',
            //         type: 'POST',
            //         data: {
            //             action: action,
            //             product_id: product_id

            //         },
            //         success: function(response) {
            //             var data = JSON.parse(response);
            //             if (data.success) {
            //                 updateCartDisplay(); // Update the cart display
            //                 alert('Product added to cart successfully!');
            //             } else {
            //                 alert('An error occurred: ' + data.message);
            //             }
            //             // try{
            //             //     var res = JSON.parse(response);
            //             //     if(res.success) {
            //             //     alert(res.message);
            //             //     } else {
            //             //         alert("An error occurred: " + res.message);

            //             //      }
            //             // } catch(e) {
            //             //     alert("Failed to process the response from the server.");
            //             // }

            //             // alert(res.message);
            //         },
            //         error: function(xhr, status, error) {
            //         alert("An AJAX error occurred: " + error);
            //         }
            //     });
            // });
            // function updateCartDisplay() {
            //     $.ajax({
            //         url: 'shopping_cart.php',
            //         type: 'POST',
            //         success: function(response) {
            //             $('#cart_display').html(response);
            //         }
            //     });
            // }

            // Function to fetch products
            function fetchProducts(searchtext = '', category = 'all') {
                $.ajax({
                    url: 'fetch_products.php',
                    type: 'POST',
                    data: {
                        Searchtext: searchtext,
                        category: category
                    },
                    success: function(response) {
                        $('#product_list').html(response);
                    }
                });
            }

            // Load all products on page load
            fetchProducts();

            // Handle search form submission
            $('#searchForm').submit(function(e) {
                e.preventDefault();
                var searchtext = $('#searchbar').val();
                fetchProducts(searchtext);
            });

            // Handle category filter buttons
            $('.filter_btn').click(function() {
                var category = $(this).data('category');
                fetchProducts('', category);
                $('.filter_btn').removeClass('active');
                $(this).addClass('active');
            });
        });


    </script>
   

    <?php include('footer.php'); ?>
    <?php include('alert.php'); ?>
</body>
</html>

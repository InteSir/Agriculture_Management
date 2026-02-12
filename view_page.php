
<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include('connection.php');


if (isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
    }else{
        $user_id=2;
    }
    $sql='SELECT * FROM product';
    $all_product=mysqli_query($conn,$sql);

    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $qty = 1;

        $sql = "SELECT price FROM product WHERE product_id='$product_id'";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        $product_price = $row['price'];

        $check = mysqli_query($conn,
            "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'"
        );

        if (mysqli_num_rows($check) == 0) {
            mysqli_query($conn,
                "INSERT INTO cart (user_id,product_id,price,quantity)
                VALUES ('$user_id','$product_id','$product_price','$qty')"
            );
        }
    }





?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel='stylesheet' href='product.css'>
</head>

<body>
    <?php include('header.php'); ?>
     <?php include('shopping_cart.php'); ?>
    <main>
        <div class="product_first">
            <div class="product_b">
                <img src="assets/img/Banner-3.jpg" alt="">
                <h1 class='p-title'>Product Details</h1>
            </div>
            
        </div>
        <section class="view_body">

            <div class='view_page'>
 
                <div class='view_contain'>
                    <?php 
                        if(isset($_GET['pid'])){
                            $pid=$_GET['pid'];
                            $sql="SELECT * from product where product_id='$pid'";
                            $select_products=mysqli_query($conn,$sql);

                            $sql2="SELECT * from product where category = (select category from product where product_id='$pid');";
                            $recom_products=mysqli_query($conn,$sql2);

                            if(mysqli_num_rows($select_products)>0){
                                while($row = mysqli_fetch_assoc($select_products)){ 
                    ?>
                    <form action=""  class='view_detail' method='post'>
                        <img src="assets/product_img/<?php echo $row["image1"];?>.png" alt="">
                        <div class="view_content">
                            <div class="name"><?php echo $row["name"];?><span>(<?php echo $row["quantity"];?>kg)</span></div>
                            <div class="price"><?php echo $row["price"];?>Tk</div>
                            

                            <div class="button">
                                <button type='submit' class="btn" name='add_to_wishlist'>Add to wishlist<span><img src="assets/img/love.png" class="whish-list" width="30px"></span></button>
                                <input type="hidden" name='qty' value='1' min='0' class='quantity'>
                                <button type='submit' class="btn" name='add_to_cart'>Add to Cart
                                        <span class="car-s"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <div class="view_description">

                                <p><?php echo $row["details"];?></p>
                            </div>
                            <input type="hidden" name='product_id' value='<?php echo $row["product_id"];?>'>
                        
                                
                        </div>
                        
                    </form>




                    <?php
                                }

                            }
                        }
                    
                    ?>
                </div> 
                <div class="view_title">SIMILAR PRODUCTS</div>
                <div class="container">
                    <div class="listProduct">
                        <?php 
                            while($row = mysqli_fetch_assoc($recom_products)){                
                        ?>

                        <form action="" method='post' class="item">
                                <a href="view_page.php?pid=<?php echo $row["product_id"];  ?>"><img src="assets/product_img/<?php echo $row["image1"];?>.png" alt=""></a>
                            
                            <div class="con">
                                    <h2><?php echo $row["name"];?><span>(<?php echo $row["quantity"];?>kg)</span></h2>
   



                            </div>

                            
                            <input type="hidden" name='product_id' value='<?php echo $row["product_id"];?>'>
                            
                        
                            
                            <div class="price"><?php echo $row["price"];?>Tk</div>
                            <input type="hidden" name='product_price' value='<?php echo $row["price"];?>'>

                            <button class="addcart" name='add_to_cart'>Add to Cart</button>

                            
                            
                            

                        </form>
                        <?php 
                                }
                                
                        ?>

                    </div>

                </div>
                
            
            </div>            
        </section> 

    </main>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="script.js"></script>
    <?php include 'footer.php'?>
    <?php include('alert.php'); ?>
</body>
</html>
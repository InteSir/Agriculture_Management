
<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include('connection.php');





if (isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
}else{
    $user_id="";

}





$sql="SELECT * from wishlist where user_id='$user_id'";
$all_wish=mysqli_query($conn,$sql);


    
if (isset($_POST['add_to_cart'])){
    
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

// delete intem
if (isset($_POST['delete_item'])){
    $wishlist_id=$_POST['wishlist_id'];
    $sql="SELECT * FROM wishlist where id='$wishlist_id'";
    $varify=mysqli_query($conn,$sql);

    if(mysqli_num_rows($varify)>0){
        $sql2="DELETE FROM wishlist where id='$wishlist_id'";
        $result=mysqli_query($conn,$sql2);
        $success_msg[]='wishlist item delete successfully';
    }else{
        $warning_msg[]='wishlist item already deleted';
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
    <?php include 'shopping_cart.php' ?>
    <main>
        <div class="product_first">
                <div class="product_b">
                    <img src="assets/img/Banner-3.jpg" alt="">
                    <h1 class='p-title'>My Wishlist</h1>
                </div>
                
        </div>
        <section class="wish_body">
            <div class='wish_page'>               
                <div class='wish_grid'>           
                    
                        <?php 
                            if(mysqli_num_rows($all_wish)>0){
                                while($wis = mysqli_fetch_assoc($all_wish)){ 
                                    $product_id=$wis['product_id'];
                                    $sql="SELECT * from product where product_id='$product_id'";
                                    $all_product=mysqli_query($conn,$sql);
                                    while($row = mysqli_fetch_assoc($all_product)){
                        ?>
                        <form action=""  class='wish_card' method='post'>
                            <input type="hidden" name='wis_id' value='<?php echo $wis["id"];?>'>

                            <a href="view_page.php?pid=<?php echo $row["product_id"];  ?>" class="wish_image"><img src="assets/product_img/<?php echo $row["image1"];?>.png" alt=""></a>


                            <div class="wish_content">
                           
                                <div class="wish_name"><?php echo $row["name"];?><span>(<?php echo $row["quantity"];?>kg)</span></div>

                                <div class="wish_description">
                                    <p><?php echo $row["details"];?></p>
                                </div>
                                <div class="wish_price"><?php echo $row["price"];?>Tk</div>  
                                <div class="wish_actions">
                                    <button type='submit' class="btn_cart" name='add_to_cart'>Add to Cart
                                        <ion-icon name="cart"></ion-icon>
                                    </button>

                                    <button type='submit' name='delete_item' onclick="return confirm('delete this item');" class="btn_delete" > <img src="assets/img/bin.png"  alt="Delete"></button> 
                                </div>   

                            </div>
                   
                        </form>
                        <?php
                                    }
                                }
                            }else{
                                echo "<p class='empty'>no products added yet!</p>";

                            }
                        
                        ?>

                </div> 
            
            
            </div>

            
              
            
       
           
        </section> 

    </main>
    

    
    <script>

    </script>
    <?php include 'footer.php'?>
</body>
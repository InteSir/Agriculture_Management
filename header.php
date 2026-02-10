<?php
    include('connection.php');
    session_start();



    
    $link = '';

    if (isset($_SESSION['user_id'])){
            $user_id=$_SESSION['user_id'];



            if ($_SESSION['user_type'] == 'farmer') {
                $link = 'farmer.php';
            } else {
                $link = 'customer.php';
            }
        

            $sql="Select * from wishlist where user_id='$user_id'";
            $wishlist = mysqli_query($conn,$sql);
            $count_wishlist=mysqli_num_rows($wishlist);


            $sql2="Select * from cart where user_id=$user_id";
            $result = mysqli_query($conn,$sql2);
            $count_cart=mysqli_num_rows($result);


        
    }else{
        $user_id='';
        $link='login.php';
        $count_wishlist=0;
        $count_cart=0;
    }
    

    $sql1="SELECT * FROM product WHERE product_id IN (SELECT product_id FROM cart WHERE user_id = '$user_id')";
    $all_cart=mysqli_query($conn,$sql1);

    $sql2="SELECT SUM(price) as total_price from cart where user_id = '$user_id'";
    $total=mysqli_query($conn,$sql2);

   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  
</head>
<body>
    <header>
        <div class="nav">
            <a href="home.php"><img src='./assets/img/logo.png'   class="logo"/></a>
            <div class="navbar">
                <a href="home.php">Home</a>
                <!-- <a href="">About</a> -->
                <a href="#contact-section">Contact</a>
                <a href="product.php">Products</a>

                <?php if (isset($_SESSION['username'])){
                        echo "<a href='checkout.php'>Orders</a>";
                        $sql = "SELECT user_type FROM users WHERE fullname='" . mysqli_real_escape_string($conn, $_SESSION['username']) . "'";
                        $all = mysqli_query($conn, $sql);

                        if ($row = mysqli_fetch_assoc($all)) {
                            // Checking the user_type
                            if ($row['user_type'] == 'farmer') {
                                echo "<a href='farmer.php'>Dashboard</a>";
                            } else {
                                echo "<a href='customer.php'>Profile</a>";
                            }
                        } else {
                            echo "User not found.";
                        }
                        
                    }else{
                        echo "<a href='Login.php'>Sign Now</a>";
                    }
                ?>
                
                
            </div>
            <div class="icon">
                <img src="assets/img/user.png" id="user-btn" width="30px">



                <a href="wishlist.php"><img src="assets/img/love.png" id="whish-list" width="30px"></a>
                <span class='w-span'><?php echo $count_wishlist;?></span>


                
                
    
                  
                <svg aria-hidden="true" class='icon-cart'   xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1"/>
                </svg>
              
                <span class='c-span'><?php echo $count_cart?></span>
                
            </div>
            <div class="user-box">
                <p>Username :  <span>
                    <?php 
                        if (isset($_SESSION['username'])){
                            echo $_SESSION['username'];
                        }else{
                            echo '';

                        }
                     ?>
                    </span></p>
                <p>Email :  <span>
                <?php 
                    if (isset($_SESSION['email'])){
                        echo $_SESSION['email'];
                    }else{
                        echo '';

                    }
                ?></span></p>
                <?php
                    if(isset($_SESSION['username'])){
                        echo '<form method="post">
                        
                               <a href="logout.php" name="logout" class="logout-btn">Log Out</a>
                            </form>';
                    }else{
                        echo '<a href="login.php" class="sign_btn">Login</a>';
                        echo '<a href="register.php" class="sign_btn">Register</a>';
                    }
                
                ?>
                

            </div>
            <div class="navbar_toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>

        </div>       
    </header>
    
    <script>
        let userbtn=document.querySelector('#user-btn');

        userbtn.addEventListener('click',function(){
            let userbox=document.querySelector('.user-box');

            if (userbox) {
                userbox.classList.toggle('active');
            }

            
        })

        const mobileMenu = document.querySelector('#mobile-menu');
        const navbar = document.querySelector('.navbar');

        mobileMenu.addEventListener('click', () => {
            mobileMenu.classList.toggle('is-active');
            navbar.classList.toggle('active');
        });
        




        let iconCart=document.querySelector('.icon-cart');
        let body = document.querySelector('body');
        let closeCart=document.querySelector('.close');

        iconCart.addEventListener('click',()=>{
            body.classList.toggle('showCart')
        })
        closeCart.addEventListener('click',()=>{
            body.classList.toggle('showCart')

        })
    </script>


   
</body>
</html>
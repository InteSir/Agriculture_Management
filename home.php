<?php 

include('connection.php');
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if (isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
    }else{
        $user_id='';
    }
    if (isset($_POST['logout'])){
        session_destroy();
        header('location:login.php');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['submit-btn'])){
            $name=$_POST['name'];
            $number=$_POST['number'];
            $email=$_POST['email'];
            $subject=$_POST['subject'];
            $message=$_POST['message'];

            if (isset($_SESSION['user_id'])){
                $sql="INSERT INTO `query` (user_id,name,number,email,subject,message) VALUES ('$user_id','$name','$number','$email','$subject','$message')";

                 $result = mysqli_query($conn,$sql);
                 $success_msg[]='Your messagehas been sent';
            }else{
                header('location:login.php');
            }
        }

    }

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
    <?php include 'header.php'?>
    <?php include 'shopping_cart.php' ?>



    <div class="slider">
        <div class="list">
            <div class="item active">
                <img src="assets/img/pexels-quang-nguyen-vinh-222549-2165688.jpg" alt="">
                <div class="content">
                    <p>Farmers Market</p>
                    <h2>100% Organic Products</h2>
                    <p>Grown with care, harvested with honesty — 100% organic products you can trust.Healthy soil, healthy crops, healthy you.</p>
                </div>

            </div>
            <div class="item">
                <img src="assets/img/pexels-mongkon-duangkhiew-2290728-8703373.jpg" alt="">
                <div class="content">
                    <p>Farmers Market</p>
                    <h2>The Heart of the Harvest</h2>
                    <p>This image showcases dedicated farmers harvesting rice in lush green fields. It represents the hard work and dedication that goes into providing fresh and quality produce directly from the farm to your table.</p>
                </div>

            </div>
            <div class="item">
                <img src="assets/img/pexels-quang-nguyen-vinh-222549-2153824.jpg" alt="">
                <div class="content">
                    <p>Farmers Market</p>
                    <h2>From Nature to You</h2>
                    <p>Dedicated farmers harvest fresh produce with care and tradition, ensuring natural quality from the field to your table.Every harvest reflects commitment, care, and respect for the land—bringing you fresh, high-quality produce directly from trusted farms.</p>
                </div>

            </div>
            <div class="item">
                <img src="assets/img/pexels-nany-casteleira-1855908-12593928.jpg" alt="">
                <div class="content">
                    <p>Farmers Market</p>
                    <h2>Vineyards in Bloom</h2>
                    <p>A vibrant vineyard, symbolizing the diversity of produce available. From fresh grapes to a variety of fruits and vegetables, our platform connects you with the finest farm-fresh products.</p>
                </div>

            </div>
            <div class="item">
                <img src="assets/img/pexels-quang-nguyen-vinh-222549-2582652.jpg" alt="">
                <div class="content">
                    <p>Farmers Market</p>
                    <h2>Verdant Tea Fields</h2>
                    <p>This image of lush tea fields at dawn highlights the freshness and purity of the products offered. Experience the essence of nature in every sip and bite, sourced directly from the heart of the farm.</p>
                </div>

            </div>
            <div class="item">
                <img src="assets/img/pexels-pixabay-462119.jpg" alt="">
                <div class="content">
                    <p>Farmers Market</p>
                    <h2>Pastoral Serenity</h2>
                    <p>A peaceful scene of sheep grazing under a beautiful sunset, emphasizing sustainable and organic farming practices. Our commitment to eco-friendly methods ensures that you receive high-quality, natural products</p>
                </div>

            </div>
            

        </div>

        <div class="thumbnail">
            <button id="prev" class="pr"><</button>
            <div class="item active"></div>
            <div class="item">
            </div>
            <div class="item">
            </div>
            <div class="item">
            </div>
            <div class="item">
            </div>
            <div class="item">
            </div>
            <button id="next" class="nx">></button>
        </div>

    </div>
    <div class="blank">
        <div class="item">
            <h1>2800</h1>
            <h4>PRODUCTS</h4>
        </div>
        <div class="item">
            <h1>2800</h1>
            <h4>USERS</h4>
        </div>
        <div class="item">
            <h1>2800</h1>
            <h4>FARMERS</h4>
        </div>
        <div class="item">
            <h1>2800</h1>
            <h4>ORDERS</h4>
        </div>
    </div>
    <about class='about'>
        <h1 class='about_header autoshow'>WHY CHOOSE US?</h1>
        <div class="about_content">
            <div class="about_grid">
                <div class="item ">
                    <h3>1.</h3>
                    <h4>100% organic foods</h4>
                    <p>We offer only the finest organic products, free from chemicals and harmful additives.</p>
                </div>
                <div class="item ">
                    <h3>2.</h3>
                    <h4>secure Payment</h4>
                    <p>Your transactions are safe and protected with our advanced encryption technology.</p>
                </div>
                <div class="item ">
                    <h3>3.</h3>
                    <h4>Free delivary</h4>
                    <p>Enjoy hassle-free delivery on all your orders, right to your doorstep</p>
                </div>
                <div class="item ">
                    <h3>4.</h3>
                    <h4>24/7 support</h4>
                    <p>Our customer support is available around the clock to assist you with any queries.</p>
                </div>
            </div>
            <div class="about_img">
                <div class="img_wrapper">
                    
                    <img src="assets/img/side-view-hands-with-gloves-holding-soil-plant.jpg" alt="">

                </div>

                
                <button class="about_btn"><a href="about.php">Learn More</a></button>
            </div>
        </div>


    </about>
     <!-- <div class="product_grid">
        <div class="title">
            <h1>Trending Products</h1>
        </div>

        <div class="box grid-row-span-2">
            <img src="assets/img/g-1.jpg" alt="">
            <div class="detail">
                <span>Shop Your Daily necessities</span>
            </div>
        </div>
        <div class="box-container">
            <div class="box">
                <img src="assets/img/g-2.jpg" alt="">
                    <span>BIG OFFERS</span>
                    <h1>Extra 15% off</h1>
                    <a href="product.php">Shop now</a>

            </div>
            <div class="box">
                <img src="assets/img/g-2.jpg" alt="">
                    <span>BIG OFFERS</span>
                    <h1>Extra 15% off</h1>
                    <a href="product.php">Shop now</a>

            </div>

        </div>




    </div> -->
    <div id="contact-section" class="contact_page">
        <div class="contact-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29206.376647919064!2d90.38979251848453!3d23.790239202580914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7a0f70deb73%3A0x30c36498f90fe23!2sGulshan%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1726055143520!5m2!1sen!2sbd" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        
        <div class="query">
            <div class="Get_in_touch">
                <h1>Get in Touch</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam veritatis eligendi, mollitia facere earum assumenda ratione dolor accusamus quisquam incidunt doloremque et quidem labore at quis? Minima amet ipsa animi.</p>
                <ul>
                    <li><ion-icon name="home"></ion-icon>Gulshan1,road-6,Dhaka.</li>
                    <li><ion-icon name="call"></ion-icon>+8801783716677</li>
                    <li><ion-icon name="mail"></ion-icon>arnar@gmail.com</li>
                    <li><ion-icon name="logo-facebook"></ion-icon>farmers_market.com</li>
                </ul> 
            </div>
            <form method="post"  class="message">
             
                        <div class="title">                  
                            <h1>Send us a message</h1>
                        </div>
                        <div class="contact_input">
                             <div class="input-field">

                                <input type="text" name="name" placeholder="your name">
                            </div>
                            <div class="input-field">

                                <input type="email" name="email" placeholder="Email">
                            </div>
                            <div class="input-field">

                                <input type="text" name="number" placeholder="Phone">
                            </div>
                            <div class="input-field">

                                <input type="text" name="subject" placeholder="Subject">
                            </div>

                        </div>
                        <div class="input-field">

                            <textarea class='textarea' name="message"></textarea>
                        </div>
                        <button type="submit" name="submit-btn" class=btn>Send message</button>
    

            </form>
        </div>

    </div>



   
    <!-- <div class="comment">
        <h1>What People Say</h1>
    </div> -->







   

    
    
        
    
    <script src='app.js'></script>
 
    <script src="script.js"></script>
    <?php include('alert.php'); ?>
    <?php include ('footer.php')?>


</body>
</html>





<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include('connection.php');
?>





<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
<?php include('header.php'); ?>

<section class="admin-dashboard">

      <h1 class="title">Admin dashboard</h1>

      <div class="dashboard-container">
            <div class="box">
                  <?php
                  $total_customer = 0;
                  $select ="SELECT count(*) as number FROM `users` WHERE user_type = 'customer'";
                  $result = mysqli_query($conn,$select);
                  $row = mysqli_fetch_assoc($result);
                  $total_customer += $row['number'];
                  ?>
                  <h3>Total Customers</h3>
                  <h3><?= $total_customer; ?></h3>
                  <button type="button" id="see_customer" >See Customers</button>
            </div>

            <div class="box">
                  <?php
                  $total_farmer = 0;
                  $select ="SELECT count(*) as number FROM `users` WHERE user_type = 'farmer'";
                  $result = mysqli_query($conn,$select);
                  $row = mysqli_fetch_assoc($result);
                  $total_farmer += $row['number'];
                  ?>
                  <h3>Total Farmers</h3>
                  <h3><?= $total_farmer; ?></h3>
                  <button type="button" id="see_customer" >See Farmers</button>
            </div>


            <div class="box">
                  <?php
                  $total_products = 0;
                  $select ="SELECT count(*) as number FROM `product`";
                  $result = mysqli_query($conn,$select);
                  $row = mysqli_fetch_assoc($result);
                  $total_products += $row['number'];
                  ?>
                  <h3>Total Products</h3>
                  <h3><?= $total_products; ?></h3>
                  <button type="button" id="see_customer" >See Products</button>
            </div>
            <div class="box">
                  <?php
                  $total_order = 0;
                  $select ="SELECT count(*) as number FROM `order`";
                  $result = mysqli_query($conn,$select);
                  $row = mysqli_fetch_assoc($result);
                  $total_order += $row['number'];
                  ?>
                  <h3>Order Placed </h3>
                  <h3><?= $total_order; ?></h3>
                  <button type="button" id="see_customer" >See Orders</button>
            </div>
      </div>

      <div class="admin_message">
            
            <h2>Query</h2>
            <div class="updates">

                  <?php 
                        $sql='Select * from query';
                        $result=mysqli_query($conn,$sql);
                        if ($result->num_rows > 0) {
                        while($row = mysqli_fetch_assoc($result)){                         
                  ?>
                  <div class="user">
                        <div class="user-img">
                              <img class='grid-row-span-3' src="assets/img/user.png" id="user-btn" width="30px">
                        </div>
                        <div class="message_details">
                              <h2><?php echo $row["name"];?></h3>
                              <h3>Subject:  <?php echo $row["subject"];?></h3>
                              <div class="message">
                                    <p><?php echo $row["message"];?></p>
                              </div>
                        </div>

                  </div>
                  <?php }}
                  ?>

            </div>
      </div>

</body>
   
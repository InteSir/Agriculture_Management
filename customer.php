<?php 
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    include('connection.php');



    if (isset($_SESSION['user_id'])){
        $user_id=$_SESSION['user_id'];
        $user_name=$_SESSION['username'];
        }else{
            $user_id='';
            $user_name='';
    
        }
        if (isset($_POST['logout'])){
            session_destroy();
            header('location: login.php');
        }
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include 'header.php'?>
    <main>
        <div class='wrapper'>
            <div class="user_title" style='margin-top:10rem;'>
                <h1>Welcome    <span><?php echo $user_name?></span></h1>
            </div>
            
            <?php
                    $sql="SELECT * FROM users where user_id='$user_id'";
                    $profile=mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($profile)){
            ?>
            <div class="user_welcome active">
                <div class="user_profile">
                        <div class="detail">
                            <div class="profile-img">
                                <img class='grid-row-span-3' src="assets/img/user.png" id="user-btn" width="30px">
                            </div>
                            <div class="profile-details">
                                <h2><?php echo $_SESSION['username'];?>
                                <p><ion-icon name="call" style='margin:auto;'></ion-icon><?php echo $row['number']; ?></p>
                                <p><ion-icon name="mail" style='margin:auto;'></ion-icon></ion-icon><?php echo $row['email']; ?></p>
                                <p><ion-icon name="home" style='margin:auto;'></ion-icon><?php echo $row['address']; ?></p>
                            </div>
                            <button type="button" id="edit-btn" ><ion-icon style="font-size: 36px;" name="create-outline"></button>
                        </div>                                  
                </div>
                <div class="dashboard">
                    <div class="edit-profile">
                        <div class='input-box'>
                            <label for="username" class='label-default'>UserName</label>
                            <input type="text"  class='input-default' placeholder=<?php echo $_SESSION['username'];?>  name='fullname'>

                        </div>
                        <div class='input-box'>
                            <label for="email" class='label-default'>Email</label>
                            <input type="text" class='input-default'  placeholder=<?php echo $row['email']; ?>  name='email'>

                        </div>
                        <div class='input-box'>
                            <label for="number" class='label-default'>Phone Number</label>
                            <input type="text" class='input-default' placeholder=<?php echo $row['number']; ?>  name='number'>

                        </div>
                        <div class='input-box'>
                            <label for="address" class='label-default'>Address</label>
                            <input type="text"  class='input-default' placeholder=<?php echo $row['address']; ?> name='address'>
                        </div>
                        <button type="submit" name="edit_pr" class='edit_pr'>Save</button>
                    </div>
                </div> 

                

            </div>

            <?php 
                        }
            ?>
        </div>
    </main>
    <script>        
        let editbtn=document.querySelector('#edit-btn');


        editbtn.addEventListener('click',function(){
            let editprof=document.querySelector('.edit-profile');
            editprof.classList.toggle('active');
        })

      
    </script>

</body>

</html>
<?php
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    include('connection.php');
    
 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        

        $sql='SELECT email from admin where id=1';
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        if ($row && $row['email'] == $email) {
            // If the email matches the admin email, redirect to admin page
            header("Location: admin.php");
            exit();
        }else{
            $sql2 = "SELECT user_id,fullname,user_type,email,password FROM users WHERE email = '$email'";
            $result2 = mysqli_query($conn,$sql2);
    
            if (mysqli_num_rows($result2) == 1) {
                $row = mysqli_fetch_assoc($result2);;
                
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username']=$row['fullname'];
                $_SESSION['user_type']=$row['user_type'];
                $_SESSION['email']=$row['email'];
    
                $success_msg[]= 'YOU ARE SUCCESSFULY LOGGED IN';
    
                header("Location: home.php");
                exit();
    
            } else {
                $warning_msg[]= "No user found with this email.";
            }
        }


      
    }

    mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Farmer Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'?>
    <div class="log-body">
        <div class='log-container'>
            <img src="assets/img/home-bg.jpg" alt="">


            <div class='log-in'>
                <h1>LOG IN</h1>
                <form method="POST">
                    <div class='input-box'>
                        <input type="email" name="email" placeholder='Enter Your Email' required>
                    </div>
                    <div class='input-box'>
                        <input type="password" name="password" id="password" placeholder="Enter Your Password">
                        <ion-icon name="eye-off-outline" class='eye'></ion-icon>
                    </div>

                    <button type="submit" class='btn'>Login</button>
                    
                    <a href="register.php">Dont have an account?Register Now</a>
                </form>

                
            </div>
        </div>

    </div>
    <script>
        const showPassword = document.querySelector('.eye');
        const passwordInput = document.querySelector('#password');

        showPassword.addEventListener("click",()=>{
            if(passwordInput.type=='password'){
                passwordInput.type='text';
                showPassword.setAttribute('name', 'eye-outline');


            }else{
                passwordInput.type='password';
                showPassword.setAttribute('name', 'eye-off-outline');
                
            }
        });
    </script>
    <?php include('alert.php'); ?>
    <?php include 'footer.php'?>
    

   
</body>    
</html>

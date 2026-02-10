<?php 
    include('connection.php');


    if($_SERVER['REQUEST_METHOD']=='POST'){


        $fullname= $_POST['fullname'];;
        $email=$_POST['email'];
        $number=$_POST['number'];
        $password=$_POST['password'];
        $cpass=$_POST['cpass'];
        $address=$_POST['district'].'/'.$_POST['upazilla'].'/'.$_POST['road_flat'];
        $usertype = $_POST['usertype'];


        $sql = "SELECT email FROM users WHERE email = '$email'";
        $result = mysqli_query($conn,$sql);

        if (mysqli_num_rows($result)>0) {
            $warning_msg[]='email already exist';
        }else{
            if ($password!=$cpass){
                $message[]='confirm your password';
            }else{
                $sql3 = "INSERT INTO users (fullname,email,password,user_type) values ('$fullname','$email','$password','$usertype')";
                mysqli_query($conn,$sql3);
                $success_msg[]='You have successfully created an account';
                header("Location:login.php");
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
</head>
<body>
     <?php include 'header.php'?> 
    <main>
        <div class="register-body">
            <div class="register-container">
                <img src="assets/img/home-bg.jpg" alt="">
                <div class='register-wrapper'>
                    <form action= "<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" >
                    
                    

                        <h1> Register Now </h1>

                        <input type="hidden" name="usertype" id="user_type" value="customer">

                        <div class="usertype">
                            <button type='button' class='type' onclick="selectMethod(this, 'customer')">Customer</button>
                            <button type='button' class='type' onclick="selectMethod(this, 'farmer')">Farmer</button>
                        </div>


                       
                        <?php
                            if (isset($error)){
                                foreach($error as $error){
                                    echo '<span class="error-msg">'.$error.'</span>';
                                }
                            };
                        ?>


                        <div class="flex" style=''>
                            <div class='input-box'>
                                <div class="icon-box">
                                    <ion-icon name="person"></ion-icon>
                                </div>

                                    <input type="text" placeholder="Full Name"   name='fullname'>


                            </div>
                            <!-- <div class='input-box'>
                                <div class="icon-box">
                                    <ion-icon name="person"></ion-icon>
                                </div>


                                <input type="text" placeholder="Last Name"   name='lastname'>

                            </div> -->



                        </div>
                        <div class="flex" style='display:grid;grid-template-columns:repeat(2,1fr);gap:1px'>
                            <div class='input-box' >
                                <div class="icon-box"  >
                                    <ion-icon name="mail" ></ion-icon>
                                </div>
                                <input type="text" placeholder="email"   name='email'>
                            </div>
                            <!-- <div class='input-box'>
                                <div class="icon-box">
                                    <ion-icon name="call"></ion-icon>
                                </div>
                                <input type="text" placeholder="phonenumber"   name='number'>

                             </div> -->

                        </div>
                        <div class="flex" style='display:grid;grid-template-columns:repeat(2,1fr);gap:1px'>
                            <div class='input-box'>
                                <div class="icon-box">
                                    <ion-icon name="lock-closed"></ion-icon>
                                </div>
                                <input type="password" placeholder="Password" name='password'>

                            </div>
                            <div class='input-box'>
                                <div class="icon-box">
                                    <ion-icon name="lock-open"></ion-icon>
                                </div>

                                <input type="password" placeholder="Comfirm Password" name='cpass'>

                            </div>
                        </div>
                        <!-- <div class='input-box'>
                            <div class="icon-box">
                                <ion-icon name="home"></ion-icon>
                            </div>
                            <input type="text" placeholder="District" name='district'>
                            <input type="text" placeholder="Upazilla" name='upazilla'>
                            <input type="text" placeholder="House no./street" name='road_flat'>
                        </div> -->




                        <button type="submit" class='register-btn' name="submit" value="Register">Register</button>
                        

                    

                    </form>

                </div>
            </div>
            <a class='Register-link' href="login.php">Already have a account?Login Now</a>


        </div>



    </main>

    <script>
            function selectMethod(element, method) {
             // Remove the selected class from all buttons
                document.querySelectorAll('.usertype .type').forEach(function(button) {
                    button.classList.remove('selected');
                });

                // Add the selected class to the clicked button
                element.classList.add('selected');

                // Update the hidden input value
                document.getElementById('user_type').value = method;
             }

    </script>



    <?php include('alert.php'); ?>

    <?php include ('footer.php')?>

</body>
</html>
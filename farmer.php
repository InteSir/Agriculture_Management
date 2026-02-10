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
    

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $name=$_POST['name'];
        $category=$_POST['cat'];
        $quantity=$_POST['quantity'];
        $detail=$_POST["detail"];
        $price=$_POST['price'];

        $prod_cat=$_POST['prod_cat'];





        if (isset($_FILES['image'])) {
            $file_name = $_FILES['image']['name'];
            $tempname = $_FILES['image']['tmp_name'];
            $targetPath = 'assets/product_img/' . $file_name;
    
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowedpath = array('jpg', 'jpeg', 'png', 'gif');
    
            if (in_array($ext, $allowedpath)) {
                if (move_uploaded_file($tempname, $targetPath)) {
                    $farmerid = $_SESSION['user_id'];
                    
                   
                    $query = "INSERT INTO product (farmer_id, name, category, quantity, details, price, image1) VALUES ('$farmerid', '$name', '$category', '$quantity', '$detail', '$price', '$file_name')";
                  
    
                    if (mysqli_query($conn, $query)) {
                        echo 'Image inserted';
                        header('location:farmer.php');
                    } else {
                        echo 'Something is wrong: ' . mysqli_error($conn);
                    }
                } else {
                    echo 'File not uploaded';
                }
            } else {
                echo 'Your file is not allowed';
            }
        } else {
            if (!isset($_FILES['image'])) {

                $file_name=$prod_cat.'.png';
                $query = "INSERT INTO product (farmer_id, name, category, quantity, details, price, image1) VALUES ('$farmerid', '$name', '$category', '$quantity', '$detail', '$price', '$file_name')";
                ;
            }
            
        }
    }



    if (isset($_POST['delete'])){

        $product_id=$_POST['pro_id'];
    
        $sql = "DELETE FROM product WHERE  product_id = '$product_id'";
        $result=mysqli_query($conn,$sql);
    
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
        <div class='wrapper' >
            <!-- <div style="width:100%">
                <img src="assets/img/image (1).png" alt="" style="width:100%; object-fit: cover; box-shadow: 0 0 10px rgba(0,0,0,.5);">
            </div>               -->
            <div class="user_title">
                <h1>Welcome    <span><?php echo $user_name?></span></h1>
                <!-- <h1 class='title'><?php echo $user_name?><span></span></h1> -->
            </div>
            
            <?php
                    $sql="SELECT * FROM users where user_id='$user_id'";
                    $profile=mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($profile)){
            ?>
            <div class="user_welcome">
                <div class="user_profile">
                        <div class="detail">

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
                        <div class="box">
                                <?php
                                    

                                    $sql2 = "SELECT SUM(price * quantity) AS total_price FROM product WHERE farmer_id=$user_id";

                                    // Execute the query
                                    $result = mysqli_query($conn, $sql2);

                                    // Fetch the result
                                    if ($result) {
                                        $price = mysqli_fetch_assoc($result); // Fetch associative array
                                        $total_earn =  $price['total_price']; // Display the total price
                                    } else {
                                        $total_earn = 0;
                                    }

                                ?>
                                <h3>Total Earned</h3>
                                <h3><?=$total_earn; ?> Tk</h3>

                        </div>
                </div>
                <div class="dashboard">                                   
                        <div class="box">
                                <?php
                                    $sql2 = "SELECT SUM(quantity) AS total_price FROM product WHERE farmer_id=$user_id";

                                    // Execute the query
                                    $result = mysqli_query($conn, $sql2);

                                    // Fetch the result
                                    if ($result) {
                                        $price = mysqli_fetch_assoc($result); // Fetch associative array
                                        $total_sold =  $price['total_price']; // Display the total price
                                    } else {
                                        $total_sold = 0;
                                    }
                                ?>
                                <h3>Total Sold</h3>
                                <h3><?= $total_sold; ?> products</h3>

                        </div>                
                </div>
                <button type="button" id="add-product-btn" ><ion-icon name="add"></ion-icon>Add Products</button>



            </div>
            <div class="farmer_register">
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
                <div class='sell_products'>
                            <h1> SELL YOUR PRODUCTS </h1>
                            <form action= "<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                                <div class="flex">
                                    <input type="text" placeholder="Product_name" name='name'>  
                                    <input type="text"  placeholder="quantity" name='quantity'>      
                                </div>       

                                <div class="flex" style='height:70px;'>
                                    <input type="text" placeholder="price" name='price'> 
                                                            

                                    <select id="product_category" class='box' name='cat'   onchange="filterOptions()">
                                            <!-- <option value="" class='selected'>select category</option> -->
                                            <option value="">Select Category</option>
                                            <option value="Fruit">Fruit</option>
                                            <option value="Vegetable">Vegetable</option>
                                            <option value="Meat">Meat</option>
                                    </select><br><br>
                                </div>
                                                

                                <div class="di_flex" style='height:140px'>
                                    <div class="img-area" onclick="document.getElementById('fileInput').click();" >
                                        <img id="previewImage" src="assets/img/icons8-upload-64.png"  style="margin-left:10px;" alt="">
                                        <h3 id='fileName'>Upload Image</h3>
                                    </div>

                                    <h3>OR</h3>

                                    <select id="product_options" class='box' style='margin:0; font-size:1.2rem;height: 73px;' name='prod_cat'>
                                        <option value="">Select Product</option>
                                    </select><br><br>
                                </div>

                                <input type="file" name="image"  id="fileInput"  accept="image/jpg, image/jpeg, image/png" style='display:none'onchange="displayFileName()">

                                <textarea class='textarea' placeholder="Enter details" name='detail'></textarea>

                                        
                            <button type="submit" class='btn' name="submit" >SELL</button>

                            </form>

                </div>   
            </div>
            <?php 
                        }
            ?>

        </div>
        <div class="show_products">
                <div class="box-container">
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>S NO.</th>
                                        <th>Product</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>                    
                                    <?php
                                        $sl_no=1;
                                        $grand_total=0;
                                        

                                        $sql = "SELECT * FROM `product` WHERE farmer_id='$user_id'";
                                        $result = mysqli_query($conn,$sql);
                                        if(mysqli_num_rows($result) > 0){
                                            while($row = mysqli_fetch_assoc($result)){  
                                    ?>

                                    <tr>
                                        <td>        <?= $sl_no++; ?>                                      </td>
                                        <td>    <img src="assets/product_img/<?php echo $row["image1"];?>.png" alt=""> </td>
                                        <td>    <div class="name"><?= $row['name']; ?></div>             </td>
                                        <td>    <div class="cat"><?= $row['category']; ?></div>          </td>

                                        <td>    <div class="quantity"><?= $row['quantity']; ?></div>     </td>
                                        <td>    <div class="price"><?= $row['price']; ?>Tk</div>        </td>
                                        <td>
                                            <form method="post">
                                                <button  type='submit' name='edit' style="background: none;border:none;padding: 0; cursor: pointer;"><img src="assets/img/edit.png"  alt=""></button>
                                                <button type="submit" name="delete" onclick="return confirm('remove item')" style="background: none;border:none;padding: 0; cursor: pointer;"><img src="assets/img/bin.png" alt="Delete" ></button>
                                                <input type="hidden" name='pro_id' value='<?php echo $row["product_id"];?>'>
                                                <?php $grand_total+=$row['price']; ?>
                                            </form>
                                        </td>
                                    </tr>

                                    <?php
                                                }
                                            }else{
                                            echo '<p class="empty">now products added yet!</p>';
                                            }
                                    ?>
                                    <tr class='table_bottom'>
                                        <td colspan="5">Grand total: </td>
                                        <td><?php echo $grand_total; ?></td>
                                        <td></td>
                                    </tr>

                                </tbody>
                            </table>
                        
                        
                        </div>


                </div>

            </div>

    </main>
    <script>        
        let editbtn=document.querySelector('#edit-btn');
        let addProductBtn = document.querySelector('#add-product-btn');

        editbtn.addEventListener('click',function(){
            let editprof=document.querySelector('.edit-profile');
            editprof.classList.toggle('active');
        })

        addProductBtn.addEventListener('click', function() {
            let sellProducts = document.querySelector('.sell_products');
            sellProducts.classList.toggle('active');
        })
    </script>



    <script>


        function displayFileName() {
            var fileInput = document.getElementById('fileInput');
            var fileName = document.getElementById('fileName');
            var previewImage = document.getElementById('previewImage');
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                }
                reader.readAsDataURL(fileInput.files[0]);
            }
        }
        function filterOptions() {
            const category = document.getElementById('product_category').value;
            const productOptions = document.getElementById('product_options');

            // Clear the current options
            productOptions.innerHTML = '';

            // Create a placeholder option
            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.textContent = 'Select Product';
            productOptions.appendChild(placeholderOption);

            // Define products based on categories
            const products = {
                Fruit: ['apple', 'banana', 'blue grapes', 'orange','lichi','strawberry','watermelon','green grapes'],
                Vegetable: ['broccoli', 'cabbage', 'capsicum','garlic','carrot','onion','tomato','cauliflower','red papper'],
                Meat: ['Chicken','oily fishes','salmon fish','semon fish','chicken leg pieces','beaf steak']
            };

            // Populate the options based on selected category
            if (category in products) {
                products[category].forEach(function(product) {
                    const option = document.createElement('option');

                    option.value = product.toLowerCase().replace(/ /g, '_');
                    option.textContent = product;
                    productOptions.appendChild(option);
                });
            }
        }

   </script> 

    
    <?php include('alert.php'); ?>

    <?php include ('footer.php')?>
</body>
</html>
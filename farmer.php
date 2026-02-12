<?php 
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    include('connection.php');



    if (isset($_SESSION['user_id'])){
        $user_id=$_SESSION['user_id'];
        $user_name=$_SESSION['username'];
        }else{
            header('location: login.php');
            exit();
    
    }
    if (isset($_POST['logout'])){
            session_destroy();
            header('location: login.php');
            exit();
    }
    //handle profile update 
    if(isset($_POST['update_profile'])){
        $update_name = mysqli_real_escape_string($conn,$_POST['fullname']);
        $update_email = mysqli_real_escape_string($conn,$_POST['email']);
        $update_number = mysqli_real_escape_string($conn,$_POST['number']);
        $update_address = mysqli_real_escape_string($conn,$_POST['address']);

        $update_query = "UPDATE users SET fullname='$update_name',email='$update_email',number='$update_number',address='$update_address' WHERE user_id='$user_id'";
        if(mysqli_query($conn,$update_query)){
            $_SESSION['username'] = $update_name;
            $success_msg = "Profile updated successfully!";

        }else{
            $error_msg = "Failed to update profile";
        }


    };
    
    //handle product addition
    if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']=='POST'){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $category = mysqli_real_escape_string($conn, $_POST['cat']);
        $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
        $detail = mysqli_real_escape_string($conn, $_POST['detail']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $prod_cat = mysqli_real_escape_string($conn, $_POST['prod_cat']);

        if (isset($_FILES['image'])  && $_FILES['image']['error'] == 0) {
            $file_name = $_FILES['image']['name'];
            $tempname = $_FILES['image']['tmp_name'];
            $targetPath = 'assets/product_img/' . $file_name;
    
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowedpath = array('png');
            $base_name = pathinfo($file_name, PATHINFO_FILENAME);


    
            if (in_array(strtolower($ext), $allowedpath)) {
                if (move_uploaded_file($tempname, $targetPath)) {
                    $farmerid = $_SESSION['user_id'];
                    
                    $query = "INSERT INTO product (farmer_id, name, category, quantity, details, price, image1) VALUES ('$farmerid', '$name', '$category', '$quantity', '$detail', '$price', '$base_name')";
                  
    
                    if (mysqli_query($conn, $query)) {
                        $success_msg = 'Product added successfully!';

                    } else {
                        $error_msg = 'Error: ' . mysqli_error($conn);
                    }
                } else {
                    $error_msg = 'File not uploaded';
                }
            } else {
               $error_msg = 'Invalid file type';
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

        $product_id = mysqli_real_escape_string($conn, $_POST['pro_id']);
    
        $sql = "DELETE FROM product WHERE  product_id = '$product_id'";
        if(mysqli_query($conn, $sql)){
            $success_msg = "Product deleted successfully!";
        } else {
            $error_msg = "Failed to delete product!";
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
        <div class='user_dashboard_container' >
            <?php
                    $sql="SELECT * FROM users where user_id='$user_id'";
                    $profile=mysqli_query($conn,$sql);
                    $user_data  = mysqli_fetch_assoc($profile);

                    $stats_query = "SELECT 
                        COUNT(*) as total_products,
                        COALESCE(SUM(quantity), 0) as total_quantity,
                        COALESCE(SUM(price * quantity), 0) as total_value
                    FROM product WHERE farmer_id='$user_id'";

                    $stat_result = mysqli_query($conn,$stats_query);
                    $stats = mysqli_fetch_assoc($stat_result);

                    $orders_query = "SELECT COUNT(*) as order_count FROM `order` WHERE user_id='$user_id'";
                    $orders_result = mysqli_query($conn, $orders_query);
                    $orders_data = mysqli_fetch_assoc($orders_result);


                   
            ?>
            <div class="user_dashboard_head">
                <div class="user_dashboard_welcome">
                    <h1>Welcome back, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
                   <?php if ($_SESSION['user_type'] !== 'customer'): ?>
                    <p>Manage your farm products and track your sales</p>
                    <?php endif; ?>
                </div>

                <div class="profile-section">
                    <div class="profile-header">
                        <h2>Profile Information</h2>
                        <button class="btn-primary" id="editProfileBtn">
                            <ion-icon name="create-outline"></ion-icon>
                            Edit Profile
                        </button>
                    </div>

                    <div class="profile-info">
                        <div class="info-item">
                            <ion-icon name="person-outline"></ion-icon>
                            <p><?php echo htmlspecialchars($user_data['fullname'] ?? 'Not set'); ?></p>
                        </div>
                        <div class="info-item">
                            <ion-icon name="mail-outline"></ion-icon>
                            <p><?php echo htmlspecialchars($user_data['email'] ?? 'Not set'); ?></p>
                        </div>
                        <div class="info-item">
                            <ion-icon name="call-outline"></ion-icon>
                            <p><?php echo htmlspecialchars($user_data['number'] ?? 'Not set'); ?></p>
                        </div>
                        <div class="info-item">
                            <ion-icon name="location-outline"></ion-icon>
                            <p><?php echo htmlspecialchars($user_data['address'] ?? 'Not set'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($_SESSION['user_type'] !== 'customer'): ?>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-icon" style="background: #e0e7ff;">
                        <ion-icon name="cube-outline" style="color: #667eea;"></ion-icon>
                    </div>
                    <div class="card-title">Total Products</div>
                    <div class="card-value"><?php echo $stats['total_products']; ?></div>
                </div>

                <div class="dashboard-card">
                    <div class="card-icon" style="background: #dbeafe;">
                        <ion-icon name="layers-outline" style="color: #3b82f6;"></ion-icon>
                    </div>
                    <div class="card-title">Total Inventory</div>
                    <div class="card-value"><?php echo $stats['total_quantity']; ?> items</div>
                </div>

                <div class="dashboard-card">
                    <div class="card-icon" style="background: #d1fae5;">
                        <ion-icon name="cash-outline" style="color: #10b981;"></ion-icon>
                    </div>
                    <div class="card-title">Total Value</div>
                    <div class="card-value"><?php echo number_format($stats['total_value']); ?> Tk</div>
                </div>

                <div class="dashboard-card">
                    <div class="card-icon" style="background: #fef3c7;">
                        <ion-icon name="cart-outline" style="color: #f59e0b;"></ion-icon>
                    </div>
                    <div class="card-title">Total Orders</div>
                    <div class="card-value"><?php echo $orders_data['order_count']; ?></div>
                </div>

            </div>
            <?php endif; ?>

            <div class="orders-section">
                <h2 style="margin-bottom: 20px;">My Orders</h2>
                <?php
                $orders_query = "SELECT o.*, COUNT(oi.id) as item_count 
                            FROM `order` o 
                            LEFT JOIN order_items oi ON o.order_id = oi.order_id 
                            WHERE o.user_id='$user_id' 
                            GROUP BY o.order_id 
                            ORDER BY o.order_date DESC";
                $orders_result = mysqli_query($conn, $orders_query);
                
                if(mysqli_num_rows($orders_result) > 0):
                    while($order = mysqli_fetch_assoc($orders_result)):
                ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <strong>Order #<?php echo $order['order_id']; ?></strong>
                                <p style="color: #666; margin-top: 5px;"><?php echo date('M d, Y', strtotime($order['order_date'])); ?></p>
                            </div>
                            <div class="order-status status-pending">Pending</div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                            <div>
                                <small style="color: #666;">Payment Method</small>
                                <p style="margin-top: 5px;"><strong><?php echo htmlspecialchars($order['method']); ?></strong></p>
                            </div>
                            <div>
                                <small style="color: #666;">Total Amount</small>
                                <p style="margin-top: 5px;"><strong><?php echo number_format($order['total_amount']); ?> Tk</strong></p>
                            </div>
                            <div>
                                <small style="color: #666;">Items</small>
                                <p style="margin-top: 5px;"><strong><?php echo $order['item_count']; ?> items</strong></p>
                            </div>
                            <div>
                                <small style="color: #666;">Delivery Address</small>
                                <p style="margin-top: 5px;"><strong><?php echo htmlspecialchars($order['address']); ?></strong></p>
                            </div>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="empty-state">
                        <ion-icon name="cart-outline"></ion-icon>
                        <h3>No orders yet</h3>
                        <p>Your order history will appear here</p>
                    </div>
                <?php endif; ?>
            </div>
<?php if ($_SESSION['user_type'] !== 'customer'): ?>
            <!-- Products Table -->
            <div class="products-section">
                <div class="products_action">
                    <h2 style="margin-bottom: 20px;">My Products</h2>
                    <div class="action-buttons">
                        <div class="add-icon">
                            <ion-icon name="add-outline"></ion-icon>
                        </div>
                           <button class="btn-success" id="addProductBtn">  
                               Add New Product
                           </button>
                       </div>

                </div>
                <div class="products-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl_no = 1;
                            $sql = "SELECT * FROM product WHERE farmer_id='$user_id' ORDER BY product_id DESC";
                            $result = mysqli_query($conn, $sql);
                            
                            if(mysqli_num_rows($result) > 0):
                                while($row = mysqli_fetch_assoc($result)):
                            ?>
                                <tr>
                                    <td><?php echo $sl_no++; ?></td>
                                    <td><img src="assets/product_img/<?php echo htmlspecialchars($row['image1']); ?>.png" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                </td>

                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td><?php echo number_format($row['price']); ?> Tk</td>
                                    <td><?php echo number_format($row['price'] * $row['quantity']); ?> Tk</td>
                                    <td>
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="pro_id" value="<?php echo $row['product_id']; ?>">
                                            <button type="submit" name="delete" class="icon-btn" onclick="return confirm('Are you sure you want to delete this product?')">
                                                <img src="assets/img/bin.png" alt="Delete">
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 50px;">
                                        <div class="empty-state">
                                            <ion-icon name="cube-outline"></ion-icon>
                                            <h3>No products added yet</h3>
                                            <p>Start adding your products to sell</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Edit Profile Modal -->
        <div class="modal" id="editProfileModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Profile</h2>
                    <button class="close-btn" onclick="closeModal('editProfileModal')">&times;</button>
                </div>
                <form method="post">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user_data['fullname']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="number" value="<?php echo htmlspecialchars($user_data['number']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>">
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary" style="width: 100%;">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal" id="addProductModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Product</h2>
                    <button class="close-btn" onclick="closeModal('addProductModal')">&times;</button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="cat" id="product_category" onchange="filterOptions()" required>
                            <option value="">Select Category</option>
                            <option value="Fruit">Fruit</option>
                            <option value="Vegetable">Vegetable</option>
                            <option value="Meat">Meat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label>Price (Tk)</label>
                        <input type="number" name="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Product Image</label>
                        <div class="image-upload-area" onclick="document.getElementById('fileInput').click()">
                            <img id="previewImage" src="assets/img/icons8-upload-64.png" alt="Upload">
                            <p id="fileName">Click to upload image</p>
                        </div>
                        <input type="file" name="image" id="fileInput" accept="image/*" style="display: none;" onchange="displayFileName()">
                    </div>
                    <div class="form-group">
                        <label>Or Select Pre-defined Product</label>
                        <select name="prod_cat" id="product_options">
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Details</label>
                        <textarea name="detail" rows="4"></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success" style="width: 100%;">Add Product</button>
                </form>
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




        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        
        }

        document.getElementById('editProfileBtn').addEventListener('click', function() {
            openModal('editProfileModal');

        });

        document.getElementById('addProductBtn').addEventListener('click', function() {
            openModal('addProductModal');
        });

               window.addEventListener('click', function(e) {
            if(e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
            }
        });


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
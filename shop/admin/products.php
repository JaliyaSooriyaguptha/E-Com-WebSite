<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $category_id = $_POST['category_id'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(name, details,category_id, price, image_01, image_02, image_03) VALUES(?,?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $category_id, $price, $image_01, $image_02, $image_03]);

      if($insert_products){
         if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'new product added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

   <link rel="stylesheet" href="../css/admin_style.css">
<style type="text/css">
   .pagination li{
      margin-left: 10px;
   }
</style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products container">

   <h1 class="heading">add product</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>product name (required)</span>
            <input type="text" class="form-control" required maxlength="100" placeholder="enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>product price (required)</span>
            <input type="number" min="0" class="form-control" required max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
        <div class="inputBox">
            <span>image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
        </div>
        <div class="inputBox">
            <span>image 02 (required)</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
        </div>
        <div class="inputBox">
            <span>image 03 (required)</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
        </div>
         <div class="inputBox">
            <span>product categories (required)</span>

<select  name="category_id" class="form-control" style="width:100%">
   
   <?php
      $select_cats = $conn->prepare("SELECT * FROM `categories`");
      $select_cats->execute();
      if($select_cats->rowCount() > 0){
         while($fetch_cats = $select_cats->fetch(PDO::FETCH_ASSOC)){ 
   ?>
<option value="<?php echo $fetch_cats['id'] ?>"><?php echo $fetch_cats['name'] ?></option>
   <?php
         }
      }else{
         echo '<p class="empty">no categories added yet!</p>';
      }
   ?>
</select>


         </div>
         <div class="inputBox">
            <span>product details (required)</span>
            <textarea name="details" placeholder="enter product details" class="form-control" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add product" class="option-btn" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">products added</h1>

   <div class="box-container">


<?php 
    //include configuration file

    $start = 0;  $per_page = 9;
    $page_counter = 0;
    $next = $page_counter + 1;
    $previous = $page_counter - 1;
    
    if(isset($_GET['start'])){
     $start = $_GET['start'];
     $page_counter =  $_GET['start'];
     $start = $start *  $per_page;
     $next = $page_counter + 1;
     $previous = $page_counter - 1;
    }
    // query to get messages from messages table
    $q = "SELECT * FROM products LIMIT $start, $per_page";
    $query = $conn->prepare($q);
    $query->execute();

    if($query->rowCount() > 0){
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    }
    // count total number of rows in products table
    $count_query = "SELECT * FROM products";
    $query = $conn->prepare($count_query);
    $query->execute();
    $count = $query->rowCount();
    // calculate the pagination number by dividing total number of rows with per page.
    $paginations = ceil($count / $per_page);
?>


                <?php 
                    foreach($result as $data) { 

?>

   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $data['id']; ?>">
      <input type="hidden" name="name" value="<?= $data['name']; ?>">
      <input type="hidden" name="price" value="<?= $data['price']; ?>">
      <input type="hidden" name="image" value="<?= $data['image_01']; ?>">
      <button class="fas fa-heart  text-danger" type="submit" name="add_to_wishlist"></button>
      <img src="../uploaded_img/<?= $data['image_01']; ?>" alt="">
      <h3 class="name"><a href="quick_view.php?pid=<?= $data['id']; ?>" class="text-dark"><?= $data['name']; ?></a> </h3>
      <div class="flex">
         <div class="price"><span>$</span><?= $data['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn btn-primary option-btn border-0" name="add_to_cart">
   </form>
<?php

                    }
                 ?>

   
   
   </div>

<div class="container text-center">
   
            <ul class="pagination " style="padding:50px 100px">
           <?php
                if($page_counter == 0){
                    echo "<li><a class='active btn btn-primary  option-btn border-0' href=?start='0'>0</a></li>";
                    for($j=1; $j < $paginations; $j++) { 
                      echo "<li><a class='active btn btn-primary  option-btn border-0' href=?start=$j>".$j."</a></li>";
                   }
                }else{
                    echo "<li><a class=' btn btn-primary  option-btn border-0' href=?start=$previous >Previous</a></li>"; 
                    for($j=0; $j < $paginations; $j++) {
                     if($j == $page_counter) {
                        echo "<li><a class='active btn btn-primary  option-btn border-0' href=?start=$j >".$j."</a></li>";
                     }else{
                        echo "<li><a class=' btn btn-primary  option-btn border-0' href=?start=$j >".$j."</a></li>";
                     } 
                  }if($j != $page_counter+1)
                    echo "<li><a class=' btn btn-primary  option-btn border-0' href=?start=$next >Next</a></li>"; 
                } 
            ?>
         </ul>

</div>
</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>
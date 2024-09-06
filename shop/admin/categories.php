<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_category'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_cat/'.$image_01;


   $select_categories = $conn->prepare("SELECT * FROM `categories` WHERE name = ?");
   $select_categories->execute([$name]);

   if($select_categories->rowCount() > 0){
      $message[] = 'category name already exist!';
   }else{

      $insert_categories = $conn->prepare("INSERT INTO `categories`(name, file) VALUES(?,?)");
      $insert_categories->execute([$name, $image_01]);

      if($insert_categories){
         if($image_size_01 > 2000000 ){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            $message[] = 'new category added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_category_image = $conn->prepare("SELECT * FROM `categories` WHERE id = ?");
   $delete_category_image->execute([$delete_id]);
   $fetch_delete_image = $delete_category_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   $delete_category = $conn->prepare("DELETE FROM `categories` WHERE id = ?");
   $delete_category->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:categories.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>categories</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-categories container">

   <h1 class="heading">add category</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <label >category name (required)</label>
            <input type="text" class="form-control" required maxlength="100" placeholder="enter category name" name="name">
         </div>
         
        <div class="inputBox">
            <span>image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
      </div>
      
      <input type="submit" value="add category" class="btn" name="add_category">
   </form>

</section>

<section class="show-categories container">

   <h1 class="heading">categories added</h1>

   <div class="box-container">
      <div class="row">

   <?php
      $select_categories = $conn->prepare("SELECT * FROM `categories`");
      $select_categories->execute();
      if($select_categories->rowCount() > 0){
         while($fetch_categories = $select_categories->fetch(PDO::FETCH_ASSOC)){ 
   ?>
        <div class="col-md-4">
            <div class="card mb-3" >
                <img class="card-img-top" src="../uploaded_cat/<?php echo $fetch_categories['file']; ?>" height="380" alt="Card image cap">
                <?= $fetch_categories['name']; ?>
         <a href="categories.php?delete=<?= $fetch_categories['id']; ?>" class="delete-btn" onclick="return confirm('delete this category?');">delete</a>
                <div class="product-detail">
                   
                   </div>
            </div>
        </div>
<!--    <div class="box">
      <img src="../uploaded_cat/<?php echo $fetch_categories['file']; ?>" alt="">
      <div class="name"><?= $fetch_categories['name']; ?></div>
      
      <div class="flex-btn">
      </div>
   </div> -->
   <?php
         }
      }else{
         echo '<p class="empty">no categories added yet!</p>';
      }
   ?>
   
    </div>
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>
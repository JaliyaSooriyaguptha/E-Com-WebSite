<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style type="text/css">
      .swiper-slide{
         position: relative;
         //background: rgba(255, 255, 255, 0.4);
         padding: 20px;
         border-radius: 5px;
      }
      .swiper-slide .content {
         position: absolute;
         top: 0%;
         right: 10px;
         text-align: right;
         text-shadow: 3px 3px 3px rgba(0, 0, 0,.7);
      }

   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

<section class="home">

<div class="container">
  <div class="row">
    <div class="col-sm-4"><br><br><br>
      <h1 class="text-white pt-25" style="margin-top:40;"> WELCOME TO ECOMMERCE</h1>
    </div>
    <div class="col-sm-8 other">
      


   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
 


      <div class="swiper-slide slide">
         <div class="image">
            <img  src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
         </div>
         <div class="content">
            <span><?= $fetch_product['name']; ?></span>
            <h3><?= $fetch_product['price']; ?></h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>


   <?php
      }
   }else{
      echo '<p class="empty ">no products added yet!</p>';
   }
   ?>



   </div>

      <div class="swiper-pagination"></div>

   </div>

    </div>
  </div>
</div>


</section>

</div>

<section class="category container">




   <h1 class="heading">shop by category</h1>

<div class="row">

</div>


   <div class="swiper category-slider">


   <div class="swiper-wrapper">

         <?php
           $select_categories = $conn->prepare("SELECT * FROM `categories` "); 
           $select_categories->execute();
           if($select_categories->rowCount() > 0){
            while($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)){
         ?>
         <a href="category.php?category=<?= $fetch_category['id']; ?>" class="swiper-slide slide" style="background:url(<?php echo "uploaded_cat/".$fetch_category['file']; ?>); background-size: cover;">
            <h2 class="text-white text-shadow p-5 text-bold" style="text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.8);"><?= $fetch_category['name']; ?></h2>
         </a>

         <?php
            }
         }else{
            echo '<p class="empty ">no categories added yet!</p>';
         }
         ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products container">

   <h1 class="heading">latest products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` ORDER BY id desc LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart text-danger" type="submit" name="add_to_wishlist"></button>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <h3 class="name"><a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="text-dark"><?= $fetch_product['name']; ?></a> </h3>
      <div class="flex">
         <div class="price"><span>LKR </span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn btn-primary option-btn border-0" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty ">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>
   <div class="text-center container"><br>
      <a href="shop.php" class="seeMore rounded-pill">See More</a>
   </div>

</section>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   }, navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
   breakpoints: {
      0: {
         slidesPerView: 4,
       },
      650: {
        slidesPerView: 4,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>
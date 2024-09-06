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
   <title>shop</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
<style type="text/css">
   .pagination li{
      margin-left: 10px;
   }
</style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">latest products</h1>

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
      <img src="uploaded_img/<?= $data['image_01']; ?>" alt="">
      <h3 class="name"><a href="quick_view.php?pid=<?= $data['id']; ?>" class="text-dark"><?= $data['name']; ?></a> </h3>
      <div class="flex">
         <div class="price"><span>LKR </span><?= $data['price']; ?><span>/-</span></div>
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













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">admin accounts</h1>

   <div class="container">

   <div class="box">
      <a href="register_admin.php" class="btn" style="width:200px;float: right;">+ register admin</a>
   </div>
<table class="table">
   <tr>
      <th>Admin Id</th>
      <th>Admin Name</th>
      <th></th>
   </tr>
   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `admins`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
   ?>

   <tr>
      <td><?= $fetch_accounts['id']; ?></td>
      <td><?= $fetch_accounts['name']; ?></td>
      <td>
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account?')" class="delete-btn">delete</a>
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_profile.php" class="option-btn">update</a>';
            }
         ?>
      </div></td>
   </tr>
   <?php
         }
      }else{
         echo '<p class="empty">no accounts available!</p>';
      }
   ?>
</table>

   </div>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>
<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section >

<h1 class="heading">MESSAGES</h1>

<div class="container">text
   <table class="table" style="width: 100%;">
      <tr>
         <th>user id</th>
         <th>Name</th>
         <th>Email</th>
         <th>Mobile Number</th>
         <th>Message</th>
         <th>Action</th>
      </tr>
      <tr>
      </tr>


   <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <tr>


         <td><?= $fetch_message['user_id']; ?></td>
         <td><?= $fetch_message['name']; ?></td>
         <td><?= $fetch_message['email']; ?></td>
         <td><?= $fetch_message['number']; ?></td>
         <td><?= $fetch_message['message']; ?></td>
         <td>
   <a href="messages.php??delete=<?= $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="btn btn-sm btn-primary">delete</a></td>

   </tr>
   <?php
         }
      }else{
         echo '<p class="empty">you have no messages</p>';
      }
   ?>
   </table>


</div>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>
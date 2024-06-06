<?php
require_once '../include/dbcon.php';
    session_start();

    $UserName = $_SESSION["Email"];

    try{
        $pdoQuery = "SELECT * FROM customer_account WHERE Email=:Email";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->execute(['Email'=> $Email]);
        $user = $pdoResult->fetch();
    }catch(PDOException $error){
        echo $error->getMessage();
        exit;
    }
    
    if(isset($_GET['id'])){

        $pdoQuery = "DELETE FROM customer_account WHERE id = :id";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->execute(array(':id' => $_GET['id']));

        if($pdoResult){
            $loggedInUser = $_SESSION["Email"];
            $pdoQuery = "INSERT INTO `audit_trail`(`action`,`user`)VALUES('User Deleted',:user)";
            $pdoResult = $pdoConnect->prepare($pdoQuery);
            $pdoResult->execute([':user' => $loggedInUser]);
        header('location:../customer/customers.php');
        }
    }else{
        
        echo "Invalid request. Please provide a valid id.";
    }
   
    $pdoConnect = null;
?>
<?php
session_start();
include("dbcon.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_GET['token'])) {
  $token = $_GET['token'];
  try {
    $verify_query = "SELECT verify_token, verify_status FROM users WHERE verify_token='$token' LIMIT 1";
    $verify_query_run = mysqli_query($conn, $verify_query);
    if(mysqli_num_rows($verify_query_run) > 0) {
      $row = mysqli_fetch_array($verify_query_run);
      if($row["verify_status"] == "0") {
        $clicked_token = $row["verify_token"];
        $update_query = "UPDATE users SET verify_status='1' WHERE verify_token='$clicked_token' LIMIT 1";
        $update_query_run = mysqli_query($conn, $update_query);
        if($update_query_run) {
          $_SESSION['status'] = "Your Account has been verified Successfully";
          header("Location: login.php");  
          exit(0);
        } else {
          throw new Exception('Verification failed in database update'); 
        }
      } else {
        throw new Exception('Email already verified');
      }
    } else {
        throw new Exception('Invalid token');
    }
  } catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: login.php");  
    exit(0);
  }
} else {
  $_SESSION['error'] = "Not allowed";
  header("Location: login.php");
  exit(0);
}
?>
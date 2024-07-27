<?php
session_start();
include("LogIn/dbcon.php");
include("process_payment.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
function sendmail_verify1($name, $email) {
    $mail = new PHPMailer(true);

    // Server settings
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Port = 465;
    $mail->Username = 'istru03@gmail.com';
    $mail->Password = 'pjml oqhm izjg acks';
    $mail->SMTPSecure = 'ssl';
    $mail->CharSet = 'UTF-8';

    // Sender and recipient settings
    $mail->setFrom('istru03@gmail.com', $name);
    $mail->addAddress($email);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Transaction confirmed';
     $email_template = "
            <h2>Transaction confirmed</h2>
          ";
    $mail->Body = $email_template;

    // Send the email
    try {
        $mail->send();
      } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;  
        return false;
      }

}

function sendmail_verify2($name, $email, $company_name, $rec_name, $amount, $paymentDetails) {
  $mail = new PHPMailer(true);

  // Server settings
  $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Port = 465;
  $mail->Username = 'istru03@gmail.com';
  $mail->Password = 'pjml oqhm izjg acks';
  $mail->SMTPSecure = 'ssl';
  $mail->CharSet = 'UTF-8';

  // Sender and recipient settings
  $mail->setFrom('istru03@gmail.com', $name);
  $mail->addAddress($email);

  // Email content
  $mail->isHTML(true);
  $mail->Subject = 'Replenishment of the company balance';
   $email_template = "
          <h2>Replenishment of the company balance</h2>
          <h2>From company {$company_name} to Your Company {$rec_name} was transfered an amount of {$amount} mdl</h2>
          <h2>{$paymentDetails}</h2>
        ";
  $mail->Body = $email_template;

  // Send the email
  try {
      $mail->send();
    } catch (Exception $e) {
      echo "Mailer Error: " . $mail->ErrorInfo;  
      return false;
    }

}

$company_id = $_GET['company_id'];
function makePayment2($conn, $company_id, $amount, $money_comp, $money_rec, $recipient_id) {

  if($amount > $money_comp){
      echo "Insufficient funds";
      exit;
  } else {

      $balance = $money_comp - $amount;
      $sql = "UPDATE CompanyDetails SET money=$balance WHERE company_id=$company_id";
      mysqli_query($conn, $sql);
      if(!mysqli_query($conn, $sql)) {
          echo "Error updating company balance 1"; 
          return false; 
      }

      $balance = $money_rec + $amount;
      $sql1 = "UPDATE Recipient SET money=$balance WHERE recipient_id='$recipient_id'";  
      mysqli_query($conn, $sql1);
      if(!mysqli_query($conn, $sql1)) {
          echo "Error updating company balance 2"; 
          return false;
      }

      $balance = $money_rec + $amount;
      $sql3 = "UPDATE CompanyDetails SET money=$balance WHERE company_id=$recipient_id";
      mysqli_query($conn, $sql3);
      if(!mysqli_query($conn, $sql3)) {
          echo "Error updating company balance 3"; 
          return false; 
      }

      $balance = $money_comp - $amount;
      $sql4 = "UPDATE Recipient SET money=$balance WHERE recipient_id=$company_id";  
      mysqli_query($conn, $sql4);
      if(!mysqli_query($conn, $sql4)) {
          echo "Error updating company balance 4"; 
          return false;
      }
      return true; 
  }
  
}

if(isset($_GET['token'])) {
  $token = $_GET['token'];
  try {
    $verify_query = "SELECT transaction_id, verify_status FROM PaymentOrders WHERE transaction_id = '$token' LIMIT 1";
    $verify_query_run = mysqli_query($conn, $verify_query);
    if(mysqli_num_rows($verify_query_run) > 0) {
      $row = mysqli_fetch_array($verify_query_run);
      if($row["verify_status"] == "0") {
        $clicked_token = $row["transaction_id"];
        $update_query = "UPDATE PaymentOrders SET verify_status='1' WHERE transaction_id='$clicked_token' LIMIT 1";
        $update_query_run = mysqli_query($conn, $update_query);
        if($update_query_run) {

          $company_id = $_GET['company_id'];
          $amount = $_GET['amount'];
          $money_comp = $_GET['money_comp'];
          $money_rec = $_GET['money_rec'];
          $recipient_id = $_GET['recipient_id'];
          $position = $_GET['position'];
          $paymentDetails = $_GET['paymentDetails'];

          makePayment2($conn, $company_id, $amount, $money_comp, $money_rec, $recipient_id);
          
          $sql1 = "SELECT name, email FROM users WHERE company_id = $company_id AND position = '$position'";
          $result1 = mysqli_query($conn, $sql1);
          if(!$result1) {
              echo "The information entered is incorrect 1"; 
              return false; 
          }
          $row1 = mysqli_fetch_array($result1);
          $email = $row1['email'];
          $name = $row1['name'];
          sendmail_verify1($name, $email);


          $sql2 = "SELECT company_name FROM Companies WHERE company_id = $company_id";
          $company_result = mysqli_query($conn, $sql2);
          if(!$company_result) {
              echo "The information entered is incorrect"; 
              return false; 
          }
          $company_row = mysqli_fetch_array($company_result);
          $company_name = $company_row['company_name'];

          $sql4 = "SELECT recipient_name FROM Recipient WHERE recipient_id = $recipient_id";
          $rec_result = mysqli_query($conn, $sql4);
          if(!$rec_result) {
              echo "The information entered is incorrect"; 
              return false; 
          }
          $rec_row = mysqli_fetch_array($rec_result);
          $rec_name = $rec_row['recipient_name'];

          $sql3 = "SELECT name, email FROM users WHERE company_id = $recipient_id AND position = 'Boss'";
          $result3 = mysqli_query($conn, $sql3);
          if(!$result3) {
              echo "The information entered is incorrect 1"; 
              return false; 
          }
          $row3 = mysqli_fetch_array($result3);
          $email_rec = $row3['email'];
          $name_rec = $row3['name'];
          sendmail_verify2($name_rec, $email_rec, $company_name, $rec_name, $amount, $paymentDetails);


          header("Location: succes.php?token=$token");
          exit(0);
        } else {
          throw new Exception('Transfer failed'); 
        }
      } else {
        throw new Exception('Transfer already confirmed already');
      }
    } else {
        throw new Exception('Invalid token');
    }
  } catch (Exception $e) {
    echo $e->getMessage();
    header("Location: index.html"); 
    exit(0);
  }
} else {
  echo "Not allowed";
  header("Location: index.html");
  exit(0);
}
?>
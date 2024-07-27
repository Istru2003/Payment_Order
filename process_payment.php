<?php
session_start();
include('LogIn/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
function sendmail_verify($name, $email, $companyName, $recipientName, $transaction_id, $namefrom, $company_id, $amount, $money_comp, $money_rec, $recipient_id, $paymentDetails, $position) {
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
    $mail->Subject = 'Confirmation of transfer';
     $email_template = "
            <h2>An account {$namefrom} has requested a transfer of money {$amount} mdl from your company {$companyName} to a company {$recipientName}</h2>
            <h2>{$paymentDetails}</h2>
            <h3>You can confirm the transfer before the end of the day</h3>
            <h4>Follow the link to confirm the transfer</h4>
            <br/><br/>
            <a href='http://project-bd/verify_transaction.php?token=$transaction_id&company_id=$company_id&amount=$amount&money_comp=$money_comp&money_rec=$money_rec&recipient_id=$recipient_id&position=$position&paymentDetails=$paymentDetails'>Click Me</a>
        ";
    $mail->Body = $email_template;

    try {
        $mail->send();
        echo "Message sent!"; 
        return true;
      } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;  
        return false;
      }

}

function sendmail_verify3($name, $email, $company_name, $rec_name, $amount, $paymentDetails) {
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

    $companyName = $_GET['companyName'];
    $recipientName = $_GET['recipientName'];
    $amount = $_GET['amount'];
    $date = $_GET['date'];
    $IBAN_Comp = $_GET['IBAN_Comp'];
    $IBAN_Rec = $_GET['IBAN_Rec'];
    $Cod_Fisc_Comp = $_GET['Cod_Fisc_Comp'];
    $Cod_Fisc_Rec = $_GET['Cod_Fisc_Rec'];
    $bank_comp = $_GET['bank_comp'];
    $bank_rec = $_GET['bank_rec'];
    $paymentDetails = $_GET['paymentdetails'];
    $position = $_GET['position'];

    $sql = "SELECT company_id FROM Companies WHERE company_name = '$companyName'";
    $company_result = mysqli_query($conn, $sql);
    if(!$company_result) {
        echo "The information entered is incorrect"; 
        return false; 
    }
    $company_row = mysqli_fetch_array($company_result);
    $company_id = $company_row['company_id'];

    $sql1 = "SELECT bank_id FROM Banks WHERE bank_name = '$bank_comp'";
    $bank_result1 = mysqli_query($conn, $sql1);
    if(!$bank_result1) {
        echo "The information entered is incorrect"; 
        return false; 
    }
    $bank_row1 = mysqli_fetch_array($bank_result1);
    $bank_id_comp = $bank_row1['bank_id'];

    $sql2 = "SELECT IBAN_Comp, money FROM CompanyDetails WHERE company_id = $company_id AND IBAN_Comp = '$IBAN_Comp' AND Cod_Fisc_Comp = '$Cod_Fisc_Comp' AND bank_id = $bank_id_comp";
    $IBAN_result1 = mysqli_query($conn, $sql2);
    if(!$IBAN_result1) {
        echo "The information entered is incorrect"; 
        return false; 
    }
    $IBAN_row1 = mysqli_fetch_array($IBAN_result1);
    $IBAN_Company = $IBAN_row1['IBAN_Comp'];
    $money_comp = $IBAN_row1['money'];

    $sql3 = "SELECT bank_id FROM Banks WHERE bank_name = '$bank_rec'";
    $bank_result2 = mysqli_query($conn, $sql3);
    if(!$bank_result2) {
        echo "The information entered is incorrect"; 
        return false; 
    }
    $bank_row2 = mysqli_fetch_array($bank_result2);
    $bank_id_rec = $bank_row2['bank_id'];

    $sql4 = "SELECT IBAN_Rec, money, recipient_id FROM Recipient WHERE recipient_name = '$recipientName' AND IBAN_Rec = '$IBAN_Rec' AND Cod_Fisc_Rec = '$Cod_Fisc_Rec' AND bank_id = $bank_id_rec";
    $IBAN_result2 = mysqli_query($conn, $sql4);
    if(!$IBAN_result2) {
        echo "The information entered is incorrect"; 
        return false; 
    }
    $IBAN_row2 = mysqli_fetch_array($IBAN_result2);
    $IBAN_Recipient = $IBAN_row2['IBAN_Rec'];
    $money_rec = $IBAN_row2['money'];
    $recipient_id = $IBAN_row2['recipient_id'];

    $transaction_id = md5(rand());

    if($position === 'Boss'){
        $verify_status = 1;
        $sql1 = "SELECT name, email FROM users WHERE company_id = $recipient_id AND position = 'Boss'";
        $result1 = mysqli_query($conn, $sql1);
        if(!$result1) {
            echo "The information entered is incorrect 1"; 
            return false; 
        }
        $row1 = mysqli_fetch_array($result1);
        $email_rec = $row1['email'];
        $name_rec = $row1['name'];
        sendmail_verify3($name_rec, $email_rec, $company_name, $recipientName, $amount, $paymentDetails);
        $success = makePayment($conn, $company_id, $recipientName, $amount, $date, $IBAN_Company, $IBAN_Recipient, $money_comp, $money_rec, $recipient_id, $paymentDetails, $verify_status, $transaction_id); 
    } else {
        $verify_status = 0;
        $sql1 = "SELECT email, name FROM users WHERE company_id = $company_id AND position = 'Boss'";
        $result1 = mysqli_query($conn, $sql1);
        if(!$result1) {
            echo "The information entered is incorrect"; 
            return false; 
        }
        $row1 = mysqli_fetch_array($result1);
        $email = $row1['email'];
        $name = $row1['name'];

        $sql2 = "SELECT name FROM users WHERE company_id = $company_id AND position = '$position'";
        $result2 = mysqli_query($conn, $sql2);
        if(!$result2) {
            echo "The information entered is incorrect"; 
            return false; 
        }
        $row2 = mysqli_fetch_array($result2);
        $namefrom = $row2['name'];
        if($amount > $money_comp){
            echo "Insufficient funds";
            exit;
        }
        sendmail_verify($name, $email, $companyName, $recipientName, $transaction_id, $namefrom, $company_id, $amount, $money_comp, $money_rec, $recipient_id, $paymentDetails, $position);
        $success = paymentorder($conn, $company_id, $recipient_id, $paymentDetails, $amount, $date, $IBAN_Comp, $IBAN_Rec, $verify_status, $transaction_id);
    }

    if($success) {

        $sql = "SELECT money FROM CompanyDetails WHERE company_id = {$company_id}";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        
        $money = $row['money'];
      
        echo "ok;{$money}";
    } else {
        echo " error";
    }

    function paymentorder($conn, $company_id, $recipient_id, $paymentDetails, $amount, $date, $IBAN_Comp, $IBAN_Rec, $verify_status, $transaction_id){
        $sql = "INSERT INTO PaymentOrders (company_id,	recipient_id, Destin_plat, amount, payment_date, IBAN_Comp, IBAN_Rec, verify_status, transaction_id) VALUES ('$company_id', '$recipient_id', '$paymentDetails', '$amount', '$date', '$IBAN_Comp', '$IBAN_Rec', '$verify_status', '$transaction_id')";
        $query_run = mysqli_query($conn, $sql);
        if(!$query_run) {
            echo "Error inserting payment order";
            return false;
        } else {
            return true;
        }
    }

    function makePayment($conn, $company_id, $recipientName, $amount, $date, $IBAN_Comp, $IBAN_Rec, $money_comp, $money_rec, $recipient_id, $paymentDetails, $verify_status, $transaction_id) {

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
            $sql1 = "UPDATE Recipient SET money=$balance WHERE recipient_name='$recipientName'";  
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

            paymentorder($conn, $company_id, $recipient_id, $paymentDetails, $amount, $date, $IBAN_Comp, $IBAN_Rec, $verify_status, $transaction_id);

            return true;
        }
        
    }

$conn->close();
?>

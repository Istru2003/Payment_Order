<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
function sendmail_verify($name,$email,$verify_token) {
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
    $mail->Subject = 'Email Verification From MaibBank';
     $email_template = "
            <h2>You have Registered with MaibBank</h2>
            <h5>Verify your email address to Login with the below given link</h5>
            <br/><br/>
            <a href='http://project-bd/LogIn/verify-email.php?token=$verify_token'>Click Me</a>
        ";
    $mail->Body = $email_template;

    // Send the email
    $mail->send();

}

if(isset($_POST["register_btn"])){
    $name = $_POST["name"];
    $company = $_POST["company"];
    $position = $_POST["position"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];
    $verify_token = md5(rand());

    $sql = "SELECT company_id FROM Companies WHERE company_name = '$company'";
    $company_result = mysqli_query($conn, $sql);
    $company_row = mysqli_fetch_array($company_result);
    $company_id = $company_row['company_id'];

    $sql = "SELECT position FROM users WHERE company_id = '$company_id' AND position = 'Boss'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $position_boss = $row['position'];
    if ($position === $position_boss) {
      $_SESSION['status'] = "Boss for company $company is already registered";  
      header("Location: register.php");
      exit(0);
    } else {

        if ($password !== $password_confirmation) {
            $_SESSION['status'] = "Passwords do not match";
            header("Location: register.php");
            exit(0); 
        }

        $get_company_id_query = "SELECT company_id FROM Companies WHERE company_name = '$company' LIMIT 1";
        $get_company_id_query_run = mysqli_query($conn, $get_company_id_query);

        if(mysqli_num_rows($get_company_id_query_run) > 0){
            $row = mysqli_fetch_assoc($get_company_id_query_run);
            $company_id = $row['company_id'];

            $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
            $check_email_query_run = mysqli_query($conn, $check_email_query);

            if(mysqli_num_rows($check_email_query_run) > 0){
                $_SESSION['status'] = "Email ID already Exists";
                header("Location: register.php");
            } else {
                $query = "INSERT INTO users (name, company_id, position, email, password, verify_token) VALUES ('$name', '$company_id', '$position', '$email', '$password','$verify_token')";
                $query_run = mysqli_query($conn, $query);
                if ($query_run) {
                    sendmail_verify("$name","$email","$verify_token");
                    $_SESSION['status'] = "Registration Successful! Verify your Email";
                    header("Location: register.php");
                } else {
                    $_SESSION['status'] = "Registration Failed";
                    echo "Name: $name, Company: $company, Position: $position, Email: $email, Password: $password";
                    header("Location: register.php");
                }
            }
        } else {
            $_SESSION['status'] = "Company not found";
            header("Location: register.php");
        }
    }
}
?>

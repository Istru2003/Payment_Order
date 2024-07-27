<?php
session_start();
include('dbcon.php');

if (isset($_POST["login_now_btn"])) {

    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $login_query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $login_query_run = mysqli_query($conn, $login_query);

        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_array($login_query_run);
            $company_id = $row['company_id'];

            $sql = "SELECT company_name FROM Companies WHERE company_id = $company_id";
            $company_result = mysqli_query($conn, $sql);  
            $company_row = mysqli_fetch_array($company_result);
            $company_name = $company_row['company_name'];

            $sql1 = "SELECT IBAN_Comp, Cod_Fisc_Comp, bank_id, money FROM CompanyDetails WHERE company_id = $company_id";
            $detail_result = mysqli_query($conn, $sql1);
            $detail_row = mysqli_fetch_array($detail_result);
            $company_IBAN = $detail_row['IBAN_Comp'];
            $company_Fisc = $detail_row['Cod_Fisc_Comp'];
            $company_bankid = $detail_row['bank_id'];
            $company_money = $detail_row['money'];

            $sql2 = "SELECT bank_name FROM Banks WHERE bank_id = $company_bankid";
            $bank_result = mysqli_query($conn, $sql2);
            $bank_row = mysqli_fetch_array($bank_result);
            $bank_name = $bank_row['bank_name'];

            if($row["verify_status"] == "1") {
                $_SESSION['authenticated'] = TRUE;
                $_SESSION['auth_user'] = [
                    'username' => $row['name'],
                    'money' => $company_money,
                    'email' => $row['email'],
                    'company' => $company_name,
                    'position' => $row['position'],
                    'IBAN' => $company_IBAN,
                    'Fisc_Code' => $company_Fisc,
                    'Bank' => $bank_name,
                ];

                echo "<p>Debug: Company ID after setting the session: $company_id</p>";

                $_SESSION['status'] = "You are Logged In Succesfully";
                header("Location: dashboard.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Please Verify your Email to LogIn";
                header("Location: login.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Invalid Email or Password";
            header("Location: login.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "All fields are Mandatory";
        header("Location: login.php");
        exit(0);
    }
}
?>

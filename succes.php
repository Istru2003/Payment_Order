<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Order Form</title>
        <link rel="stylesheet" href="style.scss">
    </head> 
    <body>
        <div class="container" style="width:800px;">
            <div style="align-items: center;">
                <h1>Transfer Confirmed</h1>
            </div>
            <?php
                include('LogIn/dbcon.php');

                $token = $_GET['token'];

                $sql = "SELECT company_id, recipient_id, Destin_plat, amount FROM PaymentOrders WHERE transaction_id='$token'";
                $result = mysqli_query($conn, $sql);
                if(!$result) {
                    echo "The information entered is incorrect 1"; 
                    return false; 
                }
                $row = mysqli_fetch_array($result);
                $company_id = $row['company_id'];
                $recipient_id = $row['recipient_id'];
                $Destin_plat = $row['Destin_plat'];
                $amount = $row['amount'];

                $sql = "SELECT company_name FROM Companies WHERE company_id=$company_id";
                $result = mysqli_query($conn, $sql);
                if(!$result) {
                    echo "The information entered is incorrect 2"; 
                    return false; 
                }
                $row = mysqli_fetch_array($result);
                $company_name = $row['company_name'];

                $sql = "SELECT recipient_name FROM Recipient WHERE recipient_id=$recipient_id";
                $result = mysqli_query($conn, $sql);
                if(!$result) {
                    echo "The information entered is incorrect 3"; 
                    return false; 
                }
                $row = mysqli_fetch_array($result);
                $recipient_name = $row['recipient_name'];

                echo '<h2>From ' .$company_name . ' to ' . $recipient_name. '<br> was transfered ' .$amount. 'mdl.</h2>';
                echo '<h3>' .$Destin_plat. '</h3>';


                $conn->close();
            ?>
        </div>

    </body>
</html>
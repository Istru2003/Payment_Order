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
            <div style="display: flex; align-items: center;">
                <button id="backButton" style="font-size: 18px; cursor: pointer; margin-right: 125px;" onclick="goBack()">← Back</button>
                <h1>Transfers</h1>
            </div>
            <?php
                include('LogIn/dbcon.php');

                $sql = "SELECT company_id, recipient_id, Destin_plat, amount, verify_status, payment_date FROM PaymentOrders";
                $result = $conn->query($sql);

                if (!$result) {
                    echo "The information entered is incorrect 1";
                    return false;
                }

                while ($row = mysqli_fetch_array($result)) {
                    $company_id = $row['company_id'];
                    $recipient_id = $row['recipient_id'];
                    $Destin_plat = $row['Destin_plat'];
                    $amount = $row['amount'];
                    $verify_status = $row['verify_status'];
                    $payment_date = $row['payment_date'];

                    $today = date('Y-m-d');

                    if (strtotime($payment_date) < strtotime($today) && $verify_status == 0) {
                        $delete_sql = "DELETE FROM PaymentOrders WHERE payment_date='$payment_date' AND verify_status=0"; 
                        $conn->query($delete_sql);
                        continue;
                    }

                    $company_name = getCompanyName($conn, $company_id);
                    $recipient_name = getRecipientName($conn, $recipient_id);

                    if ($verify_status == 1) {
                        echo '<h2>' . $company_name . ' → ' . $amount . ' mdl → ' . $recipient_name . ' - ✓</h2>';
                    } elseif ($verify_status == 0) {
                        echo '<h2>' . $company_name . ' → ' . $amount . ' mdl → ' . $recipient_name . ' - ↺</h2>';
                    }
                }

                $conn->close();

                function getCompanyName($conn, $company_id) {
                    $sql = "SELECT company_name FROM Companies WHERE company_id=$company_id";
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        echo "The information entered is incorrect 2";
                        return false;
                    }

                    $row = mysqli_fetch_array($result);
                    return $row['company_name'];
                }

                function getRecipientName($conn, $recipient_id) {
                    $sql = "SELECT recipient_name FROM Recipient WHERE recipient_id=$recipient_id";
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        echo "The information entered is incorrect 3";
                        return false;
                    }

                    $row = mysqli_fetch_array($result);
                    return $row['recipient_name'];
                }
            ?>

        </div>
        <script>
            function goBack() {
                window.location.href = 'index.html';
            }
        </script>
    </body>
</html>
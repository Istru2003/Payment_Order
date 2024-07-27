<?php
    session_start();
    include('LogIn/dbcon.php');

    $recipientName = $_GET['name'];

    $sql = "SELECT * FROM Recipient WHERE recipient_name = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $recipientName);

    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $recipient_IBAN = $row['IBAN_Rec'];
    $recipient_Fisc = $row['Cod_Fisc_Rec'];
    $recipient_bankid = $row['bank_id'];

    $sql1 = "SELECT bank_name FROM Banks WHERE bank_id = $recipient_bankid";
    $bank_result = mysqli_query($conn, $sql1);
    $bank_row = mysqli_fetch_array($bank_result);
    $bank_name = $bank_row['bank_name'];

    if($row) {

        $iban = $recipient_IBAN;
        $fiscalCode = $recipient_Fisc;
        $bank = $bank_name;
      
        echo "iban=$iban;fiscal_code=$fiscalCode;bank=$bank";
    } else {
        echo "error"; 
      
    }

    $conn->close();
 ?>
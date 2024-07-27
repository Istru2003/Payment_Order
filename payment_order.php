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
                <button id="backButton" style="font-size: 18px; cursor: pointer; margin-right: 50px;" onclick="goBack()">← Back</button>
                <h1>Payment Order Form</h1>
            </div>
            <form id="paymentForm" style="width:800px;">

                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; width: 400px;">
                        <div style="margin-right: 10px;">
                            <label for="companyName">Your Company Name:</label>
                            <input type="text" id="companyName" name="companyName" required>
                        </div>
                        <div style="margin-right: 10px;">
                            <label for="companyIBAN">Your Company IBAN:</label>
                            <input type="text" id="companyIBAN" name="companyIBAN" required>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between;">
                        <div style="margin-right: 10px;">
                            <label for="companyCodFiscal">Your Company Cod Fiscal:</label>
                            <input type="text" id="companyCodFiscal" name="companyCodFiscal" required>
                        </div>

                        <div style="margin-right: 10px;">
                            <label for="companyBank">Your Company Bank:</label>
                            <input type="text" id="companyBank" name="companyBank" required>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between;">
                        <div style="margin-right: 10px;">
                            <label for="recipientName">Recipient Name:</label>
                            <select id="recipients" class="form-control" style="width: 180px;">
                                <option value=""></option>
                                <?php
                                    include('LogIn/dbcon.php');

                                    $sql = "SELECT * FROM Recipient";
                                    $result = $conn->query($sql);

                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['recipient_name'] . '">' . $row['recipient_name'] . '</option>';
                                    }

                                    $conn->close();
                                ?>
                            </select>
                        </div>
        
                        <div style="margin-right: 10px;">
                            <label for="recipientIBAN">Recipient IBAN:</label>
                            <input type="text" id="recipientIBAN" name="recipientIBAN" required>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between;">
                        <div style="margin-right: 10px;">
                            <label for="recipientCodFiscal">Recipient Cod Fiscal:</label>
                            <input type="text" id="recipientCodFiscal" name="recipientCodFiscal" required>
                        </div>

                        <div style="margin-right: 10px;">
                            <label for="recipientBank">Recipient Bank:</label>
                            <input type="text" id="recipientBank" name="recipientBank" required>
                        </div>
                    </div>
            
                    <div style="display: flex; justify-content: space-between;">
                        <div style="margin-right: 10px;">
                            <label for="amount">Amount:</label>
                            <input type="number" id="amount" name="amount" step="0.01" required>  
                        </div>

                        <div style="margin-right: 10px;">
                            <label for="paymentDate">Payment Date:</label>
                            <input style="width:180px;"  type="date" id="paymentDate" name="paymentDate" required>
                        </div>
                    </div>

                <button type="button" id="save">Save</button>
            </form>
        </div>

        <script>
            
            function goBack() {
                window.location.href = 'index.html';
            }

            const login = localStorage.getItem('login');
            const company = localStorage.getItem('company');
            const IBAN = localStorage.getItem('IBAN');
            const Fisc_Code = localStorage.getItem('Fisc_Code');
            const Bank = localStorage.getItem('Bank');

            if(login === '1'){
                document.getElementById('companyName').value = company;
                document.getElementById('companyIBAN').value = IBAN;
                document.getElementById('companyCodFiscal').value = Fisc_Code;
                document.getElementById('companyBank').value = Bank;
            } else if (login === '0'){
                document.getElementById('companyName').value = '';
                document.getElementById('companyIBAN').value = '';
                document.getElementById('companyCodFiscal').value = '';
                document.getElementById('companyBank').value = '';
            }

            var currentDate = new Date();

            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1;
            var year = currentDate.getFullYear();
        
            if (day < 10) {
                day = '0' + day; 
            }
        
            if (month < 10) {
                month = '0' + month;
            }
        
            var formattedDate = year + '-' + month + '-' + day;

            document.getElementById('paymentDate').value = formattedDate;

            let recipients = document.getElementById('recipients');
            recipients.onchange = function() {

                let name = this.value; 

                if(name === '') {
                    recipientIBAN.value = '';
                    recipientCodFiscal.value = '';
                    recipientBank.value = '';
                    return; 
                }

                let xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_recipient.php?name=' + name);
                xhr.send();
                xhr.onload = function() {
                    let data = xhr.responseText;
                    let parts = data.split(';');

                    recipientIBAN.value = parts[0].split('=')[1];
                    recipientCodFiscal.value = parts[1].split('=')[1];
                    recipientBank.value = parts[2].split('=')[1];
                }

            }

            const form = document.getElementById('paymentForm');
            const button = document.getElementById('save');

            button.addEventListener('click', transferMoney);

            function transferMoney() {

                const companyName = document.getElementById('companyName').value; 
                const recipientName = document.getElementById('recipients').value;
                const amount = document.getElementById('amount').value;
                const date = document.getElementById('paymentDate').value;
                const IBAN_Comp = document.getElementById('companyIBAN').value;
                const IBAN_Rec = document.getElementById('recipientIBAN').value;

                const Cod_Fisc_Comp = document.getElementById('companyCodFiscal').value;
                const Cod_Fisc_Rec = document.getElementById('recipientCodFiscal').value;
                const bank_comp = document.getElementById('companyBank').value;
                const bank_rec = document.getElementById('recipientBank').value;

                let paymentDetails = prompt("Introduceti Destinatia platii:")

                if(!paymentDetails) {
                    alert("Destinatia Platii este necesara!");
                    return; 
                }

                const position = localStorage.getItem('position');

                const params = `companyName=${encodeURIComponent(companyName)}&recipientName=${encodeURIComponent(recipientName)}&amount=${encodeURIComponent(amount)}&date=${encodeURIComponent(date)}&IBAN_Comp=${encodeURIComponent(IBAN_Comp)}&IBAN_Rec=${encodeURIComponent(IBAN_Rec)}&Cod_Fisc_Comp=${encodeURIComponent(Cod_Fisc_Comp)}&Cod_Fisc_Rec=${encodeURIComponent(Cod_Fisc_Rec)}&bank_comp=${encodeURIComponent(bank_comp)}&bank_rec=${encodeURIComponent(bank_rec)}&paymentdetails=${encodeURIComponent(paymentDetails)}&position=${encodeURIComponent(position)}`;

                let money = '';


                fetch(`process_payment.php?${params}`)
                    .then(response => response.text()) 
                    .then(result => {
                        const [status, money] = result.split(';');
                    
                        if (status === 'ok' && money) {
                            window.location.href = `index.html?money=${money}`; 
                        } else if(result == "Insufficient funds"){
                            alert(result);
                            return;
                        } else if(result == "The information entered is incorrect"){
                            alert(result);
                            return;
                        } else { 
                            window.location.href = `index.html`; 
                        }
                    })
                    .catch(error => {
                        console.error('Error during payment:', error);
                    });

            }
        </script>
    </body>
</html>
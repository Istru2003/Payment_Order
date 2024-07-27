<?php 
session_start();
include('dbcon.php');
$page_title = "Registration Form";
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert">
                    <?php
                        if(isset($_SESSION['status'])){
                            echo "<h4>".$_SESSION['status']."</h4>";
                            unset($_SESSION['status']);
                        }
                    ?>
                </div>
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Registration Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Name</label> 
                                <input type="text" name="name" class="form-control">
                            </div> 
                            <div class="form-group mb-3">
                                <label for="">Company Name</label> 
                                <select name="company" class="form-control" id="recipientSelect">
                                    <option value=""></option>
                                    <?php
                                    $servername = "localhost";
                                    $username = "root";
                                    $password = "";
                                    $dbname = "Ordin_de_plata";

                                    $conn = new mysqli($servername, $username, $password, $dbname);

                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    $sql = "SELECT company_name FROM Companies";
                                    $result = $conn->query($sql);

                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['company_name'] . '">' . $row['company_name'] . '</option>';
                                    }

                                    $conn->close();
                                    ?>
                                </select>
                            </div> 
                            <div class="form-group mb-3">
                                <label for="">Position</label> 
                                <input list="positions" name="position" id="position" class="form-control">
                                <datalist id="positions">
                                    <option value="Boss">Boss</option>
                                </datalist>
                            </div> 
                            <div class="form-group mb-3">
                                <label for="">Email</label> 
                                <input type="text" name="email" class="form-control">
                            </div> 
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" aria-describedby="passwordToggle">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="passwordToggle" onclick="togglePassword()">Show</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password_confirmation">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" aria-describedby="passwordConfirmationToggle">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="passwordConfirmationToggle" onclick="togglePasswordConfirmation()">Show</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="register_btn" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        var passwordInput = document.getElementById('password');
        var passwordToggle = document.getElementById('passwordToggle');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordToggle.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            passwordToggle.textContent = 'Show';
        }
    }

    function togglePasswordConfirmation() {
        var passwordConfirmationInput = document.getElementById('password_confirmation');
        var passwordConfirmationToggle = document.getElementById('passwordConfirmationToggle');

        if (passwordConfirmationInput.type === 'password') {
            passwordConfirmationInput.type = 'text';
            passwordConfirmationToggle.textContent = 'Hide';
        } else {
            passwordConfirmationInput.type = 'password';
            passwordConfirmationToggle.textContent = 'Show';
        }
    }

</script>

<?php include('includes/footer.php'); ?>
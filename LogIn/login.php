<?php 
session_start();
if (isset($_SESSION["authenticated"])) {
    $_SESSION['status'] = "You are already Logged In";
    header("Location: dashboard.php");
    exit(0);
} 
$page_title = "Login Form";
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                    <?php
                        if(isset($_SESSION['status'])){
                            ?>
                            <div class="alert alert-success">
                                <h5><?= $_SESSION['status']; ?></h5>
                            </div>
                            <?php
                            unset( $_SESSION['status']);
                        }       
                    ?>
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Login Form</h5>
                    </div>
                    <div class="card-body">

                        <form action="logincode.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Email</label> 
                                <input type="text" name="email" class="form-control" id="email">
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
                            <div class="form-group">
                                <button type="submit" name="login_now_btn" class="btn btn-primary">Login</button>
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

    const login = 0;
    localStorage.setItem('login', login);
    const username = '<?= $_SESSION['auth_user']['username']; ?>';
    localStorage.setItem('username', username);
</script>

<?php include('includes/footer.php'); ?>
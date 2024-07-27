<?php
session_start();
$page_title = "Dashboard";
include('authentication.php');
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
                <div class="card">
                    <div class="card-header">
                        <h4>User Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <h4>Access when you are Logged IN</h4>
                        <hr>
                        <h5>Username: <?= $_SESSION['auth_user']['username']; ?></h5>
                        <h5>Email: <?= $_SESSION['auth_user']['email']; ?></h5>
                        <h5>Company: <?= $_SESSION['auth_user']['company']; ?></h5>
                        <h5>Position: <?= $_SESSION['auth_user']['position']; ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const login = 1;
    localStorage.setItem('login', login);
    const username = '<?= $_SESSION['auth_user']['username']; ?>';
    localStorage.setItem('username', username);
    const money = '<?= $_SESSION['auth_user']['money']; ?>';
    localStorage.setItem('money', money);
    const company = '<?= $_SESSION['auth_user']['company']; ?>';
    localStorage.setItem('company', company);
    const IBAN = '<?= $_SESSION['auth_user']['IBAN']; ?>';
    localStorage.setItem('IBAN', IBAN);
    const Fisc_Code = '<?= $_SESSION['auth_user']['Fisc_Code']; ?>';
    localStorage.setItem('Fisc_Code', Fisc_Code);
    const Bank = '<?= $_SESSION['auth_user']['Bank']; ?>';
    localStorage.setItem('Bank', Bank);
    const position = '<?= $_SESSION['auth_user']['position']; ?>';
    localStorage.setItem('position', position)
</script>

<?php include('includes/footer.php'); ?>

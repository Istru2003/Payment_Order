<?php
session_start();
include("LogIn/dbcon.php");

$company = $_GET['company'];
$sql = "SELECT company_id FROM Companies WHERE company_name='$company'";
$result = mysqli_query($conn, $sql);
if(!$result) {
    echo "The information entered is incorrect 1"; 
    return false; 
}
$row = mysqli_fetch_array($result);
$company_id = $row['company_id'];


$sql = "SELECT money FROM CompanyDetails WHERE company_id=$company_id";
$result = mysqli_query($conn, $sql);
if(!$result) {
    echo "The information entered is incorrect 1"; 
    return false; 
}
$row = mysqli_fetch_array($result);
$money = $row['money'];

echo "ok;{$money}";

$conn->close();

?>
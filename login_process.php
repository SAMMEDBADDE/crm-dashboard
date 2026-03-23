<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 1){

    $user = mysqli_fetch_assoc($result);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];

    if($user['role'] == 'admin'){
        header("Location: admin-dashboard.php");
    } 
    else if($user['role'] == 'counselor'){
        header("Location: counselor-dashboard.php");
    } 
    else {
        echo "Invalid Role";
    }

} else {
    echo "Invalid Credentials";
}
?>
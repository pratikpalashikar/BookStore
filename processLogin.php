<?php include 'connection.php';
/**
 * Created by PhpStorm.
 * User: Pratik
 * Date: 11/24/2016
 * Time: 5:14 PM
 */

session_start();

$username = $_POST['username'];
$password = $_POST['password'];


if(isset($username) && isset($password)){
    // if the username and the password is set
    $sql = "SELECT username,password from customers where username='$username';";
    $result = $conn->query($sql);

    $row_cnt = mysqli_num_rows($result);
    if($row_cnt == 0){
        echo "Login Unsuccessfull";
        echo "<script type='text/javascript'>
                window.location.href = '../Assignment5/application/views/page1_Login.php';
            </script>";
    }

    while($row = mysqli_fetch_assoc($result)){
        if($username==$row['username'] && md5($password)==$row['password']){
            $shoppingbasket = mysqli_query($conn, "select basketId from shoppingbasket where username ='$username'");
            $row = mysqli_fetch_array($shoppingbasket);
            $basketId = $row['basketId'];
            $_SESSION['basketId'] = $basketId;
            $_SESSION['username'] = $username;
            header('Location:page2_search.php');
        }else{
            echo "Login Unsuccessfull";
            echo "<script type='text/javascript'>
                window.location.href = '../Assignment5/application/views/page1_Login.php';
            </script>";
        }
    }
}
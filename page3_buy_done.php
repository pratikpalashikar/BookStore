<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Assignment 4</title>
    <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.css">
    <style>
        .resize {
            width: 60%;
        }
        .resize-body {
            width: 80%;
        }
    </style>
    <?php include 'navbar.php';
    ?>
    <?php include 'script_link.php';
    ?>
</head>
<body>
<?php include 'connection.php';

session_start();

if(!isset($_SESSION['username'])){
    header('Location:page1_Login.php');
}

$basketId = $_SESSION['basketId'];
//first get the number of books quantity in the cart
$sqlouter = "select book.title as title, contains.ISBN as ISBN, SUM(contains.number) as total, SUM(contains.number) * book.price as price 
from contains LEFT JOIN book ON contains.ISBN = book.ISBN where contains.basketId = '$basketId' group by contains.ISBN;";

$result = $conn->query($sqlouter);

while($row=mysqli_fetch_assoc($result)){

    $isbn = $row['ISBN'];
    $required = $row['total'];

    $sql = "SELECT * FROM cheapbooks.stocks where ISBN='$isbn'";
    $result2 = $conn->query($sql);
     while($row2 = mysqli_fetch_array($result2)){

         $actual = $row2['number'];
         $warehousecode = $row2['warehouseCode'];
         $username = $_SESSION['username'];
         if( ($required-$actual)>0 && $actual!=0){
                $sql3 = "insert into shippingorder VALUES ('$isbn','$warehousecode','$username','$required')";
                $res = $conn->query($sql3);
                $sql4 = "update stocks set number=0 where isbn='$isbn' and warehouseCode=$warehousecode";
                $conn->query($sql4);
                $required = $required-$actual;
         }else if($required!=0 && ($actual-$required)>=0){
             $actual = $actual-$required;
             $sql3 = "insert into shippingorder VALUES ('$isbn','$warehousecode','$username','$required')";
             $res = $conn->query($sql3);
             $sql4 = "update stocks set number=$actual where isbn='$isbn' and warehouseCode=$warehousecode";
             $conn->query($sql4);
             $required = 0;
         }
     }

}

$sqldelete = "delete  from contains where basketId='$basketId'";
$conn->query($sqldelete);

?>


<div class="container block">
    <div class="panel panel-default resize center-block">
        <div class="panel-heading text-center"><h1>Buy<h1></div>

        <h1 align="center">Thank you for shopping</h1>


    </div>
</div>

</body>
</html>
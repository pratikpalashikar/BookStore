<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Assignment 4</title>
    <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.css">
    <?php include 'navbar.php';
    ?>
    <?php include 'script_link.php';
    ?>
    <style>
        .resize {
            width: 60%;
        }
        .resize-body {
            width: 80%;
        }
    </style>
    <script type="text/javascript">

        $(document).ready(function () {

            $('#buy').on('click',function () {
                window.location.href = 'page3_buy_done.php';
            })

        });


    </script>


</head>
<body>
<div class="container block">
    <div class="panel panel-default resize center-block">
        <div class="panel-heading text-center"><h1>Buy<h1></div>
    </div>
</div>
<div class="container">
<div class="row">
        <?php include 'connection.php';
        /**
         * Created by PhpStorm.
         * User: Pratik
         * Date: 11/26/2016
         * Time: 12:12 AM
         */
        session_start();

        if(!isset($_SESSION['username'])){
            header('Location:page1_Login.php');
        }

        $basketId = $_SESSION['basketId'];
        $sql = "select book.title, contains.ISBN, SUM(contains.number) as total, SUM(contains.number) * book.price as price 
from contains LEFT JOIN book ON contains.ISBN = book.ISBN where contains.basketId = '$basketId' group by contains.ISBN;";


        $result = $conn->query($sql);
        $totalPrice = 0;
        echo "<div class=\"panel panel-default\">
                <!-- Default panel contents -->
                <div class=\"panel-heading\">Books</div>
                <div class=\"panel-body\">
                </div>

                <!-- Table -->
                <table class=\"table\">
                <tr><th>Title</th><th>ISBN</th><th># Total Books</th><th>Price</th></tr>";
        while($row = mysqli_fetch_assoc($result)){

            echo "<tr><td>".$row['title']."</td><td>".$row['ISBN']."</td><td>".$row['total']."</td><td>".number_format($row['price'],'2','.','')."</td>
        </tr>";
        $totalPrice+= $row['price'];
        }
        echo "<tr><td>".'Total Price'."</td><td>".''."</td><td>".''."</td><td>".number_format($totalPrice,'2','.','')."</td>
        </tr>";
        echo "</table></div>";

        ?>
    <button id="buy" class="btn btn-primary">Buy</button>
</div>
</div>
</body>
</html>
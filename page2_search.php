<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Assignment 4</title>
    <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.css">
    <style>
        .searchBackground {
            padding: 10px 13px;
            margin-bottom: 14px;
            background-color: #e8e8ee;
            border: 1px solid #c4c4d2;
            border-radius: 6px;
        }
    </style>

    <?php include 'script_link.php';
    ?>
    <script type="text/javascript">

            $(document).ready(function() {
                //Search the text books
                $('#searchByAuthor').on('click',function() {
                        var authorName = $('#title').val();
                        window.location.href = 'page2_search.php?author='+authorName;
                    })

                $('#searchByTitle').on('click',function () {
                    var titleName = $('#title').val();
                    window.location.href = 'page2_search.php?title='+titleName;
                })

                $('#filterButton').on('click',function () {
                    window.location.href = 'page3_buy.php';
                })

            });

    </script>

</head>
<body>
<?php include 'navbar.php'
?>
<div class="container">
<div class="row">
<div class="searchBackground">
    <!--<input type="radio" id="TitleRadioButton"
           value="1"
           name="filter" "> Search By Author : <br>
    --><!--<input type="radio" id="DescRadioButton"
           value="2"
           name="filter"> Search By Book Title : <br>-->
    <input type="text" id="title" class="form-control"/>
    <br>
    <?php include 'connection.php';
    session_start();
    $cartCount = 0;
    $basketId = $_SESSION['basketId'];
    $sql2 = "select SUM(number) as total from contains where basketId='$basketId'";
    $result2 = $conn->query($sql2);

    $row = mysqli_fetch_array($result2);
    $cartCount = $row['total'];

    if($cartCount=='' || $cartCount==null){
        $cartCount=0;
    }

    #print_r($row['number']);
    echo "<div class='btn btn-info' id='result'>Cart Items :".$cartCount."</div><br>";
    ?>

    <br>
    <button id="searchByAuthor" class="btn btn-primary">Search By Author</button>
    <button id="searchByTitle" class="btn btn-primary">Search By Title</button>
    <button id="filterButton" class="btn btn-primary">ShoppingBasket</button>
    <!--fetch the data form the database and display the result here-->
</div>
</div>
<br>
<h1>Book Result</h1>

<hr>
<!--show the result here-->

    <?php include 'connection.php';

    //session_start();

    if(!isset($_SESSION['username'])){
        header('Location:page1_Login.php');
    }

    if(isset($_GET['author']) && $_GET['author']!=null){
        $author = filter_input(INPUT_GET,'author',FILTER_SANITIZE_SPECIAL_CHARS);
        echo "<script type='text/javascript'>
                $(document).ready(function() {
                  $('#TitleRadioButton').prop('checked', true);
                  $('#title').val('".$author."')
                })
            </script>";

    if($_GET['author']!=null && $_GET['author']!='') {

        echo "<div class=\"panel panel-default\">
                <!-- Default panel contents -->
                <div class=\"panel-heading\">Books</div>
                <div class=\"panel-body\">
                </div>

                <!-- Table -->
                <table class=\"table\">
                <tr><th>Book Name</th><th>ISBN</th><th># of Books</th><th>Qty</th><th>Cart</th></tr>";
        $sql = "select * from book where ISBN in (select ISBN from writtenby where SSN in
            (select SSN from author where name LIKE '%$author%'))";

        $result = $conn->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {

            $name = $row['title'];
            $ISBN = $row['ISBN'];

            $noofbooks = mysqli_query($conn, "select number from stocks where ISBN ='$ISBN'");
            $count = 0;
            while ($row = mysqli_fetch_array($noofbooks)) {
                $count += $row['number'];
            }

            if ($count > 1) {
                echo "<tr><td>$name</td><td>$ISBN</td><td>$count</td><td><input id='$ISBN' type='number' max='10' min='1'></td>
        <td><button id='cart' value='Add To Cart' class='btn btn-danger' onclick=\"addToCart('$name','$count','$ISBN')\">Add To Cart</button></td>
        </tr>";
            }

        }
    }
            echo "</table></div>";

    }else if(isset($_GET['title'])) {


        $title = filter_input(INPUT_GET,'title',FILTER_SANITIZE_SPECIAL_CHARS);
        //get the books by author and display
        echo "<script type='text/javascript'>
                $(document).ready(function() {
                  $('#DescRadioButton').prop('checked', true);
                  $('#title').val('".$title."')
                })
            </script>";

        if($_GET['title']!=null && $_GET['title']!='') {
            $sql = "select * from book where title LIKE '%$title%'";
            echo "<div class=\"panel panel-default\">
                <!-- Default panel contents -->
                <div class=\"panel-heading\">Books</div>
                <div class=\"panel-body\">
                </div>

                <!-- Table -->
                <table class=\"table\">
                <tr><th>Book Name</th><th>ISBN</th><th># of Books</th><th>Qty</th><th>Cart</th></tr>";
            $result = $conn->query($sql);

            if (mysqli_num_rows($result) == 0)
                return;

            while ($row = mysqli_fetch_assoc($result)) {

                $name = $row['title'];
                $ISBN = $row['ISBN'];

                $noofbooks = mysqli_query($conn, "select number from stocks where ISBN ='$ISBN'");
                $count = 0;
                while ($row = mysqli_fetch_array($noofbooks)) {
                    $count += $row['number'];
                }

                if ($count > 1) {
                    echo "<tr><td>$name</td><td>$ISBN</td><td>$count</td><td><input id='$ISBN' type='number' max='10' min='1'></td>
        <td><button id='cart' value='AddToCart' class='btn btn-danger' onclick=\"addToCart('$name', '$count', '$ISBN')\"'>Add To Cart</button></td>
        </tr>";
                }
            }

            echo "</table></table></div>";
        }
    }
    ?>
</body>
</html>
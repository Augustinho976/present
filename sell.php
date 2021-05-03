<?php

include 'protect.php';
require 'connection.php';

$sql = "SELECT * FROM products";
$result = mysqli_query($con, $sql) or die( mysqli_error($con) );// executing the query
$rows = mysqli_fetch_all($result, 1);//assoc array
mysqli_close($con);//close the connection

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sell</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php include 'nav.php';?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <?php
            if (isset($_SESSION["products"]))
            $no_of_items = count(array_unique($_SESSION["products"]));
            ?>

            <p class="text-success mb-0">You have <?=$no_of_items ?? 0 ?> items in your cart</p>
            <a href="check-out.php "class="btn btn-info mb-1">Check Out</a>


            <table id="example" class="table table-striped table-bordered">

                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Genre</th>
                    <th>Poster</th>
                    <th>Add</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($rows as $product): ?>
                    <tr>
                        <td> <?= $product["title"] ?> </td>
                        <td> <?= $product["description"] ?> </td>
                        <td> <?= $product["genre"] ?> </td>
                        <td><img src="<?=$product['poster']?>" width="80" height="60" alt=""> </td>
                        <td> <a class="btn btn-info btn-sm" href="add-to-cart.php?id=<?=$product["id"]?>">Add to Cart</a>  </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>

</body>
</html>

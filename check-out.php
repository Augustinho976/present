<?php

include 'protect.php';
require 'connection.php';

if (isset($_REQUEST["customer_id"])){
    //save sales
    $customer_id=$_REQUEST["customer_id"];
    $user_id=$_SESSION["id"];
    $product_ids=$_SESSION["products"];
    $date_sold=date("Y-m-d");
    //save
    foreach ($product_ids as $pid){
        $query="INSERT INTO `sales`(`id`, `user_id`, `product_id`, `customer_id`, `date_sold`)
      VALUES (null,$user_id,$pid,$customer_id,'$date_sold')";
        mysqli_query($con,$query) or die(mysqli_error($con));
    }
    //clear cart
   // unset($_SESSION["products"]);// removes completely
    $_SESSION["products"]=[];//empty array

}
if (isset($_GET["id"])){// romes from cart
    $_SESSION["products"]=array_diff($_SESSION["products"],[$_GET["id"]]); // [1,2,3] [1]= [2][3]
}
// if there is nothing in your cart re-directs to sell page
if(count($_SESSION["products"])==0){
    header("location:sell.php");
}
$ids=array_unique($_SESSION["products"]);
//print_r($ids);
$data=implode(",",$ids);
//echo "$data";//converts data from an array to a string
//die();
$sql = "SELECT * FROM products WHERE id IN($data)";
$result = mysqli_query($con, $sql) or die( mysqli_error($con) );// executing the query
$rows = mysqli_fetch_all($result, 1);//assoc array

//fetch customers
$sql2 = "SELECT * FROM customers";
$result2 = mysqli_query($con, $sql2) or die( mysqli_error($con) );// executing the query
$customers = mysqli_fetch_all($result2, 1);//assoc array


mysqli_close($con);//close the connection

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
</head>
<body>
<?php include 'nav.php';?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
<!--            --><?php
//            if (isset($_SESSION["products"]))
//                $no_of_items = count(array_unique($_SESSION["products"]));
//            ?>
<!---->
<!--            <p class="text-success mb-0">You have --><?//=$no_of_items ?? 0 ?><!-- items in your cart</p>-->
<!--            <a href="check-out.php "class="btn btn-info mb-1">Check Out</a>-->
            <form action="check-out.php" method="post" class="form-inline mt-2 mb-2">
                <div class="form-group">
            <select name="customer_id" class="form-control">
                <?php foreach ($customers as $person): ?>
                    <option value="<?=$person["id"]?>"> <?=$person["names"]?> </option>

                <?php endforeach; ?>

            </select>

                </div>
                <button class="btn btn-info btn-sm ml-2">Buy</button>
            </form>


            <table id="example" class="table table-striped table-bordered">

                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Genre</th>
                    <th>Poster</th>
                    <th>Remove</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($rows as $product): ?>
                    <tr>
                        <td> <?= $product["title"] ?> </td>
                        <td> <?= $product["description"] ?> </td>
                        <td> <?= $product["genre"] ?> </td>
                        <td><img src="<?=$product['poster']?>" width="80" height="80" alt=""> </td>
                        <td> <a class="btn btn-danger btn-sm" href="check-out.php?id=<?=$product["id"]?>">Remove</a>  </td>
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

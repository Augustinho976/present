<?php
include 'protect.php';
include 'protect-admins.php';
require 'connection.php';

$sql = "SELECT 
        customers.names AS customer, products.title, products.price, sales.date_sold, users.names AS user
        FROM customers
        JOIN sales ON customers.id = sales.customer_id
        JOIN products ON products.id = sales.product_id
        JOIN users ON users.id = sales.user_id ORDER BY sales.date_sold DESC";

if (isset($_GET["start_date"]) and  isset($_GET["end_date"]))
{
    $start = $_GET["start_date"];
    $end = $_GET["end_date"];

    $sql = "SELECT 
        customers.names AS customer, products.title, products.price, sales.date_sold, users.names AS user
        FROM customers
        JOIN sales ON customers.id = sales.customer_id
        JOIN products ON products.id = sales.product_id
        JOIN users ON users.id = sales.user_id 
        WHERE sales.date_sold BETWEEN '$start' AND '$end'
        ORDER BY sales.date_sold DESC";

}
$result = mysqli_query($con, $sql) or die( mysqli_error($con) );// executing the query
$rows = mysqli_fetch_all($result, 1);//assoc array
mysqli_close($con);//close the connection
$today=date('Y-m-d');
$last_seven=date('Y-m-d', strtotime('-7 dAYS'));
$last_one_month=date('Y-m-d', strtotime('-1 month'));
$last_two_weeks=date('Y-m-d', strtotime('-14 dAYS'));


$total = 0;
foreach ($rows as $item){
    $total += $item["price"] * 1;
}
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Report-sale</title>
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
<!--                --><?php
//                if (isset($_SESSION["products"]))
//                    $no_of_items = count(array_unique($_SESSION["products"]));
//                ?>
<!---->
<!--                <p class="text-success mb-0">You have --><?//=$no_of_items ?? 0 ?><!-- items in your cart</p>-->
<!--                <a href="check-out.php "class="btn btn-info mb-1">Check Out</a>-->
                <form action="reports.php" method="get" class="form-inline mt-2 mb-3">

                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" max="<?=date('Y-m-d')?>" value="<?=date('Y-m-d')?>"class="form-control" name="start_date">
                    </div>

                    <div class="form-group ml-3">
                        <label>End Date</label>
                        <input type="date" max="<?=date('Y-m-d')?>" value="<?=date('Y-m-d')?>" class="form-control" name="end_date">
                    </div>

                    <button class="btn btn-info ml-3">Filter</button>

                    <button type="reset" class="btn btn-warning ml-3">Clear Fields</button>

                </form>
                <a class="btn btn-sm btn-dark" href="reports.php?start_date=<?=$last_seven?>&end_date=<?=$today?>">Report For last 7 Days</a>
                <a class="btn btn-sm btn-dark" href="reports.php?start_date=<?=$last_one_month?>&end_date=<?=$today?>">Report For last 30 Days</a>
                <a class="btn btn-sm btn-dark" href="reports.php?start_date=<?=$last_two_weeks?>&end_date=<?=$today?>">Report For last 2 weeks</a>

                <h4 class="text-success">Total Sales is Ksh <?=$total?> </h4>

                <table id="example" class="table table-striped table-bordered">

                    <thead>
                    <tr>
                        <th>CUSTOMER</th>
                        <th>SERVED BY</th>
                        <th>TITLE</th>
                        <th>PRICE</th>
                        <th>DATE SOLD</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($rows as $sale): ?>
                        <tr>
                            <td><?=$sale['customer']?></td>
                            <td><?=$sale['user']?></td>
                            <td><?=$sale['title']?></td>
                            <td><?=$sale['price']?></td>
                            <td><?=$sale['date_sold']?></td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <th></th>
                    <th></th>
                    <th>Total sales</th>
                    <th class="total"></th>
                    <th></th>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.24/api/sum().js"></script>
    <script>
        // $(document).ready(function() {
        //     $('#example').DataTable();
        // } );
        $('#example').DataTable( {
            drawCallback: function () {
                var api = this.api();
                $('.total').html(
                    api.column( 3, {page:'current'} ).data().sum()
                );
            }
        }); //
    </script>

    </body>
    </html>


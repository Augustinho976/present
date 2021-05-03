<?php
if (isset($_REQUEST["password"])) {
    $email = $_REQUEST["email"];
    $password = $_REQUEST["password"];

    require 'connection.php';
    //prevents hacking through forms
    $query = mysqli_prepare($con, "SELECT* FROM users WHERE email = ?");
    mysqli_stmt_bind_param($query, "s", $email) ;//add the data
    mysqli_stmt_execute($query) ;//execute the query
    $result = mysqli_stmt_get_result($query);
    //   $sql="SELECT FROM users WHERE email = '$email' ";
    // $result=mysqli_query($con,$sql);
    $num_of_users = mysqli_num_rows($result);
    if (mysqli_num_rows($result) == 1) {
        $user=mysqli_fetch_assoc($result);
       // var_dump($user);
       // die();
        $hash=$user["password"];
        if (password_verify($password,$hash)){
            //success
            session_start();
            $_SESSION["names"]= $user["names"];//123456
            $_SESSION["id"]= $user["id"];
            $_SESSION["admin"]= $user["admin"];
            $_SESSION["logged_in"]= true;
            header("location:sell.php");
        }else{
            //failed
            setcookie("error", "Wrong Username or Password", time() + 3);
            header("location:login.php");
        }


    } else {
        setcookie("error", "Wrong Username or Password", time() + 3);
        header("location:login.php");


    }
    mysqli_close($con);
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-5">
            <h4>Sign In</h4>
            <?php include 'alerts.php';?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button class="btn  btn-outline-dark">Sign In</button>


            </form>
        </div>
    </div>
</div>


</body>
</html>

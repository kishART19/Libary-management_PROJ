<?php
    session_start();
    if(isset($_SESSION['admin'])){
        header('location:home.php');
    }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>Library Management system</b>
    </div>
 
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
<!--LOG IN FORM-->
        <form action="login.php" method="POST">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" placeholder="input Username" required autofocus>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="input Password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
                </div>
            </div>
        </form>
    </div>
    <?php
    //input error if session is error
        if(isset($_SESSION['error'])){
            echo "
                <div class='callout callout-danger text-center mt20'>
                    <p>".$_SESSION['error']."</p>
                </div>
            ";
            unset($_SESSION['error']);
        }
    ?>
</div>
   
<?php include 'includes/scripts.php' ?>
</body>
</html>

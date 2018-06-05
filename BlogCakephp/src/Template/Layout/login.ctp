<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Log in</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> 
        <?php
        echo $this->Html->css(array(
            '../backend/css/bootstrap.min.css',
            '../backend/css/AdminTheme.min.css',
            '../backend/css/blue.css',
            '../backend/css/messenger.css',
            '../backend/css/messenger-theme-future.css'
        ));
        ?> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <?php
        echo $this->Html->script(array(
            '../backend/js/jquery-2.2.3.min.js',
            '../backend/js/bootstrap.min.js',
            '../backend/js/fastclick.js',
            '../backend/js/icheck.min.js',
            '../backend/js/messenger.min.js',
            '../backend/js/messenger-theme-future.js',
			'../backend/js/custome.js'
        ));
        ?>        
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="#"><b>Super</b>Admin</a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <?php echo $this->Flash->render('flash'); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->     
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>

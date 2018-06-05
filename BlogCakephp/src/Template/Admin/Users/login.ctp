<p class="login-box-msg">&nbsp;</p>
<?php echo $this->Form->create('User', array('action' => 'login', 'class' => 'form-signin')); ?>
<div class="form-group has-feedback">
    <input type="text" name="username" class="form-control" placeholder="User Name">
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <input type="password" name="password" class="form-control" placeholder="Password">
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="row">
    <div class="col-xs-8">
        <div class="checkbox icheck">
            <label>
                <input type="checkbox" value="on" name="remember_me"> Remember Me
            </label>
        </div>
    </div>
    <div class="col-xs-4">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<?php $this->setSiteTitle('Login')?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<div class="grid-auto gap-outer-2">
    <h2 class="center center-text">Log In</h2>
    <form class="col grid-auto center" action="<?=PROOT?>register/login" method="post">
        <div class="form-group-text">
            <input type="text" name="username" value="<?=$this->post['username']?>">
            <label>Username</label>
            <?= $this->displayErrors['username']; ?>
        </div>
        <div class="form-group-password">
            <input type="password" name="password" value="<?=$this->post['password']?>">
            <label>Password</label>
            <?= $this->displayErrors['password']; ?>
        </div>
        <?= $this->displayErrors['password']; ?>
        <div class="form-group-switch">
            <input type="checkbox" name="rememberme">
            <label>Remember me</label>
        </div>
        <input type="submit" name="submit" id="submit" value="Submit">
    </form>
    <div class="no-account center-text">
        <a href="<?=PROOT?>register/register">Create Account</a>
    </div>
</div>
<?php $this->end(); ?>
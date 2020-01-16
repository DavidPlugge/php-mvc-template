<?php $this->setSiteTitle('Register')?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>

<div class="grid-auto gap-outer-2">
    <h2 class="center center-text">Register</h2>
    <form class="col grid gap-inner-1 center" action="<?=PROOT?>register/register" method="post">
        <div class="col-6 form-group-text">
            <input type="text" name="firstname" value="<?=$this->post['firstname']?>">
            <label>First Name</label>
            <?= $this->displayErrors['firstname']; ?>
        </div>
        <div class="col-6 form-group-text">
            <input type="text" name="lastname" value="<?=$this->post['lastname']?>">
            <label>Last Name</label>
            <?= $this->displayErrors['lastname']; ?>
        </div>
        <div class="col-6 form-group-text">
            <input type="text" name="email" value="<?=$this->post['email']?>">
            <label>Email</label>
            <?= $this->displayErrors['email']; ?>
        </div>
        <div class="col-6 form-group-text">
            <input type="text" name="username" value="<?=$this->post['username']?>">
            <label>Username</label>
            <?= $this->displayErrors['username']; ?>
        </div>
        <div class="col-6 form-group-password">
            <input type="password" name="password" value="<?=$this->post['password']?>">
            <label>Password</label>
            <?= $this->displayErrors['password']; ?>
        </div>
        <div class="col-6 form-group-password">
            <input type="password" name="confirm" value="<?=$this->post['confirm']?>">
            <label>Confirm Password</label>
            <?= $this->displayErrors['confirm']; ?>
        </div>
        <input class="col-12" type="submit" name="submit" id="submit" value="Submit">
    </form>
    <div class="has-account center-text">
        <a href="<?=PROOT?>register/login">Already have an account?</a>
    </div>
</div>
<?php $this->end(); ?>
<?php $this->setSiteTitle('Profile Settings')?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<div class="space-2"></div>
<div class="grid">
    <div class="col-1"></div>
    <div class="col-4">
        <img src="https://www.hr-fernsehen.de/sendungen-a-z/hallo-hessen/aktionen/wetter-bilder_lydia_gierhardt_gedern-100~_t-1504273413628_v-16to9.jpg" alt="">
        <form action="<?=PROOT?>profile/settings" method="post">
            <div class="form-group-file">
                <label for="uploadProfilePicture">Upload Picture</label>
                <input type="file" id="uploadProfilePicture" name="profilePicture">
                <?= $this->displayErrors['password']; ?>
            </div>
            <input class="center-text" type="submit" value="Submit" name="submitProfilePictureUpload">
        </form>
    </div>
    <div class="col-2"></div>
    <div class="col-4 grid-auto">
        <p>Username:<span style="margin-left: 1em"><?=$this->username?></span></p>
        <p>First Name:<span style="margin-left: 1em"><?=$this->firstname?></span></p>
        <p>Last Name:<span style="margin-left: 1em"><?=$this->lastname?></span></p>
        <p>Email:<span style="margin-left: 1em"><?=$this->email?></span></p>
        <button class="btn toggle" role="button" data-toggle="#changePassword" data-toggle-class="active">Change Password</button>
        </div>
    </div>
    <div class="col-1"></div>
</div>

<div class="<?= ($this->changePasswordModal) ? "modal active" : "modal" ?>" id="changePassword">
    <div class="modal-container">
        <h1>Change Password</h1>
        <form method="post">
            <div class="form-group-password">
                <input type="password" name="password" value="<?=$this->post['password']?>">
                <label>Current password</label>
                <?= $this->displayErrors['password']; ?>
            </div>
            <div class="form-group-password">
                <input type="password" name="newpassword" value="<?=$this->post['newpassword']?>">
                <label>New password</label>
                <?= $this->displayErrors['newpassword']; ?>
            </div>
            <div class="form-group-password">
                <input type="password" name="confirm" value="<?=$this->post['confirm']?>">
                <label>Confirm new password</label>
                <?= $this->displayErrors['confirm']; ?>
            </div>
            <input class="center-text" type="submit" value="Submit" name="submitChangePassword">
        </form>
    </div>
<?php $this->end(); ?>
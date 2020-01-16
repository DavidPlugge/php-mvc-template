<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$this->siteTitle()?></title>
    <link rel="stylesheet" href="<?=PROOT?>css/style.css">
    <script  src="<?=PROOT?>js/base.js"></script>
    <script  src="<?=PROOT?>js/baseCss.js"></script>
    <script  src="<?=PROOT?>js/custom.js"></script>
    <script src="<?=PROOT?>fonts/fontawesome/js/fontawesome-all.js"></script>
    <script src="<?=PROOT?>js/ServerSocketClient/socket.io.js"></script>
    <script src="<?=PROOT?>js/ServerSocketClient/client.js"></script>

    <?=$this->content('head')?>


    <info id='hasAccess' data-value='<?= currentUser() ? "true" : "false"?>' data-user-name='<?=currentUser() ? currentUser()->username : ""?>' data-user-id='<?= currentUser() ? password_hash(currentUser()->id, PASSWORD_DEFAULT) : ""?>' style='display: none'></info>

</head>
<body>
    <?php include('default/header.php'); ?>
    <?=$this->content('body')?>
</body>
</html>
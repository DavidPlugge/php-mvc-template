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

    <script src="/js/ServerSocketClient/ClientSocket.js"></script>
    <script src="<?=PROOT?>js/Canvas/canvas.js"></script>
    <script src="<?=PROOT?>js/Canvas/canvasUtility.js"></script>
    <script src="<?=PROOT?>js/Canvas/canvasElements.js"></script>
    <script src="<?=PROOT?>js/Canvas/sprite.js"></script>
    <script src="<?=PROOT?>js/Canvas/Pen.js"></script>
    <script src="<?=PROOT?>js/Mouse/Mouse.js"></script>

    <?=$this->content('head')?>

</head>
<body>
<?=$this->content('body')?>
</body>
</html>
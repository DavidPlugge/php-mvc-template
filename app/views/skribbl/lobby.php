<?php $this->setSiteTitle('Skribbl')?>
<?php $this->start('head'); ?>

<script type="text/javascript">
    onDocumentReady(function () {
        const socket = new ClientSocket('david-plugge.de', '9000', 'test/TestServer.php');
        socket.onOpen(function () {
            console.log('Socket open');
        });
        socket.onClose(function () {
            console.log('Socket close');
        });
        socket.on("test", function (src) {
            const img = new Image();
            img.onload = function () {
                canvas.context.drawImage(img, 0, 0);
            };
            img.src = src;
        });


        const canvas = new Canvas("canvasGame", 800, 600);
        const pen = new Pen(canvas);

        canvas.setBackgroundColor(255,255,255);
        pen.color = new RGBAcolor(0,150,0);
        pen.activate();

        pen.onMove(function () {
            socket.send("test",canvas.canvas.toDataURL());
        });
    });
</script>

<style type="text/css">
    body {
        background-color: #155a93;
    }

    .game-header, .sidebar-left, .sidebar-right {
        width: 100%;
        height: 100%;
        background-color: #eeeeee;
    }

    .game-header > * {
        padding: 0 1em;
        line-height: 2.5em;
        font-size: 1.3em;
    }

    #timer span{
        margin-left: 1em;
    }

    #current-word .white-space {
        border: none;
    }
    #current-word span {
        line-height: 1.2em;
        display: inline-block;
        width: 1em;
        border-bottom: 3px solid #000000;
        margin: 0 .1em;
        text-align: center;
    }

    .sidebar-left {

    }

    .sidebar-right {

    }

    #canvasGame {
        background: #ffffff;
        width: 100%;
        height: auto;
    }

    #testBtn {
        font-size: 2em;
        padding: .3em;
        background: #004f64;
        color: #ffffff;
        margin: 0 auto;
        border: 2px solid #777777;
        display: block;
    }
</style>

<?php $this->end(); ?>
<?php $this->start('body'); ?>

<div class="grid gap-all-1">
    <div class="col-12 game-header grid">
        <div class="col-2" id="timer">
            <i class="far fa-clock"></i><span>90</span>
        </div>
        <div class="col-2" id="round">Round</div>
        <div class="col-8 center-text" id="current-word">
            <span>
                1
            </span>
            <span>
                b
            </span>
            <span class="white-space"></span>
            <span>
                c
            </span>
        </div>
    </div>
    <div class="col-3 sidebar-left">
        <button id="testBtn">Test</button>
    </div>
    <div class="col-6">
        <canvas width="800" height="600" id="canvasGame"></canvas>
    </div>
    <div class="col-3 sidebar-right">

    </div>
</div>

<?php $this->end(); ?>

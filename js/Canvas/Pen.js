class Pen {
    constructor (canvas) {
        this.color = new RGBAcolor(255,0,0);
        this.fontfamily = "Arial";
        this.penSize = 20;
        this.canvas = canvas;
        this.ctx = canvas.context;
        this.penActive = false;
        this.penDown = false;
        this.mouse = new Mouse(this.canvas.canvas);
        this.mouse.onDown = this.down.bind(this);
        this.mouse.onUp = this.up.bind(this);
        this.mouse.onLeave = this.up.bind(this);
        this.mouse.onMove = this.move.bind(this);
        this.onmove = null;
        this.ondown = null;
        this.onup = null;
    }

    toggle() {
        if (this.penActive) {
            this.deactivate();
            this.up();
        } else this.activate();
    }
    activate() {
        this.penActive = true;
    }
    deactivate() {
        this.penActive = false;
        this.penDown = false;
        this.ctx.stroke();
    }
    isActive() {
        return this.penActive;
    }
    isDown() {
        return this.penDown;
    }

    down() {
        if (this.penActive && !this.penDown) {
            this.penDown = true;
            this.prevX = this.canvas.mouse.x;
            this.prevY = this.canvas.mouse.y;
            this.drawLine(this.prevX, this.prevY);
            if (this.ondown !== null) this.ondown();
        }
    }

    move() {
        if (this.penDown && this.penActive) {

            this.drawLine(this.mouse.x, this.mouse.y, this.mouse.lastX, this.mouse.lastY);

            if (this.onmove !== null) this.onmove();
        }
    }

    drawLine(x,y,x1 = x,y1 = y, color = undefined) {
        this.ctx.lineCap = "round";
        this.ctx.lineJoin = "round";
        if (color === undefined) color = this.color;
        color = color.rgbaString();
        this.ctx.lineWidth = this.penSize;
        this.ctx.strokeStyle = color;
        this.ctx.beginPath();
        this.ctx.moveTo(x1, y1);
        this.ctx.lineTo(x, y);
        this.ctx.stroke();
        this.ctx.closePath();
    }

    onMove(callback) {
        this.onmove = callback.bind(this);
    }
    onDown(callback) {
        this.ondown = callback.bind(this);
    }
    onUp(callback) {
        this.onup = callback.bind(this);
    }

    up() {
        if (this.penDown) {
            this.penDown = false;
            this.prevX = undefined;
            this.prevY = undefined;
            if (this.onup !== null) this.onup();
        }
    }
}
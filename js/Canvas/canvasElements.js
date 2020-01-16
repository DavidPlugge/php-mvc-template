class CanvasElement {
    constructor(dx=0, dy=0) {
        this.borderWidth = 1;
        this.background = new RGBAcolor();
        this.borderColor = new RGBAcolor();
        this.stroke = false;
        this.fill = false;
        this.updateFunction = null;
        this.velocity = {
          x: dx,
          y: dy
        }
    }
    render (ctx){
        ctx.beginPath();
        if (this.background !== null) {
            ctx.fillStyle = this.background.rgbaString();
            this.fill = true;
        } else this.fill = false;
        if (this.borderColor !== null && this.borderWidth > 0) {
            ctx.strokeStyle = this.borderColor.rgbaString();
            ctx.lineWidth = this.borderWidth;
            this.stroke = true;
        } else this.stroke = false;
    };
    setUpdateFunction(callback) {
    this.updateFunction = callback;
    }
    update() {
        if (this.updateFunction !== null) {
        this.updateFunction();
        this.x += this.velocity.x;
        this.y += this.velocity.y;
        }
    }
}

class CanvasElementList {
    constructor () {
        this.elements = [];
    }
    add(element) {
        this.elements.push(element);
    }
    update() {
        for (let i = 0; i < this.elements.length; i++) {
            this.elements[i].update();
        }
    }
    remove(element) {
        const index = array.indexOf(element);
        if (index > -1) {
            this.elements.splice(index, 1);
        }
    }
    render(ctx) {
        for (let i = 0; i < this.elements.length; i++) {
            this.elements[i].render(ctx);
        }
    }
}

class CRectangle extends CanvasElement {
    constructor (x,y,w,h, dx, dy) {
        super(dx,dy);
        this.x = x;
        this.y = y;
        this.w = w;
        this.h = h;
    }
    render (ctx){
        super.render(ctx);

        if (this.stroke) {
            ctx.strokeRect(this.x, this.y, this.w, this.h);
        }
        if (this.fill) {
            ctx.fillRect(this.x, this.y, this.w, this.h);
        }
    };
}

class CCircle extends CanvasElement {
    constructor (x,y,r, dx, dy) {
        super(dx,dy);
        this.x = x;
        this.y = y;
        this.radius = r;
    }
    render (ctx){
        super.render(ctx);

        ctx.arc(this.x, this.y, this.radius, 0, 2*Math.PI, false);

        if (this.stroke) {
            ctx.stroke();
        }
        if (this.fill) {
            ctx.fill();
        }
    }
}
class Mouse {
    constructor(element = document) {
        this.element = element;
        this.x = undefined;
        this.y = undefined;
        this.lastX = undefined;
        this.lastY = undefined;
        this.element.addEventListener("mousemove", this.listenMove.bind(this));
        this.element.addEventListener("mousedown", this.listenDown.bind(this));
        this.element.addEventListener("mouseup", this.listenUp.bind(this));
        this.element.addEventListener("mouseleave", this.listenLeave.bind(this));
        this.onMove = null;
        this.onDown = null;
        this.onUp = null;
        this.onLeave = null;
    }

    listenMove() {
        if (this.onMove !== null) this.onMove();
        this.lastX = this.x;
        this.lastY = this.y;
        this.x = event.offsetX;
        this.y = event.offsetY;
    }
    listenDown() {
        this.x = event.offsetX;
        this.y = event.offsetY;

        if (this.onDown !== null) this.onDown();
    }
    listenUp() {
        if (this.onUp !== null) this.onUp();
    }
    listenLeave() {
        if (this.onLeave !== null) this.onLeave();
        this.x = undefined;
        this.y = undefined;
        this.lastX = undefined;
        this.lastY = undefined;
    }
}
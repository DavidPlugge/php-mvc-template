class Canvas {
    constructor(canvas_tag_id, w = 600, h = 400) {
        this.canvas = document.querySelector("#" + canvas_tag_id);
        this.canvas.style.cursor = "crosshair";
        this.context = this.canvas.getContext('2d');
        this.initWidth = w;
        this.initHeight = h;
        this.canvas.width = w;
        this.canvas.height = h;
        this.animationCallback = null;
        this.fps = 60;
        this.backgroundColor = "rgba(0,0,0,1)";
        this.fullscreenListener = function resizeListener() {
            this.canvas.width = window.innerWidth;
            this.canvas.height = window.innerHeight;
        };

        this.mouse = {
            x: undefined,
            y: undefined
        };

        this.addListener("mousemove", function() {
            this.mouse.x = event.x - this.canvas.offsetLeft;
            this.mouse.y = event.y - this.canvas.offsetTop;
        });
        this.addListener("mouseleave", function() {
            this.mouse.x = undefined;
            this.mouse.y = undefined;
        });
    }
    addListener(event, callback, bind = this) {
        this.canvas.addEventListener(event, callback.bind(bind));
    }
    setBackgroundColor(r,g,b,a=1) {
        this.backgroundColor = 'rgba('+r+','+g+','+b+','+a+')';
        this.canvas.style.backgroundColor = this.backgroundColor
    }
    clear() {
        this.context.fillStyle = this.backgroundColor;
        this.context.fillRect(0,0,this.width(),this.height())
    }
    setFullscreen(fullscreen = true) {
        if (fullscreen) {
              this.canvas.style.position = "fixed";
              this.canvas.style.top = "0";
              this.canvas.style.left = "0";
              this.canvas.style.zIndex = "10000";
              this.canvas.height = window.innerHeight;
              this.canvas.width = window.innerWidth;
              window.addEventListener('resize', this.fullscreenListener.bind(this));
        } else {
              this.canvas.style.position = "";
              this.canvas.style.top = "";
              this.canvas.style.left = "";
              this.canvas.style.zIndex = "";
              this.canvas.height = this.initHeight;
              this.canvas.width = this.initWidth;
              window.removeEventListener("resize", this.fullscreenListener);
        }
    }
    height() {return this.canvas.height;}
    width() {return this.canvas.width;}
    setAnimation(callback) {
        this.animationCallback = callback;
    }
    startAnimation() {
      if (this.animationCallback !== null) {
          this.animation = setInterval(this.animationCallback.bind(this), 1000/this.fps);
      }
    }
    stopAnimation() {
        clearInterval(this.animation);
    }
    render(item) {
        item.render(this.context);
    }
    getRandomRectangle(minw, minh, maxw = minw, maxh = minh) {
        const w = randomInt(minw, maxw);
        const h = randomInt(minh, maxh);
        const x = randomInt(0, this.width() - w);
        const y = randomInt(0, this.height() - h);
        return new CRectangle(x,y,w,h);
    }
    getRandomCircle(minr, maxr = minr) {
        const radius = randomInt(minr, maxr);
        const x = randomInt(radius, this.width() - radius);
        const y = randomInt(radius, this.height() - radius);
        return new CCircle(x,y,radius);
    }
}
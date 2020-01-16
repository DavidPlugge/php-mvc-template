class Sprite {
  constructor(filename, is_pattern = false) {
    this.img = null;
    this.pattern = null;
    this.TO_RADIANS = Math.PI / 180;

    if (filename !== undefined && filename !== "" && filename !== null) {
      this.img = new Image();
      this.img.src = filename;
      if (is_pattern)
        this.pattern = Context.context.createPattern(this.img, 'repeat');
    } else console.log('Unabled to load sprite');
  }

  draw(x,y,w,h) {
    
    if (this.pattern !== null) {
      Context.context.fillStyle = this.pattern;
      Context.context.fillRect(x,y,w,h);
    } else {
      if (w === undefined || h === undefined) {
        Context.context.drawImage(this.img, x,y,this.img.width, this.img.height);
      } else {
        Context.context.drawImage(this.img,x,y,w,h);
      }
    }
  }

  rotate(angle,x=0,y=0, w,h, offX,offY) {
    if (w === undefined) w = this.img.width;
    if (h === undefined) h = this.img.height;
    if (offX === undefined) offX = w / 2;
    if (offY === undefined) offY = h / 2;
    Context.context.save();
    Context.context.translate(offX+x,offY+y);
    Context.context.rotate(angle * this.TO_RADIANS);
    this.draw(-offX,-offY,w,h);
    Context.context.restore();
  }
}
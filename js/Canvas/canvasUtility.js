function rotateVelocity(velocity, angle) {
    return rotatedVelocities = {
        x: velocity.x * Math.cos(angle) - velocity.y * Math.sin(angle),
        y: velocity.x * Math.sin(angle) + velocity.y * Math.cos(angle)
    };
}
function resolveCollision(particle, otherParticle) {
    const xVelocityDiff = particle.velocity.x - otherParticle.velocity.x;
    const yVelocityDiff = particle.velocity.y - otherParticle.velocity.y;

    const xDist = otherParticle.x - particle.x;
    const yDist = otherParticle.y - particle.y;

    // Prevent accidental overlap of particles
    if (xVelocityDiff * xDist + yVelocityDiff * yDist >= 0) {

        // Grab angle between the two colliding particles
        const angle = -Math.atan2(otherParticle.y - particle.y, otherParticle.x - particle.x);

        // Store mass in var for better readability in collision equation
        const m1 = particle.mass;
        const m2 = otherParticle.mass;

        // Velocity before equation
        const u1 = rotateVelocity(particle.velocity, angle);
        const u2 = rotateVelocity(otherParticle.velocity, angle);

        // Velocity after 1d collision equation
        const v1 = { x: u1.x * (m1 - m2) / (m1 + m2) + u2.x * 2 * m2 / (m1 + m2), y: u1.y };
        const v2 = { x: u2.x * (m1 - m2) / (m1 + m2) + u1.x * 2 * m2 / (m1 + m2), y: u2.y };

        // Final velocity after rotating axis back to original location
        const vFinal1 = rotateVelocity(v1, -angle);
        const vFinal2 = rotateVelocity(v2, -angle);

        // Swap particle velocities for realistic bounce effect
        particle.velocity.x = vFinal1.x;
        particle.velocity.y = vFinal1.y;

        otherParticle.velocity.x = vFinal2.x;
        otherParticle.velocity.y = vFinal2.y;
    }
}
function randomInt(start, end) {
    return Math.floor(Math.random()*(end-start) + .5) + start;
}
function randomColor(colors) {
    const color = colors[randomInt(0, colors.length-1)];
    return new RGBAcolor(color[0], color[1], color[2]);
}
function distance (x1,y1, x2,y2) {
    return Math.sqrt(Math.pow(Math.abs(x1-x2), 2)+Math.pow(Math.abs(y1-y2), 2));
}
class RGBAcolor {
    constructor (r=0,g=0,b=0,a=1) {
        this.r = r;
        this.g = g;
        this.b = b;
        this.a = a;
    }
    rgbaString() {
        return 'rgba('+this.r+','+this.g+','+this.b+','+this.a+')';
    }
    random() {
        this.r = randomInt(0,255);
        this.g = randomInt(0,255);
        this.b = randomInt(0,255);
        this.a = randomInt(0,255);
    }
}
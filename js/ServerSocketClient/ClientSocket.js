class ClientSocket {
    constructor (host, port, path) {
        this.host = host;
        this.port = port;
        this.path = path;
        this.socket = null;
        this.url = "ws://" + this.host + ":" + this.port + "/" + this.path;
        this.init();
        this.pendingMessages = [];
        this.tick = null;
        this.msgCallbacks = [];
        this.socket.onmessage = this.onMessage.bind(this);
    }

    init() {
        if (this.socket == null) {
            try {
                this.socket = new WebSocket(this.url);
                this.tick = setInterval(this._tick.bind(this), 5000);
            } catch (e) {
                //console.log("ClientSocket: "+e.message);
            }
        }
    }

    _tick() {
        if (this.pendingMessages.length > 0) {
            const newPendingMSGs = [];
            for (let i = 0; i < this.pendingMessages.length; i++) {
                if (!this.send(this.pendingMessages[i])) {
                    newPendingMSGs.push(this.pendingMessages[i]);
                }
            }
            this.pendingMessages = newPendingMSGs;
        }
    }

    quit() {
        if (this.socket != null) {
            this.socket.close();
            this.socket = null;
            clearInterval(this.tick);
        }
    }

    reconnect() {
        this.quit();
        this.init();
    }

    send(event, msg) {
        if (this.socket != null) {
            try {
                const m = {
                    header: event,
                    message: JSON.stringify(msg)
                };
                this.socket.send(JSON.stringify(m));
                return true;
            } catch(e) {
                //console.log("ClientSocket: "+e.message);
                this.pendingMessages.push(msg);
                return false;
            }
        }
    }

    on(event, callback) {
        this.msgCallbacks[event] = callback;
    }

    onError(callback) {
        this.socket.onerror = callback;
    }

    onOpen(callback) {
        this.socket.onopen = callback;
    }

    onMessage(msg) {
        const message = JSON.parse(msg.data);
        this.msgCallbacks[message.header](JSON.parse(message.message));
    }

    onClose(callback) {
        this.socket.onclose = callback;
    }
}


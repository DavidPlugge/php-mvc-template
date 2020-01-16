//add scripts to load them on document ready
function onDocumentReady(callback) {
    window.addEventListener("load", callback);
}
//CSS-handler-instance
let cssHandlerInstance = null;

class CSSHandler {
    constructor() {
        this.stylesheet = document.styleSheets[0];
        this.rules = [];
    }

    static getInstance() {
        if (!cssHandlerInstance) {

            cssHandlerInstance = new this();
        }
        return cssHandlerInstance;
    }

    addRule(selector, rules) {
        let rulesString = null;
        if (this.ruleExists(selector)) {
            rulesString = CSSHandler.createRuleString(rules, this.getRules(selector));
            this.removeRule(selector);
        } else {
            rulesString = CSSHandler.createRuleString(rules);
        }
        this.stylesheet.addRule(selector, rulesString, 0);
    }

    removeRule(name) {
        for (let i = 0; i < this.stylesheet.rules.length; i++) {
            if (this.stylesheet.rules[i].selectorText = name)
            {
                this.stylesheet.removeRule(i);
                return true;
            }
        }
        return false;
    }

    static createRuleString(rules, existing = null) {
        let ruleString = "";

        for (let key in existing) {
            if (rules[key] === undefined) {
                rules[key] = existing[key];
            }
        }

        for (let key in rules) {
            ruleString += key + ": " + rules[key] + "; ";
        }
        return ruleString;
    }

    ruleExists(rule) {
        for (let i = 0; i < this.stylesheet.rules.length; i++) {
            if (this.stylesheet.rules[i].selectorText = rule)
                return true;
        }
        return false;
    }

    getRules(selector) {
        const out = Array();
        for (let i = 0; i < this.stylesheet.rules.length; i++) {
            let selectorExist = this.stylesheet.rules[i].selectorText;
            if ((selectorExist = selector) || selector === undefined) {
                let text = this.stylesheet.rules[i].cssText.replace(selector, "").replace(/[{} ]/g, "");
                text = text.substr(0, text.length-1).split(";");
                let styles = Array();
                for (let i2 = 0; i2 < text.length; i2++) {
                    let style = text[i2].split(":");
                    styles.push('"' + style[0] + '":"' + style[1] + '"');
                }
                styles = "{" + styles.join(",") + "}";
                styles = JSON.parse(styles);
                if (selector === undefined) {
                    out.push(this.getRules(this.stylesheet.rules[i].selectorText));
                } else return styles;
            }
            if (selector === undefined) {
                console.log(out);
                return true;
            }
        }
        return false;
    }
}

class Item {
    constructor(element)
    {
        this.element = element;
    }

    getPath() {
        let path = "";
        let curElement = this.element;
        while (curElement.localName !== "body") {
            let classes = "";
            Array.from(curElement.classList).forEach(function (entry) {
                classes += "." + entry;
            });
            path = curElement.localName + classes + " " + path;
            curElement = curElement.parentElement;
        }
        return path;
    }

    css(rules) {
        const csshandler = CSSHandler.getInstance();
        csshandler.addRule(this.getPath(), rules);
    }

    removeCssRules() {
        const csshandler = CSSHandler.getInstance();
        csshandler.removeRule(this.getPath());
    }

    serialize () {
        let form = this.element;
        if (!form || form.nodeName !== "FORM") {
            return;
        }
        let i, j, q = [];
        for (i = form.elements.length - 1; i >= 0; i--) {
            if (form.elements[i].name === "") {
                continue;
            }
            switch (form.elements[i].nodeName) {
                case 'INPUT':
                    switch (form.elements[i].type) {
                        case 'text':
                        case 'tel':
                        case 'email':
                        case 'hidden':
                        case 'password':
                        case 'button':
                        case 'reset':
                        case 'submit':
                            q[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
                            break;
                        case 'checkbox':
                        case 'radio':
                            if (form.elements[i].checked) {
                                q[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
                            }
                            break;
                    }
                    break;
                case 'file':
                    break;
                case 'TEXTAREA':
                    q[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
                    break;
                case 'SELECT':
                    switch (form.elements[i].type) {
                        case 'select-one':
                            q[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
                            break;
                        case 'select-multiple':
                            for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                                if (form.elements[i].options[j].selected) {
                                    q[form.elements[i].name] = encodeURIComponent(form.elements[i].options[j].value);
                                }
                            }
                            break;
                    }
                    break;
                case 'BUTTON':
                    switch (form.elements[i].type) {
                        case 'reset':
                        case 'submit':
                        case 'button':
                            q[form.elements[i].name] = encodeURIComponent(form.elements[i].value);
                            break;
                    }
                    break;
            }
        }
        return q;
    }

    rules() {
        return CSSHandler.getInstance().getRules(this.getPath());
    }

    html(input) {
        if (input === undefined) {
            return this.element.innerHTML;
        } else {
            this.element.innerHTML = input;
        }
    }

    addHtml(input) {
        this.element.innerHTML += input;
    }

    text(input) {
        if (input === undefined) {
            return this.element.innerText;
        } else {
            this.element.innerText = input;
        }
    }

    addText(input) {
        this.element.innerText += input;
    }

    hasClass(className) {
        return this.element.classList.contains(className);
    }

    addClass(className) {
        this.element.classList.add(className);
    }

    addClasses(classNames = "") {
        if (classNames !== "")
        {
            let classes = classNames.split(" ");
            for (let i = 0; i < classes.length; i++) {
                this.addClass(classes[i]);
            }
        }
    }

    id (id = "") {
        if (id === "") {
            return this.element.id;
        } else this.element.id = id;
    }

    removeClass(className) {
        this.element.classList.remove(className);
    }

    toggleClass(className) {
        this.element.classList.toggle(className);
    }

    hasAttr(attribute) {
        return this.element.hasAttribute(attribute);
    }

    getAttr(attribute) {
        return this.element.getAttribute(attribute);
    }

    removeAttr(attribute) {
        this.element.removeAttribute(attribute);
    }

    setAttr(attributeName, attributeValue) {
        this.element.setAttribute(attributeName, attributeValue);
    }

    find(name) {
        return new ItemList(name, this.element);
    }

    findChildren(name) {
        return new ItemList(this.getPath() + " > " + name);
    }

    findFirstChildren(name) {
        return this.findChildren(name).first();
    }

    children() {
        const out = new ItemList();
        Array.from(this.element.children).forEach(function (entry) {
            out.elements.push(new Item(entry));
        });
        return out;
    }

    parent() {
        return new Item(this.element.parentElement);
    }

    findSibling(name) {
        return this.parent().findChildren(name);
    }

    findFirstSibling(name) {
        return this.parent().findChildren(name).elements[0];
    }

    firstChild() {
        return new Item(this.element.firstElementChild);
    }

    nextSibling() {
        return new Item(this.element.nextElementSibling);
    }

    listen(event, callback, preventDefault = false) {
        const s = this;
        this.element.addEventListener(event, function(e) {
            if (preventDefault) e.preventDefault();
            callback(s);
        });
    }

    val() {
        return this.element.value;
    }

    appendChild(element) {
        this.element.insertBefore(element.element, this.element.lastChild);
    }

    insertChild(element) {
        this.element.insertBefore(element.element, this.element.firstChild);
    }

    insertElementAfter(element) {
        if (this.element.nextSibling) {
            this.parent().element.insertBefore(element.element, this.element.nextSibling);
        }
        this.parent().appendChild(element);
    }
    insertElementBefore(element) {
        this.parent().element.insertBefore(element.element, this.element);
    }
}

class ItemList {

    constructor($query, $source = document) {
        this.elements = Array();
        if ($query !== undefined) {
            const elements = Array();
            Array.from($source.querySelectorAll($query)).forEach(function (entry) {
                elements.push(new Item(entry));
            });
            this.elements = elements;
        }
    }

    each(callback) {
        this.elements.forEach(function (entry) {
            callback(entry);
        });
    }

    css(rules) {
        const csshandler = CSSHandler.getInstance();
        csshandler.addRule(this.first().getPath(), rules);
    }

    removeCssRules() {
        const csshandler = CSSHandler.getInstance();
        csshandler.removeRule(this.first().getPath());
    }

    rules() {
        return CSSHandler.getInstance().getRules(this.first().getPath());
    }

    first() {
        return this.elements[0];
    }

    last() {
        return this.elements[this.elements.length-1];
    }

    listen(event, callback, preventDefault = false) {
        this.each(function (item) {
            item.listen(event, callback, preventDefault);
        });
    }

    html(input) {
        if (input !== undefined) {
            this.each(function (item) {
                item.html(input);
            });
        }
    }

    addHtml(input) {
        this.each(function (item) {
            item.addHtml(input);
        });
    }

    text(input) {
        if (input !== undefined) {
            this.each(function (item) {
                item.text(input);
            });
        }

    }

    addText(input) {
        this.each(function (item) {
            item.addText();
        });
    }

    addClass(className) {
        this.each(function (item) {
            item.addClass(className);
        });
    }

    removeClass(className) {
        this.each(function (item) {
            item.removeClass(className);
        });
    }

    toggleClass(className) {
        this.each(function (item) {
            item.toggleClass(className);
        });
    }

    setAttr(attributeName, attributeValue) {
        this.each(function (item) {
            item.setAttr(attributeName, attributeValue);
        });
    }

    removeAttr(attribute) {
        this.each(function (entry) {
            entry.removeAttr(attribute);
        })
    }
}

class Utils {

    static post(url, data, json = false) {
        return new Promise(function (resolve, reject) {
            const httpRequest = new XMLHttpRequest();
            httpRequest.open("POST", url, true);
            httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            httpRequest.onload = function () {
                if (httpRequest.status === 200) {
                    let response = this.responseText;
                    if (json) {
                        response = json.parse(response);
                    }
                    resolve(response);
                } else {
                    reject(httpRequest.statusText);
                }
            };
            httpRequest.onerror = function () {
                reject(httpRequest.statusText);
            };
            httpRequest.send(Utils.createDataString(data));
        });
    }

    static get(url, data, json = false) {
        return new Promise(function (resolve, reject) {
            const httpRequest = new XMLHttpRequest();
            httpRequest.open("GET", url + "?" + Utils.createDataString(data), true);
            httpRequest.onload = function () {
                if (httpRequest.status === 200) {
                    let response = this.responseText;
                    if (json) {
                        response = json.parse(response);
                    }
                    resolve(response);
                } else {
                    reject(httpRequest.statusText);
                }
            };
            httpRequest.onerror = function () {
                reject(httpRequest.statusText);
            };
            httpRequest.send();
        });
    }

    static createDataString(data) {
        let dataString = "";
        for (let key in data) {
            dataString += key + "=" + data[key] + "&";
        }
        return dataString.substr(0, dataString.length - 1);
    }

    static generator(generator) {
        const gen = generator();

        function handle(yielded) {
            if (!yielded.done) {
                yielded.value.then(function (data) {
                    return handle(gen.next(data));
                })
            }
        }
        return handle(gen.next());
    }
}

class Creator {
    static a(classes = "", href = "", text = "", id = "") {
        let item = new Item(document.createElement("a"));
        item.element.className = classes;
        item.element.href = href;
        item.element.id = id;
        item.text(text);
        return item;
    }

    static i(classes = "", id = "") {
        let item = new Item(document.createElement("i"));
        item.addClasses(classes);
        item.id(id);
        return item;
    }

    static h1(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("h1"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static h2(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("h2"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static h3(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("h3"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static h4(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("h4"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static h5(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("h5"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static h6(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("h6"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static p(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("p"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static ul(classes = "", id = "") {
        let item = new Item(document.createElement("ul"));
        item.addClasses(classes);
        item.id(id);
        return item;
    }

    static li(classes = "", id = "") {
        let item = new Item(document.createElement("li"));
        item.addClasses(classes);
        item.id(id);
        return item;
    }

    static button(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("button"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static textInput(classes = "", text = "", name = "", id = "") {
        let item = new Item(document.createElement("input"));
        item.element.type = "text";
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        item.element.name = name;
        return item;
    }

    static passwordInput(classes = "", text = "", name = "", id = "") {
        let item = new Item(document.createElement("input"));
        item.element.type = "password";
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        item.element.name = name;
        return item;
    }

    static checkboxInput(classes = "", text = "", name = "", id = "") {
        let item = new Item(document.createElement("input"));
        item.element.type = "checkbox";
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        item.element.name = name;
        return item;
    }

    static buttonInput(classes = "", text = "", name = "submit", id = "") {
        let item = new Item(document.createElement("input"));
        item.element.type = "submit";
        item.addClasses(classes);
        item.id(id);
        item.element.value = text;
        item.element.name = name;
        return item;
    }

    static radioInput(classes = "", text = "", name = "", id = "") {
        let item = new Item(document.createElement("input"));
        item.element.type = "radio";
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        item.element.name = name;
        return item;
    }

    static label(classes = "", text = "", id = "") {
        let item = new Item(document.createElement("label"));
        item.addClasses(classes);
        item.id(id);
        item.text(text);
        return item;
    }

    static div(classes = "", id = "") {
        let item = new Item(document.createElement("div"));
        item.addClasses(classes);
        item.id(id);
        return item;
    }
}

function $($query, $source) {
    const items = new ItemList($query, $source);
    if ($query.substr(0,1) === "#") {
        return items.first();
    }
    return items;
}
function dump(input) {
    console.log(input);
}
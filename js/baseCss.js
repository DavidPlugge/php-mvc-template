onDocumentReady(function () {

    $(".dropdown-toggle").listen("click", function (element) {
        const active = element.parent().hasClass("active");
        $(".dropdown").removeClass("active");
        if (!active) {
            element.parent().addClass("active");
        }
    }, true);

    $(".nav-toggle").listen("click", function (element) {
        const active = element.parent().hasClass("active");
        $(".nav").removeClass("active");
        if (!active) {
            element.parent().addClass("active");
        }
    }, true);

    $("body").first().listen("click", function () {
        const target = new Item(event.target);
        if (!target.hasClass("dropdown-menu") && !target.hasClass("dropdown-toggle")) $(".dropdown").removeClass("active");
    });


    $(".form-group-text input").each(function (el) {
        if (el.val() !== "") {
            el.parent().addClass("valid");
        }
    });

    $(".form-group-password input").each(function (el) {
        if (el.val() !== "") {
            el.parent().addClass("valid");
        }
    });

    $(".form-group-text input").listen("change", function(element) {
        if (element.val() === "") {
            element.parent().removeClass("valid");
        } else {
            element.parent().addClass("valid");
        }
    });

    $(".form-group-password input").listen("change", function(element) {
        let label = element.findFirstSibling("label");
        if (element.val() === "") {
            element.parent().removeClass("valid");
        } else {
            element.parent().addClass("valid");
        }
    });

    $(".form-group-checkbox label").each(function(element) {
        element.appendChild(Creator.i("fa fa-check-square checked"));
        element.appendChild(Creator.i("far fa-square unchecked"));
        element.appendChild(Creator.i("fa fa-square disabled"));
    });

    $(".form-group-radio label").each(function(element) {
        element.appendChild(Creator.i("far fa-dot-circle checked"));
        element.appendChild(Creator.i("far fa-circle unchecked"));
        element.appendChild(Creator.i("fa fa-circle disabled"));
    });

    $(".form-group-file label").each(function (element) {
        element.insertChild(Creator.i("fas fa-upload upload-file-icon"));
    });

    $(".form-group-file input").listen("change", function (element) {
        if (element.element.files && element.element.files.length > 0)
        {
            const label = element.findFirstSibling("label");
            console.log(label);
            label.addClass("valid");
            const fileName = element.element.files[0].name;
            label.text(fileName);
        }
    });

    $(".toggle").each(function (element) {
        const modal = $(element.getAttr("data-toggle"));
        const toggleClass = element.getAttr("data-toggle-class");

        element.listen("click", function () {
            modal.toggleClass(toggleClass);
        });
        modal.listen("click", function () {
            if (event.target === modal.element)
                modal.toggleClass(toggleClass);
        });
    });

    signOut = $(".nav-main-user .dropdown-menu li").last();
    if (signOut !== undefined)
        signOut.children("a").first().appendChild(Creator.i("fas fa-sign-out-alt user-sign-out"));
    delete signOut;
});
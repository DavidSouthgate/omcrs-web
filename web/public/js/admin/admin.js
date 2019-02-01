function modifyUser(url, callback) {

    // Make an api request

}

function userChangeAdmin(that, admin) {

    var userId = $(that).closest("li").attr("data-user-id");

    // Construct URL for API request
    var url = baseUrl + "api/user/" + userId + "/edit/?isAdmin=" + (admin?"true":"false");

    $.getJSON(url, function(data) {

        // Check user was changed successfully
        if(data["isAdmin"] === admin) {

            if(admin) {
                $(that).text("Remove Admin");
                $(that).addClass("admin-remove");
                $(that).removeClass("admin-add");

            }
            else {
                $(that).text("Make Admin");
                $(that).addClass("admin-add");
                $(that).removeClass("admin-remove");
            }

            $(that).unbind("click");
            $(that).click(function() {
                userChangeAdmin(this, !admin);
            });
        }

        // Otherwise, display error
        else {
            alerter({
                title: "Error",
                message: "Could not make user an admin for an unknown reason",
                type: "danger",
                dismissable: true
            });
        }
    });
}

function userChangeSessionCreator(that, sessionCreator) {

    var userId = $(that).closest("li").attr("data-user-id");

    // Construct URL for API request
    var url = baseUrl + "api/user/" + userId + "/edit/?isSessionCreator=" + (sessionCreator?"true":"false");

    $.getJSON(url, function(data) {

        // Check user was changed successfully
        if(data["isSessionCreator"] === sessionCreator) {
            if(sessionCreator) {
                $(that).text("Remove Session Creator");
                $(that).addClass("session-creator-remove");
                $(that).removeClass("session-creator-add");

            }
            else {
                $(that).text("Make Session Creator");
                $(that).addClass("session-creator-add");
                $(that).removeClass("session-creator-remove");
            }

            $(that).unbind("click");
            $(that).click(function() {
                userChangeSessionCreator(this, !sessionCreator);
            });
        }

        // Otherwise, display error
        else {
            alerter({
                title: "Error",
                message: "Could not make user a session creator for an unknown reason",
                type: "danger",
                dismissable: true
            });
        }
    });
}

$("button.show-guests").click(function() {
    $(".guest").css("display", "inline");
    $(".show-guests").css("display", "none");
    $(".hide-guests").css("display", "inline");
});

$("button.hide-guests").click(function() {
    $(".guest").css("display", "none");
    $(".show-guests").css("display", "inline");
    $(".hide-guests").css("display", "none");
});

$("button.admin-add").click(function() {
    userChangeAdmin(this, true);
});

$("button.admin-remove").click(function() {
    userChangeAdmin(this, false);
});

$("button.session-creator-add").click(function() {
    userChangeSessionCreator(this, true);
});

$("button.session-creator-remove").click(function() {
    userChangeSessionCreator(this, false);
});

$("button.delete-user").click(function() {

    var userId = $(this).closest("li").attr("data-user-id");

    // Construct URL for API request
    var url = baseUrl + "api/user/" + userId + "/delete/";

    var that = this;

    $.getJSON(url, function(data) {

        // Check user was deleted successfully
        if(data["success"] === true) {
            $(that).closest("li").remove();
        }

        // Otherwise, display error
        else {
            alerter({
                title: "Error",
                message: "Could not delete user for an unknown reason",
                type: "danger",
                dismissable: true
            });
        }
    });
});

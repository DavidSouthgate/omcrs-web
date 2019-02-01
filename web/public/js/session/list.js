$(".confirm-delete .confirm").click(function () {

    // Disable button clicked to indicate that something is happening
    $(this).attr("disabled", "disabled");

    var listGroupItem = $(this).closest(".list-group-item");

    // Construct URL for API request
    var url = baseUrl + "api/session/" + $(this).attr("data-session-identifier") + "/delete/";

    // Store this for access when this is no longer "this"
    var that = this;

    // Make an api request
    $.getJSON(url, function(data) {

        // If delete was successful, delete html element
        if(data["success"] === true) {
            listGroupItem.remove();
        }

        else {
            alerter({
                title: "Error",
                message: "Could not delete session for an unknown reason",
                type: "danger",
                dismissable: true
            });
        }

    })
    .always(function() {

        // Enable button once request is complete
        $(that).removeAttr("disabled");

        // Reset confirm delete buttons
        $(that).closest(".confirm-delete").css("display", "none");
        var actionsConfirmDelete = $(that).closest(".actions-confirm-delete");
        actionsConfirmDelete.find(".actions").css("display", "inline-flex");
    });
});

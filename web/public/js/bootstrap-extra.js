function onclickHref(that) {
    document.location = $(that).attr("data-href");
}

/********************************************************************
 * Input Add More
 ********************************************************************/

$(".input-add-more-button .input-add-more-input").click(function () {

    // Find the add more button
    var inputAddMoreButton = $(this).closest(".input-add-more-button");

    // Load the container using the data attribute
    var dataInputContainer = $("#" + inputAddMoreButton.attr("data-input-container-id"));

    var dataInputContainerFirstChild = dataInputContainer.find("> :first-child");

    if(dataInputContainerFirstChild.hasClass("display-none")) {
        dataInputContainerFirstChild.removeClass("display-none");
    }

    else {

        // Get previous last child
        var input = dataInputContainer.find(":last-child .input-add-more-input");

        // Get position of last "-" + 1
        var pos = input.attr("name").lastIndexOf("-") + 1;

        var nextNamePrefix = input.attr("name").substr(0, pos);
        var nextNameNum = parseInt(input.attr("name").substr(pos)) + 1;
        var nextName = nextNamePrefix + nextNameNum;

        // Add new item using the first
        dataInputContainer.append(dataInputContainerFirstChild[0].outerHTML);

        var lastChild = dataInputContainer.find(":last-child");

        // Get new last child
        input =  lastChild.find(".input-add-more-input");

        // Clear input values in this new item
        input.attr("value", "");
        input.attr("name", nextName);
        input.attr("id", nextName);

        initAddMoreDelete(dataInputContainer.find(":last-child .input-add-more-input.delete"));
    }
});

function initAddMoreDelete(deleteButton) {
    deleteButton.bind( "click", function() {
        addMoreDelete(this);
    });

    deleteButton.mouseover(function() {
        $(this).removeClass("btn-light");
        $(this).addClass("btn-danger btn-danger-border");
    });

    deleteButton.mouseout(function() {
        $(this).removeClass("btn-danger btn-danger-border");
        $(this).addClass("btn-light");
    });
}

function addMoreDelete(that) {

    var inputAddMoreContainer = $(that).closest(".input-add-more-container");

    // If this isn't the minimum number of inputs
    if(inputAddMoreContainer.children().length > parseInt(inputAddMoreContainer.attr("data-minimum-count"))) {

        // If there is only one child left, only hide it
        if(inputAddMoreContainer.children().length === 1) {
            var closestAddMoreItem = $(that).closest(".input-add-more-item");
            closestAddMoreItem.find("input").val("");
            closestAddMoreItem.addClass("display-none");
        }

        // Otherwise, remove the item
        else {
            $(that).closest(".input-add-more-item").remove();
        }
    }
}

initAddMoreDelete($(".input-add-more-container .input-add-more-input.delete"));

/********************************************************************
 * Confirm Delete
 ********************************************************************/

var body = $("body");

body.on("click", ".actions-confirm-delete .actions .delete", function(event) {
    var actions = $(this).closest(".actions");
    var actionsConfirmDelete = $(this).closest(".actions-confirm-delete");
    var confirmDelete = actionsConfirmDelete.find(".confirm-delete");
    actions.css("display", "none");
    confirmDelete.css("display", "inline-flex");
});

body.on("click", ".confirm-delete .cancel", function(event) {
    var actionsConfirmDelete = $(this).closest(".actions-confirm-delete");
    var actions = actionsConfirmDelete.find(".actions");
    var confirmDelete = actionsConfirmDelete.find(".confirm-delete");
    confirmDelete.css("display", "none");
    actions.css("display", "inline-flex");
});

/********************************************************************
 * Click Href, Click Post
 ********************************************************************/

//var redirect = 'http://www.website.com/page?id=23231';
//$.redirectPost(redirect, {x: 'example', y: 'abc'});

/**
 * Extend jQuery to have function redirectPost which redirects the user with post data
 */
/*
*/
$.extend({
    redirectPost: function(location, args) {
        var form = '';
        $.each( args, function( key, value ) {
            form += '<input type="hidden" name="'+key+'" value="'+value+'">';
        });
        $('<form action="'+location+'" method="POST">'+form+'</form>').appendTo('body').submit();
    }

});

/********************************************************************
 * Navigation Tabs
 ********************************************************************/

$("ul.nav-tabs li.nav-item").click(function() {
    var targetID = $(this).attr("data-target");
    var navTabs = $(this).closest("ul.nav-tabs");
    var sectionsContainerID = navTabs.attr("data-target");

    // Hide all sections
    $("#" + sectionsContainerID).find(".section").addClass("display-none");

    // Display the target section
    $("#" + targetID).removeClass("display-none");

    // Change the tab
    navTabs.find("li.nav-item a").removeClass("active");
    $(this).find("a").addClass("active");

    // Run callback if it exists
    var callback = window[$(this).attr("data-callback")];
    if(typeof callback === 'function') {
        callback();
    }
});

var questionBeingModified = false;
var dontCheckNewActiveQuestion = false;

/**
 * Runs when the "Update Answer" button is clicked
 */
$("#answer-update").click(function () {
    $(".answer[type=radio]").removeAttr("disabled");
    $(".answer[type=text]").removeAttr("disabled");
    $(".answer").removeAttr("disabled");
    $(".answer-submit").removeClass("display-none");
    $(this).remove();
});

/**
 * Set if the question has been modified when an answer field is focused upon. This allows for warning to be displayed
 * instead of a page refresh when the active question changes
 */
$("input.answer").focus(function() {
    questionBeingModified = true;
});

function reloadOrError() {

    // If the question hasn't been modified since displayed
    if(!questionBeingModified) {

        // Refresh the page
        location.reload();
    }

    // Otherwise, display an alert
    else {

        // Don't loop again
        dontCheckNewActiveQuestion = true;

        alerter({
            title: "Warning",
            message: "This question is no longer active",
            type: "warning",
            dismissable: true
        });
    }
}

// If in teacher led mode
if(parseInt($("meta[name=questionControlMode]").attr("content")) === 0) {

    (function checkNewActiveQuestion() {
        setTimeout(function () {

            // Get the session ID and current session question ID from a HTML meta tag
            var sessionIdentifier = $("meta[name=sessionIdentifier]").attr("content").toString();
            var sessionQuestionID = $("meta[name=sessionQuestionID]").attr("content").toString();

            // Construct URL for API request
            var url = baseUrl + "api/session/" + sessionIdentifier + "/question/active/";

            // Communicate with the API and process the response
            $.getJSON(url, function(data) {

                // If there was at least one active question
                if(data.length >= 1) {

                    // Use first active question (As only one question will be active in teacher led mode)
                    var activeSessionQuestionID = data[0].toString();

                    // If this question is different to the one currently displayed
                    if(activeSessionQuestionID !== sessionQuestionID) {
                        reloadOrError();
                    }
                }

                // Otherwise, no question active
                else {

                    // If a question is currently being displayed, refresh the page
                    if(sessionQuestionID !== "") {
                        reloadOrError();
                    }
                }

                // If we can loop again, loop again
                if(!dontCheckNewActiveQuestion) {
                    checkNewActiveQuestion();
                }
            });
        }, 2000);
    }());
}

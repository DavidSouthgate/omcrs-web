function addGenericQuestion(url, type, callback) {

    // Make an api request
    $.getJSON(url, function(data) {

        // If delete was successful, delete html element
        if(data["type"] === type) {

            if(callback) {
                callback(data);
            }
        }

        else {
            alerter({
                title: "Error",
                message: "Could not add question for an unknown reason",
                type: "danger",
                dismissable: true
            });
        }
    });
}

/**
 * Add a generic question using a code
 * @param code E.g. mcq_d, mcq_f, mrq_d, mrq_f, truefalse, textlong
 * @param sessionIdentifier;
 * @param callback
 * @param active Whether the new question should be active
 */
function addGenericQuestionFromCode(code, sessionIdentifier, callback, active) {
    active = active===true;

    switch(code) {
        case "mcq_d":
            addGenericChoicesQuestion(4, "mcq", sessionIdentifier, callback, active);
            break;
        case "mcq_e":
            addGenericChoicesQuestion(5, "mcq", sessionIdentifier, callback, active);
            break;
        case "mcq_f":
            addGenericChoicesQuestion(6, "mcq", sessionIdentifier, callback, active);
            break;
        case "mcq_g":
            addGenericChoicesQuestion(7, "mcq", sessionIdentifier, callback, active);
            break;
        case "mcq_h":
            addGenericChoicesQuestion(8, "mcq", sessionIdentifier, callback, active);
            break;
        case "mrq_d":
            addGenericChoicesQuestion(4, "mrq", sessionIdentifier, callback, active);
            break;
        case "mrq_e":
            addGenericChoicesQuestion(5, "mrq", sessionIdentifier, callback, active);
            break;
        case "mrq_f":
            addGenericChoicesQuestion(6, "mrq", sessionIdentifier, callback, active);
            break;
        case "mrq_g":
            addGenericChoicesQuestion(7, "mrq", sessionIdentifier, callback, active);
            break;
        case "mrq_h":
            addGenericChoicesQuestion(8, "mrq", sessionIdentifier, callback, active);
            break;
        case "text":
            addGenericTextQuestion(false, sessionIdentifier, callback, active);
            break;
        case "textlong":
            addGenericTextQuestion(true, sessionIdentifier, callback, active);
            break;
        case "truefalse":
            addGenericTrueFalseQuestion(false, sessionIdentifier, callback, active);
            break;
        case "truefalsedk":
            addGenericTrueFalseQuestion(true, sessionIdentifier, callback, active);
            break;
    }
}

/**
 * Add a new generic choices (MCQ/MRQ) question
 * @param numChoices The number of choices
 * @param type mcq or mrq
 * @param sessionIdentifier;
 * @param callback
 * @param active Whether the new question should be active
 */
function addGenericChoicesQuestion(numChoices, type, sessionIdentifier, callback, active) {
    if(numChoices > 26 | numChoices <= 0)
        return;

    // Construct URL for API request
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/new/" + type + "/";

    // Add URL parameters
    url += "?question=Generic " + type.toUpperCase() + " Question A-" + String.fromCharCode(65 + numChoices - 1) + "&";

    // Add generic choice (I.e. A, B, C, D)
    for (var i = 0; i < numChoices; i++) {

        // Add choice to URL as parameter
        url += "choice-" + i + "=" + String.fromCharCode(65 + i);

        // If not the last choice, add "&" ready for next choice
        if(i < numChoices-1) {
            url += "&";
        }
    }

    if(active)
        url += "&active=true";

    addGenericQuestion(url, type, callback);
}

/**
 * Add a new generic true/false question
 * @param dontKnow Whether don't know is also an option
 * @param sessionIdentifier;
 * @param callback
 * @param active Whether the new question should be active
 */
function addGenericTrueFalseQuestion(dontKnow, sessionIdentifier, callback, active) {
    dontKnow = dontKnow===true;

    // Construct URL for API request
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/new/mcq/";

    // Add URL parameters
    url += "?question=Generic True/False";
    if(dontKnow)
        url += "/Don't Know";
    url += " Question";

    // Add true/false
    url += "&choice-0=True";
    url += "&choice-1=False";

    if(dontKnow)
        url += "&choice-2=Don't Know";

    if(active)
        url += "&active=true";

    addGenericQuestion(url, "mcq", callback);
}


/**
 * Add a new generic text question
 * @param long If a long text question
 * @param sessionIdentifier;
 * @param callback
 * @param active Whether the new question should be active
 */
function addGenericTextQuestion(long, sessionIdentifier, callback, active) {
    long = long===true;

    // Get the question type from whether it is a long text question
    var type = long ? "textlong" : "text";
    var typeView = long ? "Long Text" : "Text";

    // Construct URL for API request
    var url = baseUrl + "api/session/" + sessionIdentifier + "/question/new/" + type + "/";

    // Add URL parameters
    url += "?question=Generic " + typeView + " Question";

    if(active)
        url += "&active=true";

    addGenericQuestion(url, type, callback);
}

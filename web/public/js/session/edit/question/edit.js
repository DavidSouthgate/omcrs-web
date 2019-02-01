var choiceDefaultChanged = false;
var choiceDefaultCurrent = 4;

function mcqChoiceCorrectClick(that) {
    var inputGroup = $(that).closest(".input-group");
    inputGroup.addClass("correct");
    inputGroup.find("input.mcq-choice-correct").val("true");
}

function mcqChoiceIncorrectClick(that) {
    var inputGroup = $(that).closest(".input-group");
    inputGroup.removeClass("correct");
    inputGroup.find("input.mcq-choice-correct").val("false");
}

$("#screenshotlink").click(function() {
    var element = document.getElementById('screenshotlink');
    if (element.innerHTML === 'Show Screenshot') element.innerHTML = 'Hide Screenshot';
    else {
        element.innerHTML = 'Show Screenshot';
    }
});

$("button.correct").click(function() {
    mcqChoiceCorrectClick(this);
});

$("button.incorrect").click(function() {
    mcqChoiceIncorrectClick(this);
});

$("#questionType").change(function() {
    var question = $(".question");

    // Switch on question type
    switch($(this).val()) {
        case "mcq":
        case "mrq":
            question.css("display", "none");
            $("#question-mcq").css("display", "flex");
            break;
        case "text":
        case "textlong":
            question.css("display", "none");
            $("#question-text").css("display", "flex");
            break
    }
});

function mcqChoiceChange(that) {

    // If this is a new question,
    if(questionNew) {
        choiceDefaultChanged = true;
        $(that).attr("modified", "true");
    }
}

function mcqChoiceClick(that) {

    // If this is a new question,
    if(questionNew) {
        that = $(that);
        if (that.attr("modified") !== "true") {
            that.select();
        }
    }
}

function mcqDeleteChoiceClick() {

    // If the default input values have not been changed yet, this is a new question and the current default is not A
    if(!choiceDefaultChanged && questionNew && choiceDefaultCurrent > 1) {
        choiceDefaultCurrent--;
    }
}


$(".input-add-more-button .input-add-more-input").click(function () {

    // If the default input values have not been changed yet and this is a new question
    if(!choiceDefaultChanged && questionNew) {

        var input = $("#add-more-choices > :last-child > input.mcq-choice");

        // Get the choice index
        var index = input.attr("name").substr(11);

        var inputMcqChoiceId = $("#add-more-choices > :last-child > input.mcq-choice-id");
        var inputMcqChoiceCorrect = $("#add-more-choices > :last-child > input.mcq-choice-correct");

        // Update names and IDs of new inputs
        inputMcqChoiceId.attr("name", "mcq-choice-id-"+index);
        inputMcqChoiceId.attr("id", "mcq-choice-id-"+index);
        inputMcqChoiceCorrect.attr("name", "mcq-choice-correct-"+index);
        inputMcqChoiceCorrect.attr("id", "mcq-choice-correct-"+index);

        // Set new input to next letter in the alphabet
        input.val(String.fromCharCode(65 + choiceDefaultCurrent));

        // Increment the current default choice
        choiceDefaultCurrent++;

        // If this is after Z in the alphabet, reset to A
        if (choiceDefaultCurrent >= 26)
            choiceDefaultCurrent = 0;

        $("#add-more-choices > :last-child > button.delete").click(function() {
            mcqDeleteChoiceClick();
        });

        $("#add-more-choices > :last-child > input").change(function() {
            mcqChoiceChange(this);
        }).click(function() {
            mcqChoiceClick(this);
        })
    }

    $("#add-more-choices > :last-child > button.correct").click(function() {
        mcqChoiceCorrectClick(this);
    });

    $("#add-more-choices > :last-child > button.incorrect").click(function() {
        mcqChoiceIncorrectClick(this);
    });
});

$(".input-add-more-item input.input-add-more-input").change(function() {
    mcqChoiceChange(this);
});

$(".input-add-more-item input.input-add-more-input").click(function() {
    mcqChoiceClick(this);
});

$(".input-add-more-container .input-add-more-item button.delete").click(function () {
    mcqDeleteChoiceClick();
});

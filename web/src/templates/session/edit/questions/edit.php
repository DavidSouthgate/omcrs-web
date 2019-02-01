<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $session Session
 * @var $question Question|QuestionMcq|QuestionText|QuestionTextLong
 * @var $screenshot boolean
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert
    ]
);

// Whether this is a new question
$new = !isset($question);

// Generic text to use in page the "new question" and "edit question" pages
$newEditText = $new ? "New" : "Edit";
$saveText = $new ? "Create" : "Save";

// If this is a new question, add default number of MCQ choices
if($new) {

    // Array of possible choices
    $choices = [];

    // Choice A - D
    for($i = 65; $i <= 68; $i++) {

        // Add a bew choice
        $choice = new QuestionMcqChoice(chr($i));
        array_push($choices, $choice);
    }
}

// Otherwise not a new question
else {

    // Array of possible choices
    $choices = [];

    // If MCQ add choices to array
    if(in_array(get_class($question), ["QuestionMcq", "QuestionMrq"]))
        $choices = $question->getChoices();

    // If no choices have been added, add one
    if(count($choices) == 0) {
        $choice = new QuestionMcqChoice("");
        array_push($choices, $choice);
    }
}

?>

<?php $this->push("head"); ?>
    <link rel="stylesheet" type="text/css" href="<?=$this->e($config["baseUrl"])?>css/session/edit/question/edit.css" />
<?php $this->end(); ?>

<?php $this->push("end"); ?>
    <script>
        var questionNew = <?=$new ? "true": "false"?>;
    </script>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/edit/question/edit.js"></script>
<?php $this->end(); ?>

<div class="row editquestionheader">
    <div class="col-sm-10">
        <h2 class="page-section">
            <?=$this->e($newEditText)?> Question
        </h2>
    </div>

    <?php if($screenshot): ?>
    <div class = "col-sm-2">
        <div id="screenshotbutton">
            <h5 class="page-section">
                <a id="screenshotlink" href="#collapseScreenshot" data-toggle="collapse">Show Screenshot</a>
            </h5>
        </div>
    </div>
    <?php endif; ?>
</div>


<?php if($screenshot): ?>
        <div id="collapseScreenshot" class="collapse">
        <img id="screenshot" src="<?=$this->e($config["baseUrl"])?>session/<?=$session->getSessionIdentifier()?>/edit/question/<?=$question->getSessionQuestionID()?>/screenshot/"/>
        </div>

<?php endif; ?>

<form id="" action="." method="POST" class="form-horizontal<?=$new?" new":""?>">

    <?php // If this is a new question, display question type selection ?>
    <?php if($new): ?>
        <div class="form-group row">
            <label class="col-sm-2 control-label" for="questionType">Type</label>
            <div class="col-sm-10">
                <select id="questionType" name="questionType" class="form-control" tabindex="1">
                    <option selected="1" value="mcq">Multiple Choice Question</option>
                    <option value="mrq">Multiple Response Question</option>
                    <option value="text">Text Input</option>
                    <option value="textlong">Long Text Input</option>
                </select>
            </div>
        </div>

    <?php // If this is editing an existing question, add type and session question id as hidden input field ?>
    <?php else: ?>
        <input type="hidden" name="questionType" value="<?=$this->e($question->getType())?>">
        <input type="hidden" name="sqid" value="<?=$this->e($question->getSessionQuestionID())?>">
    <?php endif; ?>

    <div class="form-group row" id="questions-row">
        <label class="col-sm-2 control-label" for="question">Question</label>
        <div class="col-sm-10">
            <input class="form-control" name="question" id="mcQuestion" value="<?=isset($question)?$this->e($question->getQuestion()):""?>" size="80" type="text" tabindex="1" maxlength="80">
        </div>
    </div>

    <?php if($new || in_array(get_class($question), ["QuestionMcq", "QuestionMrq"])): ?>
        <div id="question-mcq" class="form-group row question">
            <label for="definition" class="control-label col-sm-2">
                <span>Choices</span>
            </label>
            <div class="col-sm-10">
                <div id="add-more-choices" class="input-add-more-container" data-minimum-count="1">
                    <?php $i = 0; ?>
                    <?php foreach ($choices as $choice): ?>
                        <div class="input-group input-add-more-item<?=$choice->isCorrect()?" correct":""?>">
                            <input id="mcq-choice-<?=$i?>" name="mcq-choice-<?=$i?>" class="form-control input-add-more-input mcq-choice" type="text" value="<?=$this->e($choice->getChoice())?>" tabindex="1" maxlength="80">
                            <input id="mcq-choice-id-<?=$i?>" name="mcq-choice-id-<?=$i?>" class="mcq-choice-id" type="hidden" value="<?=$this->e($choice->getChoiceID())?>">
                            <input id="mcq-choice-correct-<?=$i?>" name="mcq-choice-correct-<?=$i?>" class="mcq-choice-correct" type="hidden" value="<?=$choice->isCorrect()?"true":"false"?>">
                            <button class="incorrect btn btn-light btn-light-border input-add-more-input" type="button" tabindex="2">
                                    <i class="fa fa-times" aria-hidden="true"></i> Incorrect
                            </button>
                            <button class="correct btn btn-light btn-light-border input-add-more-input" type="button" tabindex="2">
                                <i class="fa fa-check" aria-hidden="true"></i> Correct
                            </button>
                            <button class="delete btn btn-light btn-light-border input-add-more-input" type="button" tabindex="2">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </div>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </div>
                <div id="add-more-button-container" class="col-sm-12 input-add-more-button" data-input-container-id="add-more-choices">
                    <input class="submit btn btn-primary" name="submit" value="<?=$saveText?>" type="submit" tabindex="1">
                    <a onclick="goBack()" class="btn btn-light btn-light-border">Cancel</a>
                    <button class="btn btn-light btn-light-border input-add-more-input float-right" type="button">
                        Add Another Choice
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if($new || in_array(get_class($question), ["QuestionText", "QuestionTextLong"])): ?>
        <div id="question-text" class="form-group row question">
            <div class="col-sm-10 offset-sm-2">
                <input class="submit btn btn-primary" name="submit" value="<?=$saveText?>" type="submit" tabindex="1">
                <a onclick="goBack()" class="btn btn-light btn-light-border">Cancel</a>
            </div>
        </div>
    <?php endif; ?>
    <?php if($new || !in_array(get_class($question), ["QuestionMcq", "QuestionMrq", "QuestionText", "QuestionTextLong"])): ?>
        <div id="question-other" class="form-group row question">
            Question type may not be supported.
        </div>
    <?php endif; ?>
</form>
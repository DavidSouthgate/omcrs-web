<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $session Session
 * @var $questions Question[][]
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
?>

<?php $this->push("head"); ?>
    <link rel="stylesheet" href="<?=$this->e($config["baseUrl"])?>css/session/edit/edit.css">
    <meta name="sessionID" content="<?=$this->e($session->getSessionID())?>" />
    <meta name="sessionIdentifier" content="<?=$this->e($session->getSessionIdentifier())?>" />
    <meta name="defaultTimeLimit" content="<?=$this->e($session->getDefaultTimeLimit())?>" />
    <meta name="questionControlMode" content="<?=$this->e($session->getQuestionControlMode())?>" />
<?php $this->stop(); ?>

<?php $this->push("end"); ?>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/generic-questions.js" crossorigin="anonymous"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/edit/edit.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<div class="page-header">
    <h1 class="row">
        <div class="col-sm-9">
            <h1><?=$this->e($session->getTitle())?></h1>
            <h3>Session Identifier: <span id="sid"><i class="fa fa-hashtag"></i><?=$this->e($session->getSessionIdentifier())?></span></h3>
        </div>
        <div class="col-sm-3">
            <a href="<?=$config["baseUrl"]?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/properties/" class="btn btn-light btn-light-border pull-right width-xs-full" style="margin-left: 10px">Edit Properties</a>
            <?php if(isDesktopApp() && $session->getQuestionControlMode() === 0): ?>
                <a onclick="liveViewEnter(<?=$this->e($session->getSessionIdentifier())?>)" class="btn btn-light btn-light-border pull-right width-xs-full">Run Session</a>
            <?php endif; ?>
        </div>
    </h1>
</div>
<div class="row">
    <div class="col-sm-12">
        <h2 class="pull-left">New Question</h2>
    </div>
</div>
<div id="add-question-row" class="row">
    <div id="add-question-select-row" class="col-sm-10">
        <select id="add-question-select" class="form-control" data-custom-href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/question/new/">
            <option value="custom">Editable Custom Question</option>
            <option value="mcq_d">Multiple Choice Question A-D</option>
            <option value="mcq_e">Multiple Choice Question A-E</option>
            <option value="mcq_f">Multiple Choice Question A-F</option>
            <option value="mrq_d">Multiple Response Question A-D</option>
            <option value="mrq_e">Multiple Response Question A-E</option>
            <option value="mrq_f">Multiple Response Question A-F</option>
            <option value="text">Text Input</option>
            <option value="textlong">Long Text Input</option>
            <option value="truefalse">True/False</option>
            <option value="truefalsedk">True/False/Don't Know</option>
        </select>
    </div>
    <div id="add-question-submit-row" class="col-sm-2">
        <button id="add-question-submit" class="btn btn-primary">Add</button>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-9">
        <h2 class="pull-left">Session Questions</h2>
    </div>
</div>
<div>
    <ul class="list-group question-list" style="width:100%;" data-question-control-mode="<?=$this->e($session->getQuestionControlMode())?>">
        <li class="no-questions">
            No Questions Found
        </li>
        <?php
        $i = 1;
        $qi = count($questions["questions"]);
        $timerEnabled = false;
        foreach($questions["questions"] as $question):
            $class = $question->isActive() ? " active-question" : "";

            ?>
            <li class="list-group-item question-item<?=$this->e($class)?>" data-question-id="<?=$this->e($question->getQuestionID())?>" data-session-question-id="<?=$this->e($question->getSessionQuestionID())?>">
                <div class="question-number pull-left">
                    <?=$qi?>.
                </div>
                <div class="pull-left details">
                    <span class="question-title">
                        <?=$question->getQuestion() ? $this->e($question->getQuestion()) : $question->getTypeDisplay() . " Question"?>
                    </span>
                    <span class="question-timer">
                        <?php
                            if($session->getDefaultTimeLimit() != 0 && $question->isActive() && $session->getQuestionControlMode() === 0) {
                                echo $session->getDefaultTimeLimit();
                                $timerEnabled = true;
                            }
                        ?>
                    </span><br>
                    <span class="question-date text-muted">
                        Created <?=date("d/m/Y H:i", $question->getCreated())?>
                    </span>
                </div>
<!--                <div class="drag pull-right">-->
<!--                    <i class="fa fa-arrows-alt" id="dragable" aria-hidden="true" draggable="true""></i>-->
<!--                </div>-->
                <!--<div class="drag pull-right">
                   <i class="fa fa-arrows-alt" aria-hidden="true" >id="dragable"  draggable="true""</i>
                </div>-->
                <div class="actions-confirm-delete">
                    <div class="btn-group pull-right actions width-xs-full" aria-label="Actions">
                        <button type="button" class="btn btn-light btn-light-border deactivate width-xs-full">
                            <i class='fa fa-stop'></i><span class="d-none d-sm-inline"> Close</span>
                        </button>
                        <button type="button" class="btn btn-light btn-light-border activate width-xs-full">
                            <i class='fa fa-play'></i><span class="d-none d-sm-inline"> Activate</span>
                        </button>
                        <a href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/question/<?=$this->e($question->getSessionQuestionID())?>/response/" class="btn btn-light btn-light-border width-xs-full">
                            <i class="fa fa-eye"></i><span class="d-none d-sm-inline"> Responses</span>
                        </a>
                        <a class="btn btn-light btn-light-border width-xs-full" href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/question/<?=$this->e($question->getSessionQuestionID())?>/">
                            <i class="fa fa-pencil"></i><span class="d-none d-sm-inline"> Edit</span>
                        </a>
                        <button type="button" class="btn btn-light btn-light-border delete width-xs-full">
                            <i class="fa fa-trash-o"></i><span class="d-none d-sm-inline"> Delete</span>
                        </button>
                    </div>
                    <div class="btn-group pull-right confirm-delete width-xs-full" aria-label="Confirm Delete">
                        <button type="button" class="btn btn-danger btn-danger-border confirm width-xs-full">
                            <i class="fa fa-check"></i> Confirm
                        </button>
                        <button type="button" class="btn btn-light btn-light-border cancel width-xs-full">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </li>
            <?php
            $i++;
            $qi--;
        endforeach;
        ?>
    </ul>
    <?php // If student paced question, allow user to activate/deactivate all questions ?>
    <?php if($session->getQuestionControlMode() == 1):?>
    <div class="row">
        <div class="col-sm-12">
            <div id="activate-deactivate-all" class="btn-group pull-right width-xs-full">
                <button id="activate-all" class="btn btn-light btn-light-border width-xs-full">
                    <i class='fa fa-play'></i> Activate All
                </button>
                <button id="deactivate-all" class="btn btn-light btn-light-border width-xs-full">
                    <i class='fa fa-stop'></i> Close All
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<hr>
<div class="row">
    <div class="col-sm-12">
        <h2>Export</h2>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <a href="<?=$config["baseUrl"]?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/export/" class="btn btn-primary width-xs-full">Export as Spreadsheet</a>
    </div>
</div>
<?php $this->push("end"); ?>
    <?php if($timerEnabled): ?>
        <script>
            startCountdown();
        </script>
    <?php endif; ?>
<?php $this->stop(); ?>

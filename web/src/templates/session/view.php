<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $alert Alert
 * @var $session Session
 * @var $response Response
 * @var $responses Response[]
 * @var $question Question
 * @var $totalQuestions int
 * @var $questionNumber int
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => ($session->getTitle() ? $session->getTitle() : "Session") . " (#".$session->getSessionIdentifier().")",
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert,
    ]
);
?>

<?php $this->push("head"); ?>
    <link rel="stylesheet" href="<?=$this->e($config["baseUrl"])?>css/session/view.css">
    <meta name="sessionID" content="<?=$this->e(isset($session) ? $session->getSessionID() : "")?>" />
    <meta name="sessionIdentifier" content="<?=$this->e(isset($session) ? $session->getSessionIdentifier() : "")?>" />
    <meta name="sessionQuestionID" content="<?=$this->e(isset($question) ? $question->getSessionQuestionID() : "")?>" />
    <meta name="questionControlMode" content="<?=$this->e(isset($session) ? $session->getQuestionControlMode() : "")?>" />
<?php $this->end(); ?>

<?php $this->push("end"); ?>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/view.js"></script>
<?php $this->end(); ?>

<?php
// If no question active
if($question === null):
?>
    <div class="alert alert-warning">
        No active question.
    </div>
    <a id="check-new-question" href="." class="btn btn-light btn-light-border pull-right">
        Check for new question
    </a>

<?php else:
    $this->insert("session/view/question", [
        "question" => $question,
        "response" => $response,
        "responses" => $responses,
        "session" => $session,
        "totalQuestions" => $totalQuestions,
        "questionNumber" => $questionNumber
    ]);
endif; ?>

<!--<a href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionIdentifier())?>/discussion/" type="button" class="btn btn-success">
    <i class="fa fa-comments"></i>
    Discuss this class
</a>-->
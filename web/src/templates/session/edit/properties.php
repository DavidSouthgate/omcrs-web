<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $additionalUsers array User
 * @var $alert Alert
 * @var $session Session
 */

$title = $session->getSessionID() ? "Session Properties" : "New Session";

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

// Convert boolean values to html
$allowGuests            = $session->getAllowGuests()            ? " checked" : "";
$onSessionList          = $session->getOnSessionList()          ? " checked" : "";
$allowModifyAnswer      = $session->getAllowModifyAnswer()      ? " checked" : "";
$allowQuestionReview    = $session->getAllowQuestionReview()    ? " checked" : "";
$classDiscussionEnabled = $session->getClassDiscussionEnabled() ? " checked" : "";

$submitText = $session->getSessionID() ? "Save" : "Create";

// Whether this is a new session
$new = $session->getSessionID() ? 0 : 1;

// If this is a new session, 0 additional users
if($new) {

    // Array of possible choices
    $users = [];
}

// Otherwise not a new session
else {

    // add users to array
    $users = $additionalUsers;
}

?>
<?php $this->push("head"); ?>
    <link href="<?=$this->e($config["baseUrl"])?>css/session/edit/properties.css" rel="stylesheet">
<?php $this->stop(); ?>

<?php $this->push("end"); ?>
    <script>var SessionNew = <?=$new ? "true": "false"?>;</script>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/edit/properties.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<div class="row page-header">
    <div class="col-sm-12">
        <div class="float-left">
            <h1><?=$this->e($title)?></h1>
        </div>
        <?php if($session->getSessionIdentifier()): ?>
            <div class="float-right width-xs-full">
                <a href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/" class="btn btn-primary pull-right width-xs-full">Edit Session</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<form action="." method="POST" class="form-horizontal" style="display:block; width: 100%;">
    <input name="sessionIdentifier" value="<?=$this->e($session->getSessionIdentifier())?>" type="hidden">
    <input name="sessionID" value="<?=$this->e($session->getSessionID())?>" type="hidden">
    <div class="form-group row basic">
        <label class="col-sm-3 control-label" for="title">Title</label>
        <div class="col-sm-9">
            <input class="form-control" name="title" id="title" value="<?=$this->e($session->getTitle())?>" size="80" type="text" placeholder="Eg History 2B" maxlength="80">
        </div>
    </div>
    <!--
    <div class="form-group row advanced">
        <label class="col-sm-3 control-label" for="courseIdentifier">Course Identifier
            <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="" data-original-title="Used to import class list.">
                <i class="fa fa-question-circle" aria-hidden="true"></i>
            </a>
        </label>
        <div class="col-sm-9">
            <input class="form-control" name="courseID" id="courseID" value="<?=$this->e($session->getCourseID())?>" size="20" type="text" placeholder="Eg COMPSCI1357 " maxlength="20">
        </div>
    </div>
    -->
    <div class="form-group row">
        <div class="col-sm-3 offset-sm"></div>
        <div class="col-sm offset-sm basic">
            <div class="checkbox">
                <label>
                    <input type="hidden" value="0" name="allowGuests">
                    <input name="allowGuests" id="allowGuests" value="1" type="checkbox"<?=$allowGuests?>>
                    Allow Anonymous Guest Users
                </label>
            </div>
        </div>
        <div class="col-sm advanced">
            <div class="checkbox">
                <label>
                    <input type="hidden" value="0" name="onSessionList">
                    <input name="onSessionList" id="onSessionList" value="1" type="checkbox"<?=$onSessionList?>>
                    Display On User's Session List
                </label>
            </div>
        </div>
    </div>
    <fieldset>
        <div class="form-group row basic">
            <label class="col-sm-3 control-label" for="questionControlMode">
                Question Control Mode
                <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="" data-original-title="
                      <h1>Teacher Led</h1>
                      Only one question can be activated at any one time.

                      <h1>Student Paced</h1>
                      Multiple questions can be activated at the same time">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
            </label>
            <div class="col-sm-9">
                <select class="form-control" name="questionControlMode" id="questionControlMode">
                    <option value="0"<?=$session->getQuestionControlMode()==0 ? " selected" : ""?>>
                        Teacher Led
                    </option>
                    <option value="1"<?=$session->getQuestionControlMode()==1 ? " selected" : ""?>>
                        Student Paced
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group row advanced">
            <label for="defaultTimeLimit" class="col-sm-3 col-form-label">Default Time Limit</label>
            <div class="col-sm-2">
                <label class="form-check-label">
                    <input id="defaultTimeLimitEnable" class="form-check-input" value="" type="checkbox"<?=$session->getDefaultTimeLimit()!=0?" checked":""?>>
                    Enable
                </label>
            </div>
            <div class="col-sm-7">
                <input class="form-control" name="defaultTimeLimit" id="defaultTimeLimit" value="<?=$this->e($session->getDefaultTimeLimit())?>" size="8"
                       type="number" placeholder="Time Limit in Seconds"<?=$session->getDefaultTimeLimit()==0?" disabled":""?>>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 offset-sm"></div>
            <div class="col-sm offset-sm basic">
                <div class="checkbox">
                    <label>
                        <input type="hidden" value="0" name="allowModifyAnswer">
                        <input name="allowModifyAnswer" id="allowModifyAnswer" value="1" type="checkbox"<?=$allowModifyAnswer?>>
                        Allow students to change their answer</label>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="checkbox">
                    <label>
                        <input type="hidden" value="0" name="allowQuestionReview">
                        <input name="allowQuestionReview" id="allowQuestionReview" value="1" type="checkbox"<?=$allowQuestionReview?>>
                        Allow Students to view their answers after class</label>
                </div>
            </div>
        </div>
    </fieldset>
    <?php if($new || $session->checkIfUserIsOwner($user)):?>
        <div class="form-group row advanced">
            <label class="col-sm-3 control-label" for="courseIdentifier">
                Additional Users
                <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="" data-original-title="
                          Allows additional users to edit and run your session">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
            </label>
            <div class="col-sm-9">
                <div id="add-more-choices" class="input-add-more-container" data-minimum-count="0">
                    <?php if(count($users) > 0):?>
                        <?php $i = 0; ?>
                        <?php foreach($users as $user): ?>
                            <div class="input-group input-add-more-item">
                                <input id="user-<?=$i?>" name="user-<?=$i?>" class="form-control input-add-more-input user" type="text" value="<?=$this->e($user->getUsername())?>" tabindex="1" maxlength="80">
                                <button class="delete btn btn-light btn-light-border input-add-more-input" type="button" tabindex="2">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </div>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php else:?>
                        <div class="input-group input-add-more-item display-none">
                            <input id="user-0" name="user-0" class="form-control input-add-more-input user" type="text" value="" tabindex="1" maxlength="80">
                            <button class="delete btn btn-light btn-light-border input-add-more-input" type="button" tabindex="2">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </div>
                    <?php endif?>
                </div>
                <div id="add-more-button-container" class="col-sm-12 input-add-more-button" data-input-container-id="add-more-choices">
                    <button class="btn btn-primary input-add-more-input float-right width-xs-full" type="button">
                        Add User
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group row">
        <div class="col-sm-9 offset-sm-3">
            <input class="submit btn btn-primary margin-xs-bottom-10 width-xs-50percent-2point5px" name="submit" value="<?=$submitText?>" type="submit">
            <a onclick="goBack()" class="submit btn btn-light btn-light-border margin-xs-bottom-10 width-xs-50percent-2point5px">Cancel</a>

<!--            <div class="pull-right width-xs-full">-->
<!--                <a id="view-advanced-settings" class="btn btn-light btn-light-border width-xs-full">-->
<!--                    View Advanced Settings-->
<!--                </a>-->
<!--                <a id="hide-advanced-settings" class="btn btn-light btn-light-border width-xs-full">-->
<!--                    Hide Advanced Settings-->
<!--                </a>-->
<!--            </div>-->
        </div>
    </div>
</form>

<style>

    .tooltip {
        margin-left: 8px;
    }

    .tooltip h1 {
        font-size: 18px;
        margin: 0;
        margin-top: 10px;
        padding: 0;
        font-weight: bolder;
    }


    .form-check-label {
        margin-top: 3px;
    }

    .btn.cancel {
        margin-left: 10px;
    }

    .delete.btn {
        border: 1px solid #ced4da;
    }

    /*.advanced {*/
        /*display: none;*/
    /*}*/

    /*.basic{*/
        /*display:flex;*/
    /*}*/
</style>
<?php
/**
 * @var $config array
 * @var $user User
 * @var $responses Response[]
 * @var $session Session
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => "Sessions",
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert
    ]
);
?>
<div class="page-header">
    <h1 class="row">
        <div class="col-sm-9">
            <h1><?=$this->e($session->getTitle())?></h1>
            <h3>Session Identifier: <?=$this->e($session->getSessionIdentifier())?></h3>
        </div>
    </h1>
</div>
<div class="row">
    <div class="col-sm-9">
        <h2 class="pull-left">Review Responses</h2>
    </div>
</div>
<ul class="list-group responses-list">
    <?php // If user has responses, display them ?>
    <?php if(sizeof($responses) > 0): ?>
        <?php foreach($responses as $r): ?>
            <li class="list-group-item response-item">
                <div class="pull-left question-text">
                    <?=$r->getUsername();?>
                </div>
                <div class="pull-right response">
                    <?=$r->getResponse();?>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else:?>
        <li class="list-group-item response-item">
            <div class="pull-left">
                No Responses Found
            </div>
        </li>
    <?php endif; ?>
</ul>

<style>
    .response-item{
        width:65%;
        height:auto;
        margin: auto;
    }
    .response{
        white-space: nowrap;
        width: 50%;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: right;
    }
</style>
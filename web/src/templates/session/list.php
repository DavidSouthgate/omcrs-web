<?php
/**
 * @var $config array
 * @var $user User
 * @var $sessions Session[]
 */
?>
<ul class="list-group session-list">
    <li class="no-sessions">
        No Sessions Found
    </li>
    <?php // If user has sessions, display them ?>
    <?php if(sizeof($sessions) > 0): ?>
        <?php foreach($sessions as $s): ?>
            <?php $created = date($config["datetime"]["datetime"]["long"], $s->getCreated()); ?>
            <li class="list-group-item session-item">
                <div class="pull-left">
                    <span id="session-link" class="session-title">
                        <a id="session-link-url" href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($s->getSessionIdentifier())?>/<?=$s->checkIfUserCanEdit($user)?"edit/":""?>">
                            <?=$s->getTitle()?$this->e($s->getTitle()):"Session"?>
                        </a>
                    </span>
                    <span class="session-number">
                        <i class="fa fa-hashtag"></i><?=$this->e($s->getSessionIdentifier())?>
                    </span>
                    <span class="session-date text-muted">
                        Created <?=$this->e($created)?>
                    </span>
                </div>
                <div class="actions-confirm-delete width-xs-full">
                    <div class="btn-group pull-right actions width-xs-full" aria-label="Actions">
                        <?php if(isDesktopApp() && $s->getQuestionControlMode() === 0): ?>
                            <button type="button" class="btn btn-light btn-light-border width-xs-full" onclick="liveViewEnter(<?=$this->e($s->getSessionIdentifier())?>)">
                                <i class="fa fa-play"></i> Run
                            </button>
                        <?php endif; ?>
<!--                        // If the user can edit this session, view edit controls-->
                        <?php if($s->getAllowQuestionReview() && !$s->checkIfUserCanEdit($user)): ?>
                            <button data-href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($s->getSessionIdentifier())?>/review/" type="button" class="btn btn-light btn-light-border width-xs-full" onclick="onclickHref(this)">
                                <i class="fa fa-book"></i> Review
                            </button>
                        <?php endif; ?>
                        <?php if($s->checkIfUserCanEdit($user)):?>
                            <button data-href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($s->getSessionIdentifier())?>/edit/" type="button" class="btn btn-light btn-light-border width-xs-full" onclick="onclickHref(this)">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                        <?php endif?>
                        <button data-href="<?=$this->e($config["baseUrl"])?>session/<?=$this->e($s->getSessionIdentifier())?>/" type="button" class="btn btn-light btn-light-border width-xs-full" onclick="onclickHref(this)">
                            <i class="fa fa-plus"></i> Join
                        </button>
                        <?php if($s->checkIfUserCanDelete($user)): ?>
                            <button type="button" class="btn btn-light btn-light-border delete width-xs-full">
                                <i class="fa fa-trash-o"></i> Delete
                            </button>
                        <?php endif; ?>
                    </div>
                    <?php if($s->checkIfUserCanDelete($user)): ?>
                        <div class="btn-group pull-right confirm-delete width-xs-full" aria-label="Confirm Delete">
                            <button type="button" class="btn btn-danger btn-danger-border confirm width-xs-full" data-session-identifier="<?=$this->e($s->getSessionIdentifier())?>">
                                <i class="fa fa-check"></i> Confirm
                            </button>
                            <button type="button" class="btn btn-light btn-light-border cancel width-xs-full">
                                <i class="fa fa-times"></i> Cancel
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $sessions Session[]
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

<?php $this->push("end"); ?>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/list.js" crossorigin="anonymous"></script>
<?php $this->stop(); ?>

<div class="row">
    <div class="col-sm-6">
        <h1 class="pull-left">My Sessions</h1>
    </div>
    <?php if($user->isSessionCreator() || $user->isAdmin()): ?>
        <div class="col-sm-6" style="width: 100%;">
            <a href="<?=$this->e($config["baseUrl"])?>session/new/" class="btn btn-primary width-xs-full margin-xs-bottom-10 pull-right">New Session</a>
        </div>
    <?php endif; ?>
</div>
<?=$this->fetch("session/list", ["sessions"=>$sessions, "user" => $user, "config"=>$config])?>
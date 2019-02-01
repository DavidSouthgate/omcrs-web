<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $username string
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => "Change Password",
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert
    ]
);
?>

<?php $this->push("head"); ?>
<link href="<?=$this->e($config["baseUrl"])?>css/login.css" rel="stylesheet">
<?php $this->stop(); ?>

<form id="login" action="<?=$this->e($config["baseUrl"])?>changepassword/" method="post">
    <h1>Change Password</h1>
    <?php if(!$username): ?>
        <div class="loginGroup">
            <input id="oldPassword" name="oldPassword" class="form-control" placeholder="Old Password" type="password">
        </div>
    <?php else: ?>
        <input id="username" name="username" type="hidden" value="<?=$username?>">
    <?php endif; ?>
    <div class="loginGroup">
        <input id="newPassword" name="newPassword" class="form-control" placeholder="New Password" type="password">
        <input id="verifyPassword" name="verifyPassword" class="form-control" placeholder="Verify Password" type="password">
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
</form>

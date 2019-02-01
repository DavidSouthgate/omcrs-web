<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 */
$this->layout("template",
    [
        "config" => $config,
        "title" => "Login",
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

<form id="login" action="<?=$this->e($config["baseUrl"])?>login/" method="post">
    <h1>Login</h1>
    <div class="loginGroup">
        <input id="username" name="username" class="form-control" placeholder="Username" type="text">
        <input id="password" name="password" class="form-control" placeholder="Password" type="password">
    </div>

    <button class="btn btn-lg btn-primary btn-block" id="login-btn" type="submit">Login</button>

    <?php
    // If using native login and registartion has been enabled
    if($config["login"]["type"] === "native" && $config["login"]["register"]): ?>
        <a href="<?=$this->e($config["baseUrl"])?>register/" id="register-btn" class="btn btn-lg btn-light btn-light-border btn-block" role="button">Register</a>
    <?php endif; ?>
    <div id="anonymous">
        <a href="<?=$this->e($config["baseUrl"])?>login/anonymous/">Anonymous Guest Access</a>
    </div>
</form>
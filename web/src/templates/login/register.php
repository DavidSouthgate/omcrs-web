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
        "title" => "Register",
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

<form id="login" action="<?=$this->e($config["baseUrl"])?>register/" method="post">
    <h1>Register</h1>
    <div class="loginGroup">
        <input id="username" name="username" class="form-control" placeholder="Username" type="text">
        <input id="password" name="password" class="form-control" placeholder="Password" type="password">
        <input id="verifyPassword" name="verifyPassword" class="form-control" placeholder="Verify Password" type="password">
    </div>
    <div class="loginGroup">
        <input id="givenName" name="givenName" class="form-control" placeholder="Given Name" type="text">
        <input id="surname" name="surname" class="form-control" placeholder="Surname" type="text">
        <input id="email" name="email" class="form-control" placeholder="Email" type="email">
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
</form>

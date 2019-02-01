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
        "title" => "Anonymous Guest Login",
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
    ]
);
?>

<?php $this->push("head"); ?>
    <link href="<?=$this->e($config["baseUrl"])?>/css/login.css" rel="stylesheet">
<?php $this->stop(); ?>

<form id="login" action="<?=$this->e($config["baseUrl"])?>login/anonymous/" method="post">
    <h1>Anonymous Guest Login</h1>
    <div class="loginGroup">
        <input name="nickname" id="nickname" class="form-control" placeholder="Nickname (Optional)" type="text">
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
</form>
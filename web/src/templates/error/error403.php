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
        "title" => "Error 403",
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user
    ]
);
?>

<div class="error-container">
    <h1>Error 403: Forbidden</h1>
    <p class="lead">
        You do not have permission to view this page.
    </p>
    <p class="lead">
        You can either <a href="javascript:history.back()">go back</a> and try again or return to the <a href="<?=$this->e($config["baseUrl"])?>">home page</a>
    </p>
</div>

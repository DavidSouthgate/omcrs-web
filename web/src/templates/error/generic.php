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
        "title" => $this->e($title),
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user
    ]
);
?>

<div class="error-container">
    <h1><?=$this->e($title)?></h1>
    <p class="lead">
        <?=$message?>
    </p>
    <p class="lead">
        You can either <a href="javascript:history.back()">go back</a>
        <?php if(!$permanent): ?>
            and try again
        <?php endif; ?>
        or return to the <a href="<?=$this->e($config["baseUrl"])?>">home page</a>
    </p>
</div>

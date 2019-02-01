<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $noHeaderFooter bool
 */
$this->layout("base",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user,
        "alert" => $alert,
        "logo" => "img/logo.png",
        "noHeaderFooter" => $noHeaderFooter
    ]
);
?>
<?=$this->section("content")?>
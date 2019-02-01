<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $logo string
 * @var $footer string
 * @var $noHeaderFooter bool
 */

// Ensure $noHeaderFooter is a valid boolean
$noHeaderFooter = isset($noHeaderFooter) ? !!$noHeaderFooter : false;

$user = !$user ? new User() : $user;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?=isset($title) ? $this->e($title)." | " : ""?>OMCRS</title>
        <meta name="description" content="<?=$this->e($description)?>">
        <meta name="mobile-web-app-capable" content="yes">
        <title>OMCRS</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet">

        <link rel="stylesheet" href="<?=$this->e($config["baseUrl"])?>css/bootstrap-4.0.0-beta.2.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?=$this->e($config["baseUrl"])?>css/style.css" rel="stylesheet">
        <link href="<?=$this->e($config["baseUrl"])?>css/bootstrap-extra.css" rel="stylesheet">
        <?=$this->section("head")?>

        <?php // If this is the desktop app, apply desktop app specific CSS ?>
        <?php if(isDesktopApp()): ?>
            <link href="<?=$this->e($config["baseUrl"])?>css/style-desktop.css" rel="stylesheet">
        <?php endif; ?>
    </head>
    <body<?=$noHeaderFooter ? " class='noHeaderFooter'" : ""?>>
        <!--[if lt IE 9]>
        <div id="incompatible-browser">
            <h1>Incompatible Browser</h1>
            <p>
                You are using a web browser which is not compatible with OMCRS.
            </p>
            <p>
                Please consider using/installing one of the following modern web browsers:
            </p>
            <p>
                <a href="https://www.mozilla.org/firefox/" target="_blank">
                    Mozilla Firefox
                </a>
                <br>
                <a href="https://www.google.com/chrome/" target="_blank">
                    Google Chrome
                </a>
                <br>
                <a href="https://www.microsoft.com/windows/microsoft-edge" target="_blank">
                    Microsoft Edge
                </a>
                <br>
                <a href="https://www.microsoft.com/en-gb/download/internet-explorer.aspx" target="_blank">
                    Internet Explorer 11
                </a>
            </p>
        </div>
        <![endif]-->
        <?php
        if(!$noHeaderFooter) {
            $this->insert("partials/navigation", ["config" => $config, "logo" => $logo, "user" => $user]);
        }
        ?>
        <main role="main">
            <?php $this->insert("partials/breadcrumb", ["breadcrumbs" => $breadcrumbs]) ?>
            <?=$this->section("preContent")?>
            <div class="container">
                <div id="alert">
                    <?php $this->insert("partials/alert", ["alert" => $alert]) ?>
                </div>
                <?=$this->section("content")?>
            </div>
            <?=$this->section("postContent")?>
        </main>
        <?php if(!isDesktopApp() && !$noHeaderFooter): ?>
            <footer class="footer">
                <?php $this->insert(isset($footer) ? $footer : "partials/footer", ["config" => $config]) ?>
            </footer>
        <?php endif; ?>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?=$this->e($config["baseUrl"])?>js/jquery-3.2.1.min.js"></script>
        <?php if(isDesktopApp()): ?>
            <script>try { window.$ = window.jQuery = module.exports; } catch(e) {}</script>
        <?php endif; ?>
        <script src="<?=$this->e($config["baseUrl"])?>js/popper.min.js"></script>
        <script src="<?=$this->e($config["baseUrl"])?>js/bootstrap-4.0.0-beta.2.min.js"></script>

        <script>
            var baseUrl = "<?=$this->e($config["baseUrl"])?>";
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });

            /**
             * Displays an alert in HTML to the user
             * @param alert in format "
             *      var alert = {
             *          title: 'TITLE HERE',
             *          message: 'MESSAGE HERE',
             *          type: 'danger',
             *          dismissable: true,
             *      };
             * "
             */
            function alerter(alert) {
                var aClass = alert.dismissable ? " alert-dismissable" : "";
                var title = alert.title ? "<strong>" + alert.title + "</strong> " : "";

                // Construct HTML for alert
                var html =  "<div class='alert alert-" + alert.type + aClass + "' role='alert'>";
                if(alert.dismissable)
                    html +=     "<a href='#' class='close' data-dismiss='alert' aria-label='close' title='close'>×</a>";
                html +=     title + alert.message;
                html +=     "</div>";

                $("#alert").append(html);
            }
        </script>

        <script src="<?=$this->e($config["baseUrl"])?>js/bootstrap-extra.js" crossorigin="anonymous"></script>
        <?php if(!isDesktopApp()): ?>
            <script src="<?=$this->e($config["baseUrl"])?>js/web-app.js" crossorigin="anonymous"></script>
        <?php endif; ?>
        <?=$this->section("end")?>
    </body>
</html>
<?php
/**
 * @var $alert Alert
 */
if($alert):
    $type = $alert->getType() ? $alert->getType() : "";
    $message = $alert->getMessage() ? $this->e($alert->getMessage()) : "";;
    $title = $alert->getTitle() ? "<strong>".$this->e($alert->getTitle())."</strong> " : "";
    $class = $alert->getDismissable() ? " alert-dismissable" : "";
    ?>
    <div class="alert alert-<?=$this->e($type . $class)?>" role="alert">
        <?php if($alert->getDismissable()): ?>
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        <?php endif; ?>
        <?=$title . $message?>
    </div>
<?php endif; ?>
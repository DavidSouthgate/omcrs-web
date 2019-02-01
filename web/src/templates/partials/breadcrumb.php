<?php
/**
 * @var $breadcrumbs Breadcrumb
 */
?>
<?php if($breadcrumbs): ?>
<div class="container">
    <nav id="breadcrumb" aria-label="breadcrumb" role="navigation">
        <div class="breadcrumb-right pull-right">
            <a href="#" onclick="goBack();">Back</a>
        </div>
        <ol class="breadcrumb">
            <?php foreach($breadcrumbs->getItems() as $breadcrumb): ?>
                <?php if($breadcrumb->hasLink()): ?>
                    <li class="breadcrumb-item">
                        <a href="<?=$this->e($breadcrumb->getLink())?>">
                            <?=$this->e($breadcrumb->getTitle())?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?=$this->e($breadcrumb->getTitle())?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
</div>
<?php endif; ?>
<?php
    $this->start('body');
    $this->load('components/leftPanel.tpl.php');
?>
    <div class="rightPanel docs">
        <div class="box">
            <h1>Permissions</h1>
            <?php echo $grid; ?>
        </div>
    </div>
    <div class="clearfix"></div>


<?php
    $this->end('body');

    // use the template from sample/docs/resouces/templates/
    $this->extend($this->getRouteRequest()->getAppDirectory('docs') . 'resources/templates/layout.tpl.php');
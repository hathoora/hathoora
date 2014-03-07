<?php
    $this->start('body');
?>
    <div>
        <a href="/docs">Click here to read documentation.</a>
    </div>
<?php
    $this->end('body');

    // use the template from sample/docs/resouces/templates/
    $this->extend($this->getRouteRequest()->getAppDirectory('docs') . 'resources/templates/layout.tpl.php');
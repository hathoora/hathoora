<?php
    $this->start('body');
?>
    <div class="leftPanel">
        so?
    </div>
    <div class="rightPanel">
        <ul>
            <li>Simple </li>
        </ul>
    </div>
    <div class="clearfix"></div>


<?php
    $this->end('body');

    // use the template from sample/sampleSite/resouces/templates/
    $this->extend($this->getRouteRequest()->getAppDirectory('sampleSite') . 'resources/templates/layout.tpl.php');
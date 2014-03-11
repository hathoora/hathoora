<?php
    $this->start('body');
    $this->load('components/leftPanel.tpl.php');
?>
    <div class="rightPanel docs">
        <div class="box">
            <h1>Admin Panel</h1>
            <p>
                Please import <code class="inline">HATHOORA_ROOTPATH/app/sample/admin/data/admin-sample.sql</code> to follow the examples.
            </p>
            <p>
                This is admin home screen. Use the navigation on left to browse around. This sample admin application has not implemented any authentication and left it up to you to implement to one that meet your needs.
            </p>
            <p>
                Source code for this application is locacted at <code class="inline">HATHOORA_ROOTPATH/app/sample/admin</code>,  you can also view it on <a href="https://github.com/Pakeela/hathoora/tree/master/app/sample/admin" target="_blank">github</a>.
            </p>
            <p>
                In this admin application you would see the following in action:
            </p>
            <ul>
                <li><a href="/docs/v1/translation">Translations</a></li>
                <li><a href="/docs/v1/cache">Cache</a></li>
                <li><a href="/docs/v1/grid">Grid</a></li>
                <li><a href="/docs/v1/database">Database</a></li>
            </ul>
        </div>
    </div>
    <div class="clearfix"></div>


<?php
    $this->end('body');

    // use the template from sample/docs/resouces/templates/
    $this->extend($this->getRouteRequest()->getAppDirectory('docs') . 'resources/templates/layout.tpl.php');
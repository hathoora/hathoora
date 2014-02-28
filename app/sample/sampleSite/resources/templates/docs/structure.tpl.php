<div class="box">
    <h1>File Structure</h1>
    <ul class="outline">
        <li><a href="#hathoora">Hathoora PHP Framework Structure</a></li>
        <li><a href="#application">Application Structure</a></li>
    </ul>

    <a name="hathoora"></a>
    <h2>Hathoora PHP Framework Structure</h2>
    <p>
        When you download Hathoora PHP Framework, you would notice the following directory structure for <code class="inline">HATHOORA_ROOTPATH</code>. In most cases you do not need to change anything here.
    </p>
    <pre>
        <code class="hljs bash">
            # this is where your application source would go, more on this below...
            |-- app
            |   |-- sample
            |   |   |-- admin
            |   |   |-- site


            # this is where your application definication would go
            |-- boot
            |   `-- config
            |       |-- app_dev.yml
            |       `-- app_prod.yml


            # learn more about composer @ https://getcomposer.org/
            |-- composer.json
            |-- composer.lock
            |-- composer.phar

            # Public facing
            |-- docroot
            |   |-- _assets
            # it is recommended to put your assets (css, js, images etc.. in here)
            |   |   `-- _hathoora

            # the frontend controller
            |   `-- index.php

            # third party code goes here (via composer)
            `-- vendor
                |-- autoload.php
                |-- composer
                `-- hathoora
        </code>
    </pre>

    <a name="application"></a>
    <h2>Application Structure</h2>
    <p>
        A typical application would have the following structure inside <code class="inline">HATHOORA_ROOTPATH/app</code> folder.
    </p>
    <pre>
        <code class="hljs bash">
            #boot/app_ENV.yml
            app:
                myNameSpace:
                    directory: myCompany


            #File structure
            |-- app
            |   |-- myCompany
            |   |   |-- myNameSpace
            |   |   |   |-- config
            |   |   |   |   |-- config_dev.yml
            |   |   |   |   |-- config_prod.yml
            |   |   |   |   |-- config_ENV.yml
            |   |   |   |-- controller
            |   |   |   |   |-- defaultController.php
            |   |   |   |   |-- abcController.php
            |   |   |   |   |-- xyzController.php
            |   |   |   `-- resources
            |   |   |       |-- assets (optional to use with gulabooAssets service)
            |   |   |       |   |-- js/css/images
            |   |   |       `-- templates
            |   |   |           |-- layout.tpl.php
            |   |   |           |-- templateName.tpl.php
            |   |   |   |-- model/logic/lib (optional)

        </code>
    </pre>

    <ul>
        <li><code class="inline">Config</code>: This is where you would have application specific configurations</li>
        <li><code class="inline">Controller</code>: This is where you would have contoller code</li>
        <li><code class="inline">Resources</code>: This is where application templates and assets are loaded.</li>
        <li><code class="inline">Model/Database/Lib/Logic</code>: This is optional and you can name it whatever you want</li>
    </ul>
</div>
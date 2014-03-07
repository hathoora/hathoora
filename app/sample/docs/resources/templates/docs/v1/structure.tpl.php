<div class="box">
    <h1>File Structure</h1>
    <ul class="outline">
        <li><a href="#hathoora">Hathoora PHP Framework Structure</a></li>
        <li><a href="#application">Source Application Structure</a></li>
    </ul>

    <a name="hathoora"></a>
    <h2>Hathoora PHP Framework Structure</h2>
    <p>
        When you download Hathoora PHP Framework, you would notice the following directory structure for <code class="inline">HATHOORA_ROOTPATH</code>. In most cases you do not need to change anything here.
    </p>
    <pre>
        <code class="hljs Ini">
            # Directory: HATHOORA_ROOTPATH/

            # this is where your application source would go, more on this below...
            app/

            # this is where your application definication would go
            boot/
                config/
                    app_dev.yml
                    app_prod.yml

            # Public facing
            docroot/
                _assets

                # the frontend controller
                index.php

            # third party code goes here (via composer)
            vendor/
        </code>
    </pre>

    <a name="application"></a>
    <h2>Source Application Structure</h2>
    <p>
        A typical source application would have the following structure inside <code class="inline">HATHOORA_ROOTPATH/app</code> folder.
    </p>
    <pre>
        <code class="hljs Ini">
            # File structure of an app - HATHOORA_ROOTPATH/app

            app/
                myNameSpace/    <-- also the name of app
                    config/
                    controller/
                    resources/
                        assets/            (optional)
                        templates/
                    model/                 (optional)

        </code>
    </pre>

    <p>To summarize:</p>
    <ul>
        <li><code class="inline">Config</code>: This is where you would have application specific configurations.</li>
        <li><code class="inline">Controller</code>: This is where you would have contoller code.</li>
        <li><code class="inline">Resources</code>: This is where application templates and assets are loaded from.</li>
        <li><code class="inline">Model</code>: This is optional and you can name it whatever you want.</li>
    </ul>
</div>
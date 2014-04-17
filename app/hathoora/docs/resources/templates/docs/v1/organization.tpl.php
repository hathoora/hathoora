<div class="box">
    <h1>Organization</h1>

    <p>
        You can install Hathoora PHP Framework anywhere you want. Directory to which you install is called <code class="inline">HATHOORA_ROOTPATH</code>.
    </p>
    <a name="layout"></a>
    <h2>Example layout</h2>
    <p>
        Here is the default layout of Hathoora PHP Framework installation.
    </p>    
    
    <pre>
        <code class="hljs Ini">
            # Directory: HATHOORA_ROOTPATH/

            # App root
            app/
                
                # Folders for organization of code
                sample      
                    admin/                      <-- name of application
                        config/
                            config_dev.yml
                            config_prod.yml
                        controller/
                        resources/
                            assets/
                                css/
                                js/
                                images/
                            templates/
                        model/ 
                        
                    docs/                 <-- name of second supported application
                        ...
                
                # An app without organizational folder
                site/                           <-- name of third supported application
                    config/
                    controller/
                    resources/
                    

            # this is where your application definication would go
            boot/
                config/
                    app_dev.yml
                    app_prod.yml

            # Public facing
            docroot/
                _assets/
                    # place your static files here
                    
                # the frontend controller
                index.php

            # third party code goes here (via composer)
            vendor/
        </code>
    </pre>

    <a name="app"></a>
    <h2>The app/ Directory</h2>
    <p>
        The app directory conatins source of your applications. You can have single application of multiple applications. Each application itself contains the following:
    </p>
    <ul>
        <li><code class="inline">Config</code>: stores application specific configuations which are discussed in more details <a href="/docs/v1/configuration#configuration">here</a>.</li>
        <li><code class="inline">Controller</code>: stores application specific <a href="/docs/v1/controller">controllers</a> to intercept requests. </li>
        <li><code class="inline">Resources</code>: stores application specific <a href="/docs/v1/view">templates</a> and <a href="/docs/v1/assets">assets</a>.</li>
        <li><code class="inline">Model</code>: stores application specific <a href="/docs/v1/model">models</a> or logic. This can be named to anything.</li>
    </ul>
    
    <a name="boot"></a>
    <h2>The boot/ Directory</h2>
    <p>
        The boot directory contains a file called <code class="inline">app_HATHOORA_ENV.yml</code>. This is where you define supported application(s).
    </p>    
    <p>
        Note that <code class="inline">HATHOORA_ENV</code> represent the environment your application is running in, this could be <b>dev</b>, <b>prod</b> or <b>anything</b>.
    </p>
    
    <a name="docroot"></a>
    <h2>The docroot/ Directory</h2>
    <p>
        This is public facing directoy which should be <a href="/docs/v1/installation#apache">configured</a> in your webserver.
    </p> 

    <a name="vendor"></a>
    <h2>The vendor/ Directory</h2>
    <p>
        This is where <a href="http://getcomposer.org/" target="_blank">Composer</a> packages are installed. Composer is a dependency management library for PHP. You can find out more about it <a href="http://getcomposer.org/" target="_blank">here</a>.
    </p>     
</div>
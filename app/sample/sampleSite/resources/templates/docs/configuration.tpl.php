<div class="box">
    <h1>Configuration</h1>
    <ul class="outline">
        <li><a href="#environment">Defining Environment</a></li>
        <li><a href="#application">Setting up Application(s)</a></li>
        <ul>
            <li><a href="#application-simple">Simple Application</a></li>
            <li><a href="#application-multiple">Multiple Application</a></li>
            <li><a href="#application-environments">Multiple Environments</a></li>
            <li><a href="#application-organization">Code Organization</a></li>
        </ul>
        <li><a href="#configuration">Application Source Configuration</a></li>
    </ul>

    <a name="environment"></a>
    <h2>Defining Environment</h2>
    <p>
        To setup environment for your hathoora application you can use the following options:
    </p>
    <p>
        1. Define <code class="inline">HATHOORA_ENV</code> in Apache using <a href="http://httpd.apache.org/docs/2.2/mod/mod_env.html" target="_blank">SetEnv</a> directive. It can be defined in .htaccess of vhost configuration file.
    </p>

    <pre>
        <code class="hljs apache">
            &lt;VirtualHost *:80&gt;
                DocumentRoot /some/path/hathoora/docroot
                ServerName mysite.com

                SetEnv HATHOORA_ENV prod

                &lt;Directory /some/path/hathoora/docroot&gt;
                    AllowOverride All
                &lt;/Directory&gt;
            &lt;/VirtualHost&gt;
        </code>
    </pre>

    <p>
        2. Or by defining <code class="inline">$env</code> variable in <code class="inline">HATHOORA_ROOTPATH/index.php</code>
    </p>
    <pre>
        <code class="hljs php">
            # File: HATHOORA_ROOTPATH/index.php

            // use prod environment by default
            $env = 'prod';

            if (isset($_SERVER['HATHOORA_ENV']))
                $env = $_SERVER['HATHOORA_ENV'];

            $env = "prod";
            use hathoora\kernel;

            $kernel = new kernel($env);
        </code>
    </pre>

    <p>
        Environment value is then stored as constant that you may use in your code <code class="inline">HATHOORA_ENV</code>
    </p>

    <a name="application"></a>
    <h2>Setting up Application(s)</h2>
    <p>
        Hathoora Framrwork support mutiple applications (or websites). Applications are defined in <code class="inline">HATHOORA_ROOTPATH/boot/config/app_ENV.yml</code>.
    </p>
    <p>
        You can define things like:
    </p>
    <ul>
        <li>Multiple applications (or websites)</li>
        <li>Multiple applications (or websites) for dev/prod/stag/etc.. environments</li>
        <li>Regex pattern</li>
        <li>Directory for code organization</li>
        <li>Namespacing</li>
        <li>Custom route dispatchers for advanced routing</li>
    </ul>
    <p>
        Listed below are some example application configurations to give you an idea about various possibilites.
    </p>

    <br/>
    <p>
        <a name="#application-simple"></a>
        <b>Example 1:</b> If you have only one application then you can use something like the following:
    </p>
    <pre>
        <code class="hljs Ini">
            # File: HATHOORA_ROOTPATH/boot/config/app_ENV.yml

            app:
                mySite:    <-- name of app
                    default: true # will be used as default
        </code>
    </pre>
    <p>
        In the preceding example, the source for your application will be located in <code class="inline">HATHOORA_ROOTPATH/app/mySite</code>
    </p>

    <br/>
    <p>
        <a name="#application-multiple"></a>
        <b>Example 2:</b> For supporting multiple applications like the following:
    </p>
    <ul>
        <li>http://www.website1.com</li>
        <li>http://www.website2.com</li>
    </ul>
    <pre>
        <code class="hljs Ini">
            # File: HATHOORA_ROOTPATH/boot/config/app_ENV.yml

            app:
                website1:   <-- name of app
                    pattern: '^www.website1.com(|/)'
                    default: true # will be used as default
                    directory: myCompany

                website2:   <-- name of app
                    pattern: '^www.website2.com(|/)'
                    directory: myCompany
        </code>
    </pre>
    <p>
        In the preceding example, the source will be located as following:
    </p>
    <ul>
        <li>
            http://www.website1.com -> <code class="inline">HATHOORA_ROOTPATH/app/myCompany/website1</code>
        </li>
        <li>
            http://www.website2.com -> <code class="inline">HATHOORA_ROOTPATH/app/myCompany/website2</code>
        </li>
    </ul>

    <br/>
    <p>
        <a name="#application-environments"></a>
        <b>Example 3:</b> For supporting prod and dev enviornments:
    </p>
    <ul>
        <li>http://dev.mysite.com</li>
        <li>http://www.mysite.com</li>
    </ul>
    <pre>
        <code class="hljs Ini">
            # File: HATHOORA_ROOTPATH/boot/config/app_prod.yml
            app:
                mysite:
                    pattern: '^www.mysite.com(|/)'
                    default: true
                    directory: myCompany



            # File: HATHOORA_ROOTPATH/boot/config/app_dev.yml
            app:
                mysite:
                    pattern: '^dev.mysite.com(|/)'
                    default: true
                    directory: myCompany
        </code>
    </pre>

    <br/>
    <p>
        <a name="#application-organization"></a>
        <b>Example 4:</b> Seperation of code. If you wanted to have seperate code (for authentication/organization) between main website and admin panel in a scenario like the following:
    </p>
    <ul>
        <li>http://www.mysite.com</li>
        <li>http://www.mysite.com/admin</li>
        <li>http://api.mysite.com</li>
    </ul>
    <pre>
        <code class="hljs Ini">
            # File: HATHOORA_ROOTPATH/boot/config/app_ENV.yml

            app:
                admin:
                    pattern: '^www.mysite.com/admin(|/)'
                    directory: myCompany

                site:
                    pattern: '^www.mysite.com(|/)'
                    default: true # will be used as default
                    directory: myCompany

                api:
                    pattern: '^api.mysite.com(|/)'
                    directory: myApiDirectory

                # there is no URL for this one, this is purely for organization of code
                helper:
                    directory: myApiDirectory
        </code>
    </pre>
    <p>
        In the preceding example, order matters - first hit. Code for your application will be located as following:
    </p>
    <ul>
        <li>
            http://www.mysite.com -> <code class="inline">HATHOORA_ROOTPATH/app/myCompany/site</code>
        </li>
        <li>
            http://www.mysite.com/admin -> <code class="inline">HATHOORA_ROOTPATH/app/myCompany/admin</code>
        </li>
        <li>
            http://api.mysite.com -> <code class="inline">HATHOORA_ROOTPATH/app/myApiDirectory/api</code>
        </li>
        <li>
            NO URL, purely for organization -> <code class="inline">HATHOORA_ROOTPATH/app/myApiDirectory/helper</code>
        </li>
    </ul>

    <a name="configuration"></a>
    <h2>Application Source Configuration</h2>
    <p>
        Application source configuration are located at <code class="inline">HATHOORA_ROOTPATH/app/directory/namespace/config/config_ENV.yml</code> and can be nested.
    </p>
    <p>
        Sample configuration is shown below:
    </p>
    <pre>
        <code class="hljs Ini">
            # File HATHOORA_ROOTPATH/app/directory/namespace/config/config_ENV.yml

            # Import config which will be overwritten
            imports:
                - { resource: config_gold.yml }

            # framework configurations..
            hathoora:

                # assets management
                gulaboo:
                    assets:
                        enabled: 1
                        version: v1
                translation:
                    enabled: 1
                    debug: 0

                logger:
                    profiling:
                        enabled: 0
                    logging:
                        enabled: 0
                        level: DEBUG
                    webprofiler:
                        enabled: 0
                        content_types: ['text/html']
                        skip_on_ajax: 1
                        skip_on_post_params: []
                        skip_on_get_params: []

                template:
                    engine:
                        name: Stuob # other option is Smarty
                    # when using smarty as the engine, using the following configurations
                    Smarty:
                        caching: 0
                        cache_lifetime: 0
                        ....

                database:
                    default: mysql://dbuser:dbpassword@dbhost:3306/dbname
                    db2: mysql://dbuser:dbpassword@dbhost:3306/dbname

                    # Advanced configuration also avaialble, see Databases for more information

                # for hathoora cache service
                cache:
                    debug: 0
                    pools:
                        common: { driver: 'memcache', servers: [{host: localhost, port: 11211}]}
                        commonRedis: { driver: 'redis', servers: [{host: localhost, port: 6379}]}



            # other custom configurations
            myConfig:
                var1: value1

            myConfig2:
                nested1:
                    var1: value1



            # to define services
            services:
                assets:
                    class: \namespace\class
                    type: static

                #; returns logged in user obect
                service2:
                    class: \namespace\class
                    # when you call the service, following method on the class is executed and returned
                    method: functionToExec

                #hathoora cache service
                cache:
                    class: \hathoora\cache\cache
                    calls:
                        setContainer: [ @container@ ]
                    type: static

                # factory service
                cache.posts:
                    factory_service: @cache@
                    factory_method: pool
                    factory_method_args: [ "common" ]



            # observers
            listeners:
                event:
                    listener1:
                        class: \namespace\class
                        method: functionToPassTo

                    listener2:
                        class: \namespace\class
                        method: functionToPassTo
        </code>
    </pre>
</div>
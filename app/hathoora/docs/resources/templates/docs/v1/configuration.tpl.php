<div class="box">
    <h1>Configuration</h1>
    <ul class="outline">
        <li><a href="#environment">Defining Environment</a></li>
        <li><a href="#application">Supporting Application(s)</a></li>
        <ul>
            <li><a href="#applicationsimple">Simple Application</a></li>
            <li><a href="#applicationmultiple">Multiple Application</a></li>
            <li><a href="#applicationenvironments">Multiple Environments</a></li>
            <li><a href="#applicationorganization">Code Organization</a></li>
        </ul>
        <li><a href="#configuration">Application Specific Configuration</a></li>
    </ul>

    <a name="environment"></a>
    <h2>Defining Environment</h2>
    <p>
        To setup environment for Hathoora PHP Framework installation you can use the following options:
    </p>
    <p>
        1. Define <code class="inline">HATHOORA_ENV</code> in Apache using <a href="http://httpd.apache.org/docs/2.2/mod/mod_env.html" target="_blank">SetEnv</a> directive. It can be defined in vhost configuration file as shown below.
    </p>

    <pre>
        <code class="hljs apache">
            &lt;VirtualHost *:80&gt;
                DocumentRoot /some/path/hathoora/docroot
                ServerName mysite.com

                SetEnv HATHOORA_ENV prod

                &lt;Directory /some/path/hathoora/docroot&gt;
                    AllowOverride All

                    # if using apache 2.2, you might also need the following
                    # Order Deny,Allow
                    # Allow From All

                    # if using apache 2.4, you might also need the following
                    # Required All Granted

                &lt;/Directory&gt;
            &lt;/VirtualHost&gt;
        </code>
    </pre>

    <p>
        2. By defining <code class="inline">$env</code> variable in <code class="inline">HATHOORA_ROOTPATH/index.php</code> as shown below.
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
        Environment value is then stored as constant <code class="inline">HATHOORA_ENV</code> that you may use in your code.
    </p>

    <a name="application"></a>
    <h2>Supporting Application(s)</h2>
    <p>
        Hathoora PHP Framrwork support mutiple applications (or websites). Applications are defined in <code class="inline">HATHOORA_ROOTPATH/boot/config/app_HATHOORA_ENV.yml</code>.
    </p>
    <p>
        You can define things like:
    </p>
    <ul>
        <li>Multiple applications (or websites)</li>
        <li>Multiple applications (or websites) for dev/prod/stag/etc environments</li>
        <li>Regex pattern</li>
        <li>Directory for code organization</li>
        <li>Namespacing</li>
        <li>Custom route dispatchers for advanced routing</li>
    </ul>
    <p>
        Listed below are some various possibilites of supporting applications.
    </p>

    <br/>
    <p>
        <a name="applicationsimple"></a>
        <b>Example 1:</b> If you have only one application then you can use something like the following:
    </p>
    <pre>
        <code class="hljs Ini">
            # File: HATHOORA_ROOTPATH/boot/config/app_HATHOORA_ENV.yml

            app:
                mySite:    <-- name of app
                    default: true # will be used as default
        </code>
    </pre>
    <p>
        In this example the source for your application will be located in <code class="inline">HATHOORA_ROOTPATH/app/mySite</code>
    </p>

    <br/>
    <p>
        <a name="applicationmultiple"></a>
        <b>Example 2:</b> For supporting multiple applications like the following:
    </p>
    <ul>
        <li>http://www.website1.com</li>
        <li>http://www.website2.com</li>
    </ul>
    <pre>
        <code class="hljs Ini">
            # File: HATHOORA_ROOTPATH/boot/config/app_HATHOORA_ENV.yml

            app:
                website1:
                    pattern: '^www.website1.com(|/)'
                    default: true
                    directory: myCompany

                website2:
                    pattern: '^www.website2.com(|/)'
                    directory: myCompany
        </code>
    </pre>
    <p>
        In this example URL pattern with be checked as following:
    <p>
    <ul>
        <li>a URL matching pattern <code class="inline">^www.website1.com(|/)</code> will be processed by <code class="inline">website1</code>'s  controller.</li>
        <li>a URL matching pattern <code class="inline">^www.website2.com(|/)</code> will be processed by <code class="inline">website2</code>'s controller.</li>
    </ul>
    <p>
        And soruce of applications would be locacted in:
    </p>
    <ul>
        <li>
            website1 -> <code class="inline">HATHOORA_ROOTPATH/app/myCompany/website1</code>
        </li>
        <li>
            website2 -> <code class="inline">HATHOORA_ROOTPATH/app/myCompany/website2</code>
        </li>
    </ul>

    <br/>
    <p>
        <a name="applicationenvironments"></a>
        <b>Example 3:</b> For loading different configurations for dev & prod environments, like the following.
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
        <a name="applicationorganization"></a>
        <b>Example 4:</b> Code organization - If you wanted to have seperate code (for authentication/organization) between main website and admin panel in a scenario like the following:
    </p>
    <ul>
        <li>http://www.mysite.com</li>
        <li>http://www.mysite.com/admin</li>
        <li>http://api.mysite.com</li>
    </ul>
    <p>
        Then you can use a configuration like this.
    </p>
    <pre>
        <code class="hljs Ini">
            # File: HATHOORA_ROOTPATH/boot/config/app_HATHOORA_ENV.yml

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
                    # define custom namespace that follows psr0 
                    namespace: myApiDirectory/api

                # there is no URL for this one, this is purely for organization of code
                helper:
                    directory: myApiDirectory
        </code>
    </pre>
    <p>
        In this example order matters - first hit. Code for your application will be located as following:
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
    <h2>Application Specific Configuration</h2>
    <p>
        Hathoora PHP Framework supports multiple applications and each application will have its own configuration specific to it needs.
    </p>
    <p>
        These configurations are located at <code class="inline">HATHOORA_ROOTPATH/app/directory/namespace/config/config_HATHOORA_ENV.yml</code> and can be nested.
    </p>
    <p>
        A sample configuration is shown below:
    </p>
    <pre>
        <code class="hljs Ini">
            # File HATHOORA_ROOTPATH/app/directory/namespace/config/config_HATHOORA_ENV.yml

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
            ...
        </code>
    </pre>
    <p>
        Application configuration has basically following main parts:
    </p>
    <ul>
        <li><code class="e">hathoora</code>: to define framework confirguation within scope of application.</li>
        <li><code class="e">services</code>: for defining <a href="/docs/v1/services">services</a> within scope of application.</li>
        <li><code class="e">listeners</code>: for defining <a href="/docs/v1/listeners">listeners</a> within scope of application.</li>
        <li>Define anything else for your application needs.</li>
    </ul>
    <p>
        Learn more about how to access configurations <a href="/docs/v1/container#configuration">here</a>.
    </p>
</div>
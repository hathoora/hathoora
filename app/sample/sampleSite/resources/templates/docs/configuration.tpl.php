<div class="box">
    <h1>Configuration</h1>
    <ul class="outline">
        <li><a href="#environment">Defining Environment</a></li>
        <li><a href="#application">Setting up Application(s)</a></li>
        <li><a href="#configuration">Application Configuration</a></li>
    </ul>

    <a name="environment"></a>
    <h2>Defining Environment</h2>
    <p>
        To setup environment for your hathoora application you can use the following options:
    </p>
    <p>
        1. Define <code class="inline">HATHOORA_ENV</code> in Apache. It can be defined in .htaccess of vhost configuration file.
    </p>

    <pre>
        <code class="hljs apache">
            SetEnv HATHOORA_ENV prod
        </code>
    </pre>

    <p>
        2. By defining <code class="inline">$env</code> variable in <code class="inline">index.php</code>
    </p>
    <pre>
        <code class="hljs php">
            // use prod environment by default
            $env = 'prod';

            if (isset($_SERVER['HATHOORA_ENV']))
                $env = $_SERVER['HATHOORA_ENV'];

            $env = "prod";
            use hathoora\kernel;

            $kernel = new kernel($env);
        </code>
    </pre>


    <a name="application"></a>
    <h2>Setting up Application(s)</h2>
    <p>
        Hathoora Framrwork support mutiple applications (or websites). Applications are defined in <code class="inline">boot/config/app_ENV.yml</code>.
    </p>

    <p>
        <b>Example 1:</b> If you have only one application then you can use something like the following:
    </p>
    <pre>
        <code class="hljs Ini">
            app:
                mySite:
                    default: true # will be used as default
        </code>
    </pre>
    <p>
        In the preceding example, the source for your application will be located in <code class="inline">app/mySite</code>
    </p>

    <p>
        <b>Example 2:</b> For supporting multiple applications:
    </p>
    <ul>
        <li>http://www.website1.com</li>
        <li>http://www.website2.com</li>
    </ul>
    <pre>
        <code class="hljs Ini">
            app:
                website1:
                    pattern: '^www.website1.com(|/)'
                    default: true # will be used as default
                    directory: myCompany

                website2:
                    pattern: '^www.website2.com(|/)'
                    directory: myCompany
        </code>
    </pre>
    <p>
        In the preceding example, the source will be located as following:
    </p>
    <ul>
        <li>
            http://www.website1.com -> <code class="inline">app/myCompany/website1</code>
        </li>
        <li>
            http://www.website2.com -> <code class="inline">app/myCompany/website2</code>
        </li>
    </ul>

    <p>
        <b>Example 3:</b> For supporting prod and dev enviornments:
    </p>
    <ul>
        <li>http://dev.mysite.com</li>
        <li>http://www.mysite.com</li>
    </ul>
    <pre>
        <code class="hljs Ini">
            #File: app_prod.yml
            app:
                mysite:
                    pattern: '^www.mysite.com(|/)'
                    default: true
                    directory: myCompany


            #File: app_dev.yml
            app:
                mysite:
                    pattern: '^dev.mysite.com(|/)'
                    default: true
                    directory: myCompany
        </code>
    </pre>


    <p>
        <b>Example 4:</b> Seperation of code... If you wanted to have seperate code (for authentication/organization) between main website and admin panel.
    </p>
    <ul>
        <li>http://www.mysite.com</li>
        <li>http://www.mysite.com/admin</li>
        <li>http://api.mysite.com</li>
    </ul>
    <pre>
        <code class="hljs Ini">
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
            http://www.mysite.com -> <code class="inline">app/myCompany/site</code>
        </li>
        <li>
            http://www.mysite.com/admin -> <code class="inline">app/myCompany/admin</code>
        </li>
        <li>
            http://api.mysite.com -> <code class="inline">app/myApiDirectory/api</code>
        </li>
        <li>
            NO URL, purely for organization -> <code class="inline">app/myApiDirectory/helper</code>
        </li>
    </ul>

    <a name="configuration"></a>
    <h2>Application Configuration</h2>
    <p>
        Application configuration are located at <code class="inline">app/directory/namespace/config/config_ENV.yml</code> and can be nested.
    </p>
    <p>
        A complete list of configuration is shown below.
    </p>
    <pre>
        <code class="hljs bash">
            # Import config which will be overwritten
            imports:
                - { resource: config_gold.yml }

            # framework configurations..
            hathoora:

                # experimental assets management
                gulaboo:
                    # when enabled adds 'assets' service
                    assets:
                        enabled: 1
                        version: v1

                translation:
                    # when enabled adds 'translation' service
                    enabled: 1
                    # adds debugging info to profiler
                    debug: 0

                logger:

                    # to profile various components of application (database, cache, template etc..)
                    profiling:
                        enabled: 0

                    logging:
                        enabled: 0
                        level: DEBUG

                    webprofiler:
                        # to enable web profiller
                        enabled: 0
                        # when profiler is enabled, it would be displayed only for the following content types
                        content_types: ['text/html']
                        # don't show webprofile on ajax requests
                        skip_on_ajax: 1
                        # skip web profiller for the following POST params
                        skip_on_post_params: []
                        # skip web profiller for the following GET params
                        skip_on_get_params: []

                template:
                    # which template engine to use
                    engine:
                        name: Stuob # other option is Smarty

                    # when using smarty as the engine, using the following configurations
                    Smarty:
                        caching: 0
                        cache_lifetime: 0
                        cache_dir: '/tmp/smarty/mspt/template_cache'
                        compile_dir: '/tmp/smarty/mspt/templates_c'
                        force_compile: 0
                        compile_check: 1


                database:
                    # simple dsn
                    default: mysql://dbuser:dbpassword@dbhost:3306/dbname
                    db2: mysql://dbuser:dbpassword@dbhost:3306/dbname

                    # Advanced configuration:
                    # If you have multiple database servers for read/write then this might be a better options.
                    dbPool1:

                        # Failover logic: When a server becomes unavailable then following logics is applied:
                        #       default logic:  In default logic
                        #           Write: If master server is not reachable for write, then next writeable master (if any) will be used based on weight.
                        #           Read: If slave server is not reachable for reads, the next slave (if any) will be used based on weight.
                        #               1.  If there are no slave servers available then master read only server (if any) will be used based on weight.
                        #               2.  If there is still no read only master server, then next master with allow_read (if any) will be used based on weight
                        #       TODO custom logic: An array containing class and method to call. User can specify which db server to use.
                        #           This gives user the flexibility to pick a db based on  db health, concurrent threads etc..
                        #           Format is [\class, method]
                        failover: default

                        #list of servers in this pool
                        servers:

                            dbMaster1:
                                dsn: mysql://dbuser:dbpassword@dbhost:3306/dbname

                                # role - In advance db setup, there are two types of roles:
                                #   master - used for write (and some occasions for read, keep reading)
                                #   slave - used for read
                                role: master

                                # read_only - (default: false) 'readonly' mode would not allow any writes to specified server.  Read only
                                # servers (masters) are usually passive in nature or hot stand by. In read only mode data is not written to
                                # dsn and any query except for SELECT is ignored and result in empty result set
                                read_only: false

                                # allow_read -  (default: true) To allow reads from master when there is no slave and no read only master db
                                # Note that you cannot use allow_read & rad_only for the master
                                allow_read: true

                                # weight - For the same roles a database with higher weight is picked first.
                                weight: 1

                                # on_connect - Any sql commands to run on connect
                                on_connect:
                                    - SET NAMES utf8;

                            dbMaster2:
                                dsn: mysql://dbuser:dbpassword@dbhost:3306/dbname
                                role: master
                                read_only: true
                                weight: 2
                                on_connect:
                                    - SET NAMES utf8;
                                    - /* Another SQL command */;

                            dbSlave1:
                                dsn: mysql://dbuser:dbpassword@dbhost:3306/dbname
                                role: slave
                                weight: 1


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

                # sample cache service for posts
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
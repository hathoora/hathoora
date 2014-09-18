<div class="box">
    <h1>Translations</h1>
    <ul class="outline">
        <li><a href="#schema">Database Schema</a></li>
        <li><a href="#configuration">Configuration</a></li>
    </ul>

    <p>
        Translations in Hathoora PHP Framework are database driven (and cached) which makes it easy to modify content without any code release. In addition translations can also be used for creating static pages quickly without having to deploy any code.
    </p>
    
    <a name="schema"></a>
    <h2>Database Schema</h2>
    <p>    
        Following is the required database schema for translations.
    </p>
    <pre>
        <code class="hljs sql">    
            CREATE TABLE `translation_key` (
              `translation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `translation_key` varchar(105) NOT NULL,
              `notes` varchar(255) NOT NULL,
              PRIMARY KEY (`translation_id`),
              UNIQUE KEY `item_UNIQUE` (`translation_key`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


            CREATE TABLE `translation_route` (
              `translation_id` int(11) NOT NULL,
              `route` varchar(150) NOT NULL,
              PRIMARY KEY (`translation_id`,`route`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


            CREATE TABLE `translation_value` (
              `translation_id` int(11) NOT NULL,
              `language` varchar(5) NOT NULL,
              `translation` longtext NOT NULL,
              PRIMARY KEY (`translation_id`,`language`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
            # Sample data
            INSERT INTO `translation_value` VALUES (1,'en_US','Hello, {{name}}'),(1,'fr_FR','Bonjour, {{name}}'),
                                                   (2,'en_US','Today is: {{date}}'),(2,'fr_FR','aujourd\'hui: {{date}}'),
                                                   (3,'en_US','Link to <a href=\"{{link}}\">hathoora</a>'),(3,'fr_FR','lien vers <a href=\"{{link}}\">hathoora</a>'),
                                                   <?php echo htmlentities("(4,'en_US','Name is trimmed: \"{{name|trim}}\"<br />\r\n<br />\r\nCustom filter: \"{{name|customFilter(3)|trim}}\"'),(4,'fr_FR','nom est coupé: \"{{name|trim}}\"<br /> <br /> Filtre Personnalisé: \"{{name|customFilter(3)|trim}}\"')"); ?>;
            INSERT INTO `translation_route` VALUES (2,'hathoora_translation_route'),(3,'hathoora_route_example_title');
        </code>
    </pre>

    <a name="configuration"></a>
    <h2>Configuration</h2>
    <p>
        Enable configuration in configuration like so
    </p>
    <pre>
        <code class="hljs Ini">
            # File config.yml

            hathoora:
                translation:
                    enabled: 1
                    cache_service: @cache@
                    cache_time: 86400
                    dsn: default

                    # display empty translations keys, good for debugging
                    show_empty: true # default value
                    languages: [ en_US, fr_FR ]
                    default_language: en_US

                    # for debugging show translations keys, in below configuration passing
                    # tkDebug=1 would show tks for debugging
                    debug:
                        # GET, HEADER
                        method: GET
                        parameter: tkDebug

                # where cache service is defined as
                cache:
                    debug: 1
                    pools:
                        common: { driver: 'memcache', servers: [{host: "localhost", port: 11211}]}
        </code>
    </pre>
    <p>
        <code class="e">hathoora.translation.enabled = 1</code> adds a <code class="inline">translation</code> service.
        We also passed a cache service so we do not hit database for every fetch. We also specified language we are supporting.
    </p>
    <p>
        You can now use translation service like so:
    </p>
    <pre>
        <code class="hljs php">
            // get translation for hathoora_hello_world
            $helloTranslation = $this->getService('translation')->t(
                'hathoora_hello_world', array('name' => 'World')
            );

            // get translations for route
            $routeTranslations = $this->getService('translation')->getRouteTranslations('hathoora_translation_route',
                array(
                    'hathoora_route_example_title' => array(
                        'date' => date('m/d/y H:i:s')
                    ),
                    'hathoora_route_example_body' => array(
                        'link' => 'http://hathoora.org'
                    )
                )
            );
        </code>
    </pre>
    <p>
        To learn more about translations, filters and to see translation in action click <a href="/admin/translation">here</a>.
    </p>
</div>
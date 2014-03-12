<div class="box">
    <h1>Translations</h1>
    <ul class="outline">
        <li><a href="#configuration">Configuration</a></li>
    </ul>

    <p>
        Translations in Hathoora PHP Framework are database driven (and cached) which makes it easy to modify content without any code release. In addition translations can also be used for creating static pages quickly.
    </p>

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
                    languages: [ en_US, fr_FR ]

                # cache configurations
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
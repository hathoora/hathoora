<?php
    $this->start('body');
    $this->load('components/leftPanel.tpl.php');
?>
    <div class="rightPanel docs">
        <div class="box">
            <h1>Translation: Example</h1>
            <ul class="outline">
                <li><a href="#single">Single Translation Key</a></li>
                <li><a href="#route">Route Based Translation Keys</a></li>
                <li><a href="#filters">Filters</a></li>
            </ul>
            <p>
                This page shows an example of how translation can be used.
            </p>

            <a name="single"></a>
            <h2>Single Translation Key</h2>
            <p>
                In this example we are displaying a single translation for key <a href="/admin/translation/edit/1">hathoora_hello_world</a>.
            </p>
            <p>
                In the controller, the code looks like this:
            </p>
            <pre>
                <code class="hljs php">
                    $helloTranslation = $this->getService('translation')->t(
                        'hathoora_hello_world', array('name' => 'World')
                    );
                </code>
            </pre>
            <p>
                In this example we are passing token 'name' and the output is:
            </p>
            <pre>
                <code class="hljs html">
                    <?php echo $helloTranslation; ?>
                </code>
            </pre>

            <a name="route"></a>
            <h2>Route Based Translation Keys</h2>
            <p>
                Above example will make one call to get one translation, if you need to fetch multiple translations you can associate translations to a route and fetch them. This example is using <a href="/admin/translation/edit/2">hathoora_route_example_title</a> and <a href="/admin/translation/edit/3">hathoora_route_example_body</a>. The controller code looks like this:
            </p>
            <pre>
                <code class="hljs php">
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
                In this example we are getting all translations associated with route <code class="e">hathoora_translation_route</code>. We are also passing tokens <code class="e">date</code> and <code class="e">link</code> to keys <a href="/admin/translation/edit/2">hathoora_route_example_title</a> and <a href="/admin/translation/edit/3">hathoora_route_example_body</a> respectively.
            </p>
            <p>
                The output is shown below.
            </p>
            <pre>
                <code class="hljs Ini">
                    hathoora_route_example_title : <?php echo $hathoora_route_example_title; ?>

                    hathoora_route_example_body: <?php echo $hathoora_route_example_body; ?>
                </code>
            </pre>
            <p>
                Now click <a href="/admin/translation/toggle-language">here</a> to toggle language to see the difference.
            </p>

            <a name="filters"></a>
            <h2>Filters</h2>
            <p>
                Using translation inside template and using filters. In this example we are using key <a href="/admin/translation/edit/4">hathoora_hello_world_filter</a>.
            </p>
            <pre>
                <code class="hljs php">
                    echo $this->getService('translation')->t(
                                        'hathoora_hello_world_filter', array('name' => ' Hathoora PHP Framework '));
                </code>
            </pre>
            <p>
                The translation key has the following translation for en_US
            </p>
            <pre>
                <code class="hljs html">
                    Name is trimmed: "{{name|trim}}"&lt;br/&gt;
                    &lt;br/&gt;
                    Custom filter: "{{name|customFilter(3)|trim}}"
                </code>
            </pre>
            <p>
                In this example we are using builtin <code class="e">trim</code> filter and a custom filter called and <code class="e">customFilter</code> which takes one parameter.
            </p>
            <p>
                And we added a custom filter class that contains a <code class="inline">static</code> <code class="e">customFilter</code> like so in config.
            </p>
            <pre>
                <code class="hljs html">
                    # File admin/config/config_prod.yml

                    hathoora:
                        translation:
                            ....

                        # filter used in translation helper
                        detokenizerFilters:
                            - \admin\helper\translationFilter
                </code>
            </pre>
            <p>
                The output of result is shown below.
            </p>
            <pre>
                <code class="hljs html">
                    <?php echo $this->getService('translation')->t(
                            'hathoora_hello_world_filter', array('name' => ' Hathoora PHP Framework '));
                    ?>
                </code>
            </pre>
        </div>
    </div>
    <div class="clearfix"></div>


<?php
    $this->end('body');

    // use the template from sample/docs/resouces/templates/
    $this->extend($this->getRouteRequest()->getAppDirectory('docs') . 'resources/templates/layout.tpl.php');
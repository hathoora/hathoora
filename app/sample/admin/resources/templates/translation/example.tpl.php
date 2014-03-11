<?php
    $this->start('body');
    $this->load('components/leftPanel.tpl.php');
?>
    <div class="rightPanel docs">
        <div class="box">
            <h1>Translations: Example</h1>

            <p>
                This page shows an example of how translation can be used.
            </p>

            <p>
                <b>Example 1:</b>
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
            <p>
                <b>Example 2</b>: Above example will make one call to get one translation, if you need to fetch multiple translations you can associate translations to a route and fetch them. This example is using <a href="/admin/translation/edit/2">hathoora_route_example_title</a> and <a href="/admin/translation/edit/3">hathoora_route_example_body</a>. The controller code looks like this:
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
        </div>
    </div>
    <div class="clearfix"></div>


<?php
    $this->end('body');

    // use the template from sample/docs/resouces/templates/
    $this->extend($this->getRouteRequest()->getAppDirectory('docs') . 'resources/templates/layout.tpl.php');
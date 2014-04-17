<div class="box">
    <h1>View & Templates</h1>
    <ul class="outline">
        <li><a href="#prerequisite">Prerequisite</a></li>
        <li><a href="#basucs">Basics</a></li>
        <li>
            <ul>
                <li><a href="#anywhere">Templatize From Anywhere</a></li>
                <li><a href="#apps">Using Templates From Other Apps</a></li>
            </ul>
        </li>
        <li><a href="#engines">Template Engines</a></li>
        <ul>
            <li><a href="#stuob">Stuob (Default template engine)</a></li>
            <ul>
                <li><a href="#stuob-configuration">Configuration</a></li>
                <li><a href="#stuob-caching">Caching</a></li>
                <li><a href="#stuob-extending">Extending Templates</a></li>
                <li><a href="#stuob-include">Including Templates</a></li>
                <li><a href="#stuob-controller">Rendering A Controller</a></li>
                <li><a href="#stuob-container">Accessing Container</a></li>
            </ul>
            <li><a href="#smarty">Smarty</a></li>
        </ul>
    </ul>

    <a name="prerequisite"></a>
    <h2>Prerequisite</h2>
    <p>
        Prerequisite for this section that were previously demonstrated in <a href="/docs/v1/controller">controller</a> section:
    </p>
    <ul>
        <li><a href="/docs/v1/controller#basics">Using Controller</a></li>
        <li><a href="/docs/v1/controller#tplVars">Global Template Variables</a></li>
    </ul>

    <a name="basics"></a>
    <h2>Basics</h2>
    <p>
        In Hathoora PHP Framework there is no technical difference between a view and template. View is usually a collection of templates. Template is where you would render HTML and this HTML is then injected into response object.
    </p>
    <p>
        Templates are located in <code class="inline">HATHOORA_ROOTPATH/app/appNAME/resources/templates</code> folder.
    </p>
    <p>
        An example of how a template is used from controller is shown below.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/defaultController.php

            namespace site\controller;
            use hathoora\controller\controller;

            /**
             * Default controller
             */
            class defaultController extends controller
            {
                /**
                 * Homepage action
                 */
                public function index()
                {
                    // assign variables to template
                    $arrTplVars = array(
                        'title' => 'My Website',
                        'date' => date('m/d/y')
                    );

                    $template = $this->template('index.tpl.php', $arrTplVars);
                    $response = $this->response($template);

                    return $response;
                }
            }
        </code>
    </pre>
    <p>
        In this example <code class="inline">HATHOORA_ROOTPATH/app/appNAME/resources/templates/index.tpl.php</code> will be used which may look like the following.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/resources/templates/index.tpl.php
            # This template is using Stuob template which is plain ole' PHP

            &lt;html&gt;
                &lt;title&gt;&lt;?php echo $title ;?&gt;&lt;/title&gt;
            &lt;/html&gt;
            &lt;body&gt;
                &lt;!-- and using PHP's shorthand notation, inestead of echo, you can use &lt;?= $var; ?&gt; --&gt;
                Today's date: &lt;?= $date; ?&gt;
            &lt;/body&gt;
            &lt;/html&gt;
        </code>
    </pre>
    <p>
        In above example <code class="e">$title</code> and <code class="e">$date</code> were dynamic variables assigned via controller.
    </p>

    <a name="anywhere"></a>
    <h2>Templatize From Anywhere</h2>
    <p>
        Templates can be used from anywhere and are not restricted to controller. Suppose if a HTML table needs to be rendered by a logical function, then you can do something like the following:
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/logic/myLogic.php
            namespace appNAME\logic;
            use hathoora\model\model;

            class myLogic extends model
            {
                /**
                 * This function returns
                 */
                public static function getTableHTML()
                {
                    ...

                    // full path of current app (which is appNAME)
                    $route = $this->getRouteRequest();
                    $appPath = $route->getAppDirectory() . '/resources/templates/';
                    $templatePath = $appPath . 'components/table.tpl.php';

                    $db = $this->getDBConnection();
                    $arrData = $db->fetchArrayAll('....')

                    $arrTplVars = array(
                        'arrData' => $arrData
                    );

                    $template = new \hathoora\template\template($templatePath, $arrTplVars);

                    return $template->fetch();
                }
            }


            # File HATHOORA_ROOTPATH/app/appNAME/resources/templates/components/table.tpl.php
            &lt;table&gt;
                &lt;tr&gt;
                    &lt;th&gt;Date&lt;/th&gt;
                    &lt;th&gt;Message&lt;/th&gt;
                &lt;/tr&gt;
                &lt;?php
                    foreach ($arrData as $_arr)
                    {
                        echo '
                            &lt;tr&gt;
                                &lt;td&gt;'. $_arr['date'] .'&lt;/td&gt;
                                &lt;td&gt;'. $_arr['message'] .'&lt;/td&gt;
                            &lt;/tr&gt;';
                    }
                ?&gt;
            &lt;/table&gt;
        </code>
    </pre>

    <a name="apps"></a>
    <h2>Using Templates From Other Apps</h2>
    <p>
        If you have <a href="/docs/v1/configuation#applicationmultiple">multiple applications</a> then you can use templates from one application into another. Consider the following example:
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/defaultController.php

            namespace site\controller;
            use hathoora\controller\controller;

            /**
             * Default controller
             */
            class defaultController extends controller
            {
                /**
                 * Homepage action
                 */
                public function index()
                {
                    // assign variables to template
                    $arrTplVars = array(
                        'title' => 'My Website',
                        'date' => date('m/d/y')
                    );

                    // full path of other app
                    $route = $this->getRouteRequest();
                    $appPath = $route->getAppDirectory('otherAPPName') . '/resources/templates/';
                    $templatePath = $appPath . 'layout.tpl.php';

                    $template = $this->template($templatePath, $arrTplVars);
                    $response = $this->response($template);

                    return $response;
                }
            }
        </code>
    </pre>
    <p>
        In this example we are using <a href="/docs/v1/container">container</a>'s <code class="inline">$this->getRouteRequest()->getAppDirectory()</code> method to get absoulte path of other app. But you can also hard code path (if needed).
    </p>

    <a name="engines"></a>
    <h2>Template Engines</h2>
    <p>
        You have the ability to choose which template engine to use. The default enging is <a href="#Stuob">Stuob</a>. Template engine is defined like so.
    </p>
    <pre>
        <code class="hljs Ini">
            # File HATHOORA_ROOTPATH/app/appNAME/config/config_HATHOORA_ENV.yml

            # framework configurations..
            hathoora:
                ...
                template:
                    engine:
                        name: Stuob
                ...
        </code>
    </pre>


    <a name="#stuob"></a>
    <h2>Stuob (Default template engine)</h2>
    <p>
        Stuob is the default template engine which is plain PHP inside templates.
    </p>
    <br/>
    <p>
        <a name="stuob-configuration"></a>
        <b>Configuration</b><br/>
        Following demonstrate how to configure Stuob per application.
    </p>
    <pre>
        <code class="hljs Ini">
            # File HATHOORA_ROOTPATH/app/appNAME/config/config_HATHOORA_ENV.yml

            # framework configurations..
            hathoora:
                ...
                template:
                    engine:
                        name: Stuob
                    Stuob:
                        caching: 0|1
                        cache_dir:  writeable absoulte path to where cache content is stored
                        cache_lifetime: INT in seconds, how long to cache for
                ...
        </code>
    </pre>

    <p>
        <a name="stuob-caching"></a>
        <b>Caching</b><br/>
        In addition to above configurations, you can also define Stuob caching parameters as shown below in this cache example.
    </p>
    <pre>
        <code class="hljs php">
            $arrTplVars = array(
                'name' => 'Hathoora'
            );
            $template = \hathoora\template\template('cacheTemplate.tpl.php', $arrTplVars);
            $template->setCacheDir('/tmp/cache');
            $template->setCacheId(8171);
            $template->setCacheTime(60);
            $template->setCaching(true);

            if ($template->isCached())
            {
                //echo "File is cached ";
            }
            else
            {
                // do heavy duty stuff
                $data = ...

                $template->assign('moreVars', $data);
            }

            $html = $template->fetch();
        </code>
    </pre>

    <p>
        <a name="stuob-extending"></a>
        <b>Extending Templates</b><br/>
        Templates can be extended using <code class="inline">block()</code>, <code class="inline">start()</code>, <code class="inline">end()</code> and <code class="inline">extend()</code> functions as demonstrated in the following example.
    </p>
    <pre>
        <code class="hljs html">
            # File layout.tpl.php

            &lt;!doctype html&gt;
            &lt;html&gt;
            &lt;head&gt;
                &lt;meta charset=&quot;utf-8&quot;&gt;
                &lt;title&gt;
                    &lt;?php
                        $this-&gt;block('metaTitle');
                    ?&gt;
                &lt;/title&gt;
            &lt;/head&gt;
            &lt;body class=&quot; &quot;&gt;
                &lt;div id=&quot;container&quot;&gt;
                    &lt;div id=&quot;body&quot;&gt;
                        &lt;?php
                            $this-&gt;block('body');
                        ?&gt;
                    &lt;/div&gt;
                    &lt;div id=&quot;footer&quot;&gt; &lt;/div&gt;
                 &lt;/div&gt;
            &lt;/body&gt;
            &lt;/html&gt;


            # File children.tpl.php

            &lt;?php
            $this-&gt;start('metaTitle');
                echo 'Website Title';
            $this-&gt;end('body');

            $this-&gt;start('body');
                echo '&lt;div&gt;Body&lt;/div&gt;';
            $this-&gt;end('body');

            $this-&gt;extend('layout.tpl.php');
        </code>
    </pre>

    <p>
        Above would produce the following:
    </p>
    <pre>
        <code class="hljs html">
            &lt;!doctype html&gt;
            &lt;html&gt;
            &lt;head&gt;
                &lt;meta charset=&quot;utf-8&quot;&gt;
                &lt;title&gt;
                    Website Title
                &lt;/title&gt;
            &lt;/head&gt;
            &lt;body class=&quot; &quot;&gt;
                &lt;div id=&quot;container&quot;&gt;
                    &lt;div id=&quot;body&quot;&gt;
                        &lt;div&gt;Body&lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div id=&quot;footer&quot;&gt; &lt;/div&gt;
                 &lt;/div&gt;
            &lt;/body&gt;
            &lt;/html&gt;
        </code>
    </pre>


    <p>
        <a name="stuob-include"></a>
        <b>Including Templates</b><br/>
        Use <code class="inline">load($file, $tplVars)</code> to include a template into another.
    </p>
    <br/>

    <p>
        <a name="stuob-controller"></a>
        <b>Rendering A Controller</b><br/>
        To render a controller from template using <code class="inline">render($arrController, $args)</code> as demonstrated in the following example.
    </p>
    <pre>
        <code class="hljs html">
            # File layout.tpl.php

            &lt;!doctype html&gt;
            &lt;html&gt;
            &lt;head&gt;
                &lt;meta charset=&quot;utf-8&quot;&gt;
                &lt;title&gt;
                    &lt;?php
                        $this-&gt;block('metaTitle');
                    ?&gt;
                &lt;/title&gt;
            &lt;/head&gt;
            &lt;body class=&quot; &quot;&gt;
                &lt;div id=&quot;userBar&quot;&gt;
                    &lt;?php $this->render(array(
                                                    '\myNameSpace\controller\structureController', // class name
                                                    'topBar'    // action (method) name
                                                ),
                                                array('param2', 'param2') // arguments to pass to method
                                            );
                    ?&gt;
                &lt;/div&gt;
                &lt;div id=&quot;container&quot;&gt;
                    &lt;div id=&quot;body&quot;&gt;
                        &lt;?php
                            $this-&gt;block('body');
                        ?&gt;
                    &lt;/div&gt;
                    &lt;div id=&quot;footer&quot;&gt; &lt;/div&gt;
                 &lt;/div&gt;
            &lt;/body&gt;
            &lt;/html&gt;
        </code>
    </pre>

    <p>
        <a name="stuob-container"></a>
        <b>Accessing Container</b><br/>
        By default you have access to <a href="/docs/v1/container">container</a> within the scope of template. Consider the following demonstration.
    </p>
    <pre>
        <code class="hljs php">
            # File layout.tpl.php

            ...

            // check for GET param
            $q = $this->getRequest()->getParam('q');

            // get current application path
            $this->getRouteRequest()->getAppDirectory();

            // check for configuration
            $this->getConfig('myconfigName');

            // setting a config
            $this->setConfig('myconfigName', 'value1');

            // Hathoora PHP Framework version
            $this->getKernel()->getKernel();

            ...
        </code>
    </pre>

    <a name="smarty"></a>
    <h2>Smarty</h2>
    <p>
        To be documented...
    </p>

</div>
<div class="box">
    <h1>View & Templates</h1>
    <ul class="outline">
        <li><a href="#prerequisite">Prerequisite</a></li>
        <li><a href="#basucs">Basics</a></li>
        <li>
            <ul>
                <li><a href="#anywhere">Templatize From Anywhere</a></li>
            </ul>
        </li>
        <li><a href="#engines">Template Engines</a></li>
        <ul>
            <li><a href="#stuob">Stuob (Default template engine)</a></li>
            <li><a href="#smarty">Smarty</a></li>
            <li><a href="#custom">Roll Your Own</a></li>
        </ul>
    </ul>

    <a name="prerequisite"></a>
    <h2>Prerequisite</h2>
    <p>
        Prerequisite for this section that were previously demonstrated in <a href="/sample/docs/view/controller">controller</a> section:
    </p>
    <ul>
        <li><a href="/sample/docs/view/controller#basics">Using Controller</a></li>
        <li><a href="/sample/docs/view/controller#tplVars">Global Template Variables</a></li>
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
        A sample example of how a template is used from controller is shown below.
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
        In above example <code class="inline">HATHOORA_ROOTPATH/app/appNAME/resources/templates/index.tpl.php</code> will be used which may look like the following.
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
        In above example <code class="inline">$title</code> and <code class="inline">$date</code> were dynamic variables assigned via controller.
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
</div>
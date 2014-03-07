<div class="box">
    <h1>Controllers</h1>
    <ul class="outline">
        <li><a href="#prerequisite">Prerequisite</a></li>
        <li><a href="#basics">Basics</a></li>
        <li>
            <ul>
                <li><a href="#barebone">Barebone Controller</a></li>
                <li><a href="#tplVars">Global Template Variables</a></li>
                <li><a href="#base">Base Controllers</a></li>
                <li><a href="#globals">Accessing Globals (GET, POST, SERVER, SESSION, COOKIE etc.)</a></li>
                <li><a href="#container">Accessing Container</a></li>
                <li><a href="#customHeaders">Sending Custom Headers In Response</a></li>
                <li><a href="#redirects">Redirects and Flash Messages</a></li>
            </ul>
        </li>
        <li><a href="#restful">RESTful Controller</a></li>
    </ul>

    <a name="prerequisite"></a>
    <h2>Prerequisite</h2>
    <p>
        Prerequisite for this section that were previously discussed in <a href="/docs/v1/routing">routing & pretty URLs</a> section:
    </p>
    <ul>
        <li><a href="/docs/v1/routing#defaultController">Default Controller</a></li>
        <li><a href="/docs/v1/routing#defaultAction">Default Action</a></li>
        <li><a href="/docs/v1/routing#camelcase">Camel Case Action Names</a></li>
        <li><a href="/docs/v1/routing#uri">Note About URI</a></li>
    </ul>

    <a name="basics"></a>
    <h2>Basics</h2>
    <p>
        Controller is a PHP class located in <code class="e">controller</code> folder of an application and is suffixed with "Controller" e.g. <code class="inline">blogController</code>.
    </p>
    <p>
        A controller takes a request, does something on it and returns a response object <code class="inline">hathoora/http/response</code>
    </p>
    <p>
        Sample controller code is shown below.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/defaultController.php

            namespace appNAME\controller;
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
                        'time' => time()
                    );

                    $template = $this->template('index.tpl.php', $arrTplVars);
                    $response = $this->response($template);

                    return $response;
                }
            }
        </code>
    </pre>
    <p>
        In this example we are extending <code class="inline">hathoora\controller\controller</code> and by doing so our controller gets access to <a href="/docs/v1/container">container</a>.
    </p>

    <a name="barebone"></a>
    <h2>Barebone Controller</h2>
    <p>
        <a href="basics">Above example</a> can also be coded as following <b>without extending</b> <code class="inline">hathoora\controller\controller</code>.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/defaultController.php

            namespace appNAME\controller;

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
                        'time' => time()
                    );

                    $template = new \hathoora\template\template('index.tpl.php', $arrTplVars);
                    $response = new \hathoora\http\response($template);

                    return $response;
                }
            }
        </code>
    </pre>


    <a name="tplVars"></a>
    <h2>Global Template Variables</h2>
    <p>
        Often time variable needs to be assigned globally throughout included templates. This is achieved by using <code class="inline">setTplVarsByRef</code> and <code class="inline">setTplVars</code> as shown below.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/blogController.php

            namespace appNAME\controller;
            use hathoora\controller\controller;

            /**
             * Blog controller
             */
            class blogController extends controller
            {
                public function index()
                {
                    // assigning global template variables
                    $this->setTplVarsByRef('user', $this->user);
                    $this->setTplVars('userFlag', 1);

                    $arrTplVars = array(
                        'time' => time()
                    );

                    /**
                     * index.tpl would would have following three variables available in this example
                     *  - user
                     *  - userFlag
                     *  - time
                     */
                    $template = $this->template('index.tpl.php', $arrTplVars);

                    $response = $this->response($template);

                    return $response;
                }
            }
        </code>
    </pre>

    <a name="base"></a>
    <h2>Base Controllers</h2>
    <p>
        The above example of defining global template variables can also be achived by using a <code class="e">base controller</code>, where the name 'base' can by anything.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/baseController.php

            namespace appNAME\controller;
            use hathoora\controller\controller;

            /**
             * base controller
             */
            class baseController extends controller
            {
                public function __construct()
                {
                    $this->user = $this->getUser();

                    // assigning global template variables
                    $this->setTplVarsByRef('user', $this->user);
                    $this->setTplVars('userFlag', 1);
                }
            }



            # File HATHOORA_ROOTPATH/app/appNAME/controller/blogController.php

            namespace appNAME\controller;

            /**
             * Blog controller
             */
            class blogController extends baseController
            {
                public function __construct()
                {
                    parent::__construct();
                }

                public function index()
                {
                    $arrTplVars = array(
                        'time' => time()
                    );

                    /**
                     * index.tpl would would still have following three variables available in this example
                     *  - user
                     *  - userFlag
                     *  - time
                     */
                    $template = $this->template('index.tpl.php', $arrTplVars);

                    $response = $this->response($template);

                    return $response;
                }
            }
        </code>
    </pre>

    <a name="globals"></a>
    <h2>Accessing Globals (GET, POST, SERVER, SESSION, COOKIE etc.)</h2>
    <p>
        These can be obtained from <code class="inline">hathoora/http/request::make()</code> object. If your controller is extending <code class="inline">hathoora\controller\controller</code> then you can also use the following built-in functionality.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/blogController.php

            namespace appNAME\controller;
            use hathoora\controller\controller;

            /**
             * blog controller
             */
            class blogController extends controller
            {
                public function list()
                {
                    $request = $this->getRequest();

                    // equvilant to $_GET['q'] with ability to default to a value
                    $q = $request->getParam('q', 'default value');

                    // equvilant to $_POST['q'] with ability to default to a value
                    $q = $request->postParam('q', 'default value');

                    // equvilant to $_SERVER['HTTP_HOST']
                    $x = $request->serverParam('http_host');

                    // equvilant to returning the value of $_SESSION['param']
                    $v = $request->sessionParam('param');

                    // equvilant to $_SESSION['param'] = 'value'
                    $request->sessionParam('param', 'value');

                    // get cookie value
                    $cookie = $request->cookieParam('param');

                    // store cookie
                    $request->cookieParam('param', 'value', 300, $path = '/', $domain = null);

                    ...
                }
            }
        </code>
    </pre>
    <p>
        Click <a href="https://github.com/Pakeela/hathoora-core/blob/master/src/hathoora/http/request.php" target="_blank">here</a> to view complete list of functions avaialble in request object.
    </p>

    <a name="container"></a>
    <h2>Accessing Container</h2>
        If your contoller is extending <code class="inline">hathoora/container/*</code> then you have access to <a href="/docs/v1/container">container</a> within the scope of controller.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/blogController.php

            namespace appNAME\controller;
            use hathoora\controller\controller;

            /**
             * blog controller
             */
            class blogController extends controller
            {
                public function list()
                {
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
                }
            }
        </code>
    </pre>
    <p>
        If you are using <a href="#barebone">barebone controller</a>, the you can access container by doing something like the following:
    </p>
  <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/blogController.php

            namespace appNAME\controller;
            use hathoora\container;

            /**
             * blog controller
             */
            class blogController
            {
                public function list()
                {
                    // check for GET param
                    $q = container::getRequest()->getParam('q');

                    // get current application path
                    container::getRouteRequest()->getAppDirectory();

                    // check for configuration
                    container::getConfig('myconfigName');

                    // setting a config
                    container::setConfig('myconfigName', 'value1');

                    // Hathoora PHP Framework version
                    container::getKernel()->getKernel();

                    ...
                }
            }
        </code>
    </pre>

    <a name="customHeaders"></a>
    <h2>Sending Custom Headers In Response</h2>
    <p>
        Following example demonstrates sending custom headers in response.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/blogController.php

            namespace appNAME\controller;
            use hathoora\controller\controller;

            /**
             * blog controller
             */
            class blogController extends controller
            {
                public function list()
                {
                    $request = $this->getRequest();

                    // get HTML from a template
                    $html = $this->template('index.tpl.php');
                    // or it can be as simple as the following
                    $html = '<b>Hello World</b>';

                    // send following headers back to the client
                    $arrHeaders = array(
                        'Access-Control-Allow-Method' => 'GET, POST',
                        'Header1' => 'Value1'
                    );

                    // send HTTP status code, the default is 200
                    $httpStatusCode = 200;
                    $response = $this->response($html, $arrHeaders, $httpStatusCode);

                    return $response;
                }
            }
        </code>
    </pre>

    <a name="redirects"></a>
    <h2>Redirects and Flash Messages</h2>
    <p>
        A flash message is used in order to keep a message in session through one or several requests of the same user. By default, it is removed from session after it has been displayed to the user. Flash messages are usually used in combination with HTTP redirections, because in this case there is no view, so messages can only be displayed in the request that follows redirection.
    </p>
    <p>
        Following example demonstrates setting flash message and redirects.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/blogController.php

            namespace appNAME\controller;
            use hathoora\controller\controller;

            /**
             * blog controller
             */
            class blogController extends controller
            {
                public function list()
                {
                    ...

                    $response = $this->response();

                    if (!$user)
                    {
                        // other types can be error, warning, anything..
                        $response->setFlash('Please Login to continue.', 'info');

                        $url = '/';
                        $response->redirect($url, 301);
                    }
                    else
                    {
                        $template = $this->template('index.tpl.php');
                        $response->setContent($template);
                    }

                    return $response;
                }
            }
        </code>
    </pre>
    <p>
        Flash messages are stored in session under the name <code class="e">httpFlash</code>. They can then be rendered inside template by doing something like the following
    </p>
    <pre>
        <code class="hljs php">
            # File - Stuob template

            $arrMessages = $this->getFlashMessage();
            $arrCheckThese = array(
                'info', 'error', 'warning', 'success'
            );
            foreach ($arrCheckThese as $type)
            {
                if (isset($$type))
                    $arrMessages[$type] = $$type;
            }

            if (is_array($arrMessages))
            {
                foreach ($arrMessages as $type => $arrMessage)
                {
                    echo '
                    &lt;div class="flash '. $type .'"&gt;';

                        if (is_array($arrMessage) || is_object($arrMessage))
                        {
                            echo '&lt;p&gt;Sorry an error occured while trying to process your request.&lt;/p&gt;';
                            echo '&lt;ul&gt;';
                            foreach ($arrMessage as $k => $v)
                            {
                                echo '&lt;li class="'. $k .'"&gt;' . $v . '&lt;/li&gt;';
                            }
                            echo '&lt;/ul&gt;';
                        }
                        else
                            echo '&lt;p&gt;'. $arrMessage .'&lt;/p&gt;';

                    echo '
                    &lt;/div&gt;';
                }
            }
        </code>
    </pre>

     <a name="restful"></a>
    <h2>RESTful Controller</h2>
    <p>
        RESTful controllers are achieved by extending <code class="inline">hathoora\controller\CRUD</code> and using the following convention (for a chat room endpoint).
    </p>
    <ul>
        <li>Getting list of records -> <code class="e">GET /room</code> -> <code class="inline">collection()</code></li>
        <li>Getting record info -> <code class="e">GET /room/12</code> -> <code class="inline">read($id)</code></li>
        <li>Creating new record -> <code class="e">POST /room</code> -> <code class="inline">create()</code></li>
        <li>Updating existing record -> <code class="e">UPDATE /room/12</code> -> <code class="inline">update($id)</code></li>
    </ul>
    <p>
        Consider a sample class which demonstrate this.
    </p>
    <pre>
        <code class="hljs php">
            # File HATHOORA_ROOTPATH/app/appNAME/controller/blogController.php

            namespace appNAME\controller;
            use hathoora\controller\CRUD;

            /**
             * room api controller
             */
            class roomController extends CRUD
            {
                /**
                 * URL: GET /room to return list of all rooms
                 */
                public function collection()
                {
                    //
                }

                /**
                 * GET /room/12 - Retrieves a specific room
                 */
                public function read($id = null)
                {
                    //
                }

                /**
                 * POST /room - Creates a new room
                 */
                public function create()
                {
                    //
                }

                /**
                 * PUT /room/12 - Updates room #12
                 */
                public function update($id)
                {
                    //
                }

                /**
                 * DELETE /room/12 - Deletes ticket #12
                 */
                public function delete($id)
                {
                    //
                }
            }
        </code>
    </pre>
    <p>
        <code class="inline">hathoora\controller\CRUD</code> doesn't have any implementation for authentication or sending appropriates headers, you would have to create one that meet your needs.
    </p>
</div>
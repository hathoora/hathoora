<div class="box">
    <h1>Routing & URLs</h1>
    <ul class="outline">
        <li>
            <a href="#defaultRouting">Default Routing</a>
        </li>
        <li>
            <ul>
                <li><a href="#defaultController">Default Controller</a></li>
                <li><a href="#defaultAction">Default Action</a></li>
                <li><a href="#camelcase">Camel Case Action Names</a></li>
                <li><a href="#uri">Few Words About URI</a></li>
            </ul>
        </li>
        <li><a href="#advanced">Advanced Routing</a></li>
    </ul>

    <a name="defaultRouting"></a>
    <h2>Default Routing</h2>
    <p>
        Default routing is based on typical MVC structure in which URI corresponds to class/method.
    </p>
    <pre>
        <code class="hljs bash">
            http://example.com/blog/list/param1/param2
        </code>
    </pre>
    <p>
        In above example the request will be routed to <code class="inline">public</code> <b>list</b> method of <b>blogController</b> with <b>param1 & param2</b> as method arguments. A sample controller would look something like the following:
    </p>
    <pre>
        <code class="hljs php">
            namespace sampleSite\controller
            {
                use hathoora\controller\controller;

                /**
                 * Blog controller
                 */
                class blogController extends controller
                {
                    /**
                     * List action
                     */
                    public function list($param1, $param2, $param3 = null)
                    {
                        //
                    }
                }
            }
        </code>
    </pre>


    <a name="defaultController"></a>
    <h2>Default Controller</h2>
    <p>
        Index page or homepage of an application is routed to defaultController.
    </p>
    <pre>
        <code class="hljs bash">
            http://example.com/
        </code>
    </pre>
    <p>
        In above example the request will be routed to <code class="inline">public</code> <b>index</b> method of <b>defaultController</b>. A sample controller would look something like the following:
    </p>
    <pre>
        <code class="hljs php">
            namespace sampleSite\controller
            {
                use hathoora\controller\controller;

                /**
                 * Homepage controller
                 */
                class defaultController extends controller
                {
                    /**
                     * homepage action
                     */
                    public function index()
                    {
                        //
                    }
                }
            }
        </code>
    </pre>

    <a name="defaultIndex"></a>
    <h2>Default Action</h2>
    <p>
        If you don't specity an action for a controller, then <code class="inline">public</code> <b>index</b> method of that controller is called.
    </p>
    <pre>
        <code class="hljs bash">
            http://example.com/blog/
        </code>
    </pre>
    <p>
        In above example the request will be routed to <code class="inline">public</code> <b>index</b> method of <b>blogController</b>.
    </p>
    <pre>
        <code class="hljs php">
            namespace sampleSite\controller
            {
                use hathoora\controller\controller;

                /**
                 * Blog controller
                 */
                class blogController extends controller
                {
                    /**
                     * home action of blog
                     */
                    public function index()
                    {
                        //
                    }
                }
            }
        </code>
    </pre>


    <a name="camelcase"></a>
    <h2>Camel Case Action Names</h2>
    <p>
        Camel case method names are seperated by a dash (-). Consider the following URLs which are both pointing to the same action.
    </p>
    <pre>
        <code class="hljs bash">
            http://example.com/blog/orderBy/name
            http://example.com/blog/order-by/name
        </code>
    </pre>
    <p>
        In above example both requests will be routed to <code class="inline">public</code> <b>orderBy</b> method of <b>blogController</b> with <b>name</b> as method argument.
    </p>
    <pre>
        <code class="hljs php">
            namespace sampleSite\controller
            {
                use hathoora\controller\controller;

                /**
                 * Blog controller
                 */
                class blogController extends controller
                {
                    /**
                     * home action of blog
                     */
                    public function orderBy($type = 'name')
                    {
                        //
                    }
                }
            }
        </code>
    </pre>


    <a name="uri"></a>
    <h2>Few Words About URI</h2>
    <p>
        In above examples routing was based on URI, the part after the domain.
    </p>
    <p>
        However Hathoora PHP Frameworks allows you to change the domain identifier which would have an impact on the meaning of URI.</p>
    <p>
        To iterate over this point consider the following <code class="inline">HATHOORA_ROOTPATH/boot/config/app.yml</code> configuration.
    </p>
    <pre>
        <code class="hljs Ini">
            # File HATHOORA_ROOTPATH/boot/config/app.yml

            app:
                admin:
                    pattern: '^www.example.com/panel/admin(|/)'

                site:
                    pattern: '^www.example.com(|/)'
                    default: true # will be used as default
        </code>
    </pre>
    <p>
        Now consider the following URLs to understand the concept and the controller to which they would be routed to:
    </p>
    <ul>
        <li>http://www.example.com/blog/list will be handled by <code class="inline">app/site/controller/blogController::list</code>
        <li>http://www.example.com/panel/admin/list will be handled by <code class="inline">app/admin/controller/listController::index</code>
    </ul>



    <a name="advanced"></a>
    <h2>Advanced Routing</h2>
    <p>
        If default routing is not what you are looking for, then consider custom route dispatchers. Custom router dispatchers are defined per application souce in <code class="inline">HATHOORA_ROOTPATH/boot/config/app.yml</code> configuration.
    </p>
    <pre>
        <code class="hljs Ini">
            # File HATHOORA_ROOTPATH/boot/config/app.yml

            app:
                admin:
                    pattern: '^www.example.com/panel/admin(|/)'
                    # custom routing to be handled by this class
                    dispatcher:
                        class: appDispatcher
                        method: dispatch

                site:
                    pattern: '^www.example.com(|/)'
                    default: true # will be used as default
        </code>
    </pre>
    <p>
        In the above example requests for admin would be sent to <code class="inline">app/admin/appDispatcher::dispatch</code> which must return an array containing:
    </p>
    <ul>
        <li>controller</li>
        <li>action</li>
        <li>params</li>
    </ul>
    <p>
        To see advanced routing in action, consider the following sample code:
    </p>
    <pre>
        <code class="hljs php">
            # File app/admin/appDispatcher

            namespace admin
            {
                /**
                 * Custom request dispatcher
                 */
                class appDispatcher
                {
                    /**
                     * Custom dispatcher for route request
                     *
                     * This function returns array containing:
                     *      - controller class name
                     *      - action name
                     *      - array of params
                     */
                    public function dispatch(\hathoora\container $container)
                    {
                        $arrDispatch = null;

                        $request = $container->getRequest();
                        $routeRequest = $container->getRouteRequest();
                        $uri = $request->serverParam('REQUEST_URI');

                        // URL: /panel/admin/list/12
                        if (preg_match('~admin/list/(\d+)~i', $uri, $arrMatches)
                        {
                            $arrDispatch = array(
                                'controller' => 'listController',
                                'action' => 'view',
                                'params' => array(array_pop($arrMatches));
                        }
                        else
                        {
                            // following is the same as default routing
                            $arrUriParts = explode('/', $uri);
                            array_shift($arrUriParts);
                            $firstPart = array_shift($arrUriParts);

                            $arrDispatch = array(
                                'controller' => 'questionsController',
                                'action' => $firstPart,
                                'params' => $arrUriParts);
                        }

                        return $arrDispatch;
                    }
                }
            }
        </code>
    </pre>
</div>
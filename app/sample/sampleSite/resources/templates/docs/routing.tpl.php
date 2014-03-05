<div class="box">
    <h1>Routing & Pretty URLs</h1>
    <ul class="outline">
        <li>
            <a href="#defaultRouting">Default Routing</a>
        </li>
        <li>
            <ul>
                <li><a href="#defaultController">Default Controller</a></li>
                <li><a href="#defaultAction">Default Action</a></li>
                <li><a href="#camelcase">Camel Case Action Names</a></li>
                <li><a href="#uri">Note About URI</a></li>
            </ul>
        </li>
        <li><a href="#advanced">Advanced Routing</a></li>
        <ul>
            <li><a href="#dispatcher">Custom Dispatcher</a></li>
            <li><a href="#httpd">Apache (or other web servers) Rewrites</a></li>
        </ul>
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
        In this example the request will be routed to <code class="e">public</code> <code class="inline">list</code> method of <code class="inline">blogController</code> with <b>param1 & param2</b> as method arguments. A sample controller would look something like this.
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
        <code class="e">Index</code> page or homepage of an application is routed to <code class="inline">defaultController</code>.
    </p>
    <pre>
        <code class="hljs bash">
            http://example.com/
        </code>
    </pre>
    <p>
        In this example the request will be routed to <code class="e">public</code> <code class="inline">index</code> method of <code class="inline">defaultController</code>. A sample controller would look something like the following.
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
        If you don't specity an action for a controller, then <code class="e">public</code> <code class="inline">index</code> method of that controller is called.
    </p>
    <pre>
        <code class="hljs bash">
            http://example.com/blog/
        </code>
    </pre>
    <p>
        In this example the request will be routed to <code class="e">public</code> <code class="inline">index</code> method of <code class="inline">blogController</code> as shown below.
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
        In this example both requests will be routed to <code class="e">public</code> <code class="inline">orderBy</code> method of <code class="inline">blogController</code> with <b>name</b> as method argument. The code would look like this.
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
    <h2>Note About URI</h2>
    <p>
        In above examples routing was based on URI, the part after the domain.
    </p>
    <p>
        However Hathoora PHP Frameworks allows you to change the domain identifier (using <a class="e" href="/sample/docs/view/configuration#applicationmultiple">regex patterns</a>) which would change the meaning of URI.</p>
    <p>
        To iterate over this point consider the following application specific configuration.
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
        <li><code class="e">http://www.example.com/blog/list</code> will be handled by <code class="inline">app/site/controller/blogController::list</code>
        <li><code class="e">http://www.example.com/panel/admin/list</code> will be handled by <code class="inline">app/admin/controller/listController::index</code>
    </ul>



    <h2>Advanced Routing</h2>
    <p>
        If default routing is not what you are looking for, then you have two more options for complex routing.
    </p>

    <a name="dispatcher"></a>
    <h2>Custom Dispatcher</h2>
    <p>
        Custom dispatcher is a PHP class defined per application configuration. To enable it, you need to define <code class="inline">dispatcher</code> parameter in configuration as shown below.
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
        In this example requests for <code class="e">site</code> would be handled normally, however requests for admin would be sent to <code class="inline">app/admin/appDispatcher::dispatch</code> which must return an array containing:
    </p>
    <ul>
        <li><code class="e">controller</code> e.g. listController</li>
        <li><code class="e">action</code> e.g. view</li>
        <li><code class="e">array of params</code> e.g. ['title', 'id']</li>
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


    <a name="httpd"></a>
    <h2>Apache (or other web servers) Rewrites</h2>
    <p>
        You can also use Apache rewrite for routing URLs. You would need to set the following params in Apache:
    </p>
    <ul>
        <li><code class="e">REDIRECT_HTRO</code></li>
        <li><code class="e">REDIRECT_HTRO_CONTROLLER</code></li>
        <li><code class="e">REDIRECT_HTRO_ACTION</code></li>
        <li><code class="e">REDIRECT_HTRO_PARAMS</code></li>
    </ul>

    <p>
        The following example routes a URL <code class="e">http://mysite.com/posts/1-hello-world</code> to <code class="inline">postsController::view($id, $slug)</code>.
    </p>
    <pre>
        <code class="hljs php">
            # File: docroot/.htaccess

            RewriteEngine On

            # Fancy URL for viewing a post on domains
            RewriteCond %{HTTP_HOST} ^mysite.com [NC]
            RewriteCond %{REQUEST_URI} ((.+?)|)/posts/(\d+)(-\/?(.+?))$ [NC]
            RewriteRule .* index.php  [E=HTRO:1,E=HTRO_CONTROLLER:postsController,E=HTRO_ACTION:view,E=HTRO_PARAMS[0]:%3,E=HTRO_PARAMS[1]:%5,E=HTRO_PARAMS[2]:%2,L,QSA]
        </code>
    </pre>
    <p>
        At this moment, it is not possible to set appname using this method.
    </p>
</div>
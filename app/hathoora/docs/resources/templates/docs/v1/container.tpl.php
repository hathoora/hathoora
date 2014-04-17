<div class="box">
    <h1>Container</h1>

    <a name="basics"></a>
    <p>
        <code class="inline">hathoora\container</code> in Hathoora PHP Framework is an object created to hold other objects that are accessed, placed, and maintained
        with the class methods of the container.
        It is the gateway to accessing anything available in the framework.
    </p>
    <p>
        Some components like <a href="/docs/v1/controller">contoller</a>, <a href="/docs/v1/model">model</a>, <a href="/docs/v1/database">database</a> etc already extend container. If you need to access container outside these components then you:
    </p>
    <ul>
        <li>Simply extend <code class="inline">hathoora\container</code> class.</li>
        <li>Or use <code class="inline">hathoora\container</code> methods statically.</li>
    </ul>
    <p>
        Using conatiner you can have access to the following components:
    </p>
    <ul>
        <li>configurations</li>
        <li><a href="/docs/v1/services">services</a></li>
        <li><code class="inline">getKernel()</code> for <code class="inline">hathoora\kernel</code></li>
        <li><code class="inline">getObserver()</code> for <code class="inline">hathoora\observer</code></li>
        <li><code class="inline">getRouteRequest()</code> for <code class="inline">hathoora\route\request</code></li>
        <li><code class="inline">getRouteDispatcher()</code> for <code class="inline">hathoora\route\dispatcher</code></li>
        <li><code class="inline">getController()</code> for <code class="inline">hathoora\controller\base</code></li>
        <li><code class="inline">getResponse()</code> for <code class="inline">hathoora\http\response</code></li>
        <li><code class="inline">getRequest()</code> for <code class="inline">hathoora\http\request</code></li>
    </ul>

    <a name="configuration"></a>
    <h2>Configuration</h2>
    <p>
        Below are some example of getting & setting configurations.
    </p>
    <pre>
        <code class="hljs php">
            // get an array of all configurations
            \hathoora\container::getAllConfig();

            // check if has config
            \hathoora\container::hasConfig('hathoora');

            // get key configurations - returns array in this case
            \hathoora\container::getConfig('hathoora');

            // or get nested configurations - returns single value in this case
            \hathoora\conatiner::getConfig('hathoora.logger.profiling.enabled');

            // where $value can be an int, string, object, array - anything.
            \hathoora\container::setConfig($key, $value)
        </code>
    </pre>
</div>
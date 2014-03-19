<div class="box">
    <h1>Debugging</h1>
    <ul class="outline">
        <li><a href="#configuration">Configuration</a></li>
        <li><a href="#webprofiler">Webprofiler</a></li>
        <li><a href="#logging">Logging</a></li>
    </ul>

    <a name="configuration"></a>
    <h2>Configuration</h2>
    <p>
        To enable debugging use the following configuration
    </p>
    <pre>
        <code class="hljs Ini">
            # File config.yml

            hathoora:
                logger:
                    profiling:
                        enabled: 1
                    logging:
                        enabled: 1
                        level: DEBUG
                    webprofiler:
                        enabled: 1
                        content_types: ['text/html']
                        skip_on_ajax: 1
                        skip_on_post_params: []
                        skip_on_get_params: []
                        show_redirects: false
                        system: fale
                        template: PATH TO TEMPLATE
        </code>
    </pre>
    <p>
        There are three main components to logging:
    </p>
    <ul>
        <li><code class="e">proiling</code>: to profile things like database, cache, template and grid.</li>
        <li><code class="e">logging</code>: the usual logging with the ability to specify a following log level:
            <ul>
                <li>DEBUG: most verbose, lots of information</li>
                <li>INFO: useful information, lesser than DEBUG</li>
                <li>WARNING: show only warnings</li>
                <li>ERROR: to show only errors</li>
                <li>FATAL: internal usage when PHP dies and hathoora's error handling is enabled.</li>
            </ul>
        </li>
        <li><code class="e">webprofiler</code>: to show web profiler at the bottom of the page. Further options available:
            <ul>
                <li>content_types: array of content type of <a href="/docs/v1/controller#basics">controller's response</a> for which web profiler will be displayed.</li>
                <li>skip_on_ajax: don't show webprofiler when it is an AJAX request.</li>
                <li>skip_on_post_params: array of POST params for which webprofiler will not be shown.</li>
                <li>skip_on_get_params: array of GET params for which webprofiler will not be shown.</li>
                <li>show_redirects: when enabled would intercept redirects.</li>
                <li>system: when enabled would display system information about load, processes etc to get some sense of webserver's performance.</li>
                <li>template: don't like the default webprofiler template? Use your own by defining a file path and use <a href="https://github.com/hathoora/hathoora-core/blob/master/src/hathoora/logger/webprofiler/template.php" target="_blank">this</a> as an example.</li>
            </ul>
        </li>
    </ul>
    <p>
        If you are following documentation about setting up <a href="/docs/v1/configuration#applicationenvironments">multiple environmnets</a>, then you can enable debugging options for your development server easily.
    </p>

    <a name="webprofiler"></a>:
    <h2>Webprofiler</h2>
    <p>
        This is what webprofile looks like, when enabled:
    </p>
    <p>
        <img class="imgi" src="/_assets/_hathoora/webprofiler/webprofiler_screenshot1.png" /> <br/>
        <img class="imgi" src="/_assets/_hathoora/webprofiler/webprofiler_screenshot_logging.png" /> <br/>
        <img class="imgi" src="/_assets/_hathoora/webprofiler/webprofiler_screenshot_profiling.png" /> <br/>
    </p>

    <a name="logging"></a>
    <h2>Logging</h2>
    <p>
        Following shows an example of how to log.
    </p>
    <pre>
        <code class="hljs php">
            hathoora\logger\logger::log($level, $message);
        </code>
    </pre>
</div>
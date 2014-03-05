<div class="box">
    <h1>Services</h1>
    <ul class="outline">
        <li><a href="#configuration">Configuration</a></li>
        <li><a href="#builtin">Built-in Services</a></li>
        <li><a href="#access">Accessing Services</a></li>
    </ul>

    <a name="configuration"></a>
    <h2>Configuration</h2>
    <p>
        Services are inspired by Symfony's <a href="http://symfony.com/doc/current/book/service_container.html" target="_blank">Service Container</a>. The link best desribes what services are.
    </p>
    <p>
        Services in Hathoora PHP Framework are lazy loaded. They are not instantiated until they are called. You can define services in <a href="/sample/docs/view/configuration#configuration">configuration</a> like so:
    </p>
    <pre>
        <code class="hljs Ini">
            services:
                assets:     <-- name of service
                    class: \namespace\class

                    # arguments to pass to class contructor
                    args: [param1, param2]

                    # method to excute when service is called
                    method: functionName

                    # params to be passed to method
                    params: [param1, param2]

                    # calls calls are the methods called automatically when service is loaded
                    calls:
                        setContainer: [ @container@ ]  <-- @SERVICE@ format for referencing a service
                        someFunction: [ %hathoora.logger.profiling.enabled% ]  <-- %KEY% format for referencing a configuration

                # hathoora cache service
                cache:
                    class: \hathoora\cache\cache
                    calls:
                        setContainer: [ @container@ ]

                    # static services are cached..
                    type: static


                # example of factory service
                cache.notes:
                    factory_service: @cache@

                    # method to call from factory method when the service is called
                    factory_method: pool

                    # params to pass to factory_method
                    factory_method_args: [ "common" ]
        </code>
    </pre>


    <a name="builtin"></a>
    <h2>Built-in Services</h2>
    <p>
        Following services are define automatically:
    </p>
    <ul>
        <li><code class="e">container</code> - returns an instance of container</li>
        <li><code class="e">translator</code> - this will be defined is <code class="inline">hathoora.translation.enabled = 1</code></li>
        <li><code class="e">gulabooAssets</code> - this will be defined is <code class="inline">hathoora.gulaboo.assets.enabled = 1</code></li>
    </ul>

    <a name="services"></a>
    <h2>Accessing Services</h2>
    <p>
        Services are accessed from <a href="/sample/view/docs/container">container</a>. Container is already available in components like <a href="/sample/docs/view/controller">contoller</a>, <a href="/sample/docs/view/model">model</a>, <a href="/sample/docs/view/database">database</a> etc already extend container.
    </p>
    <p>
        Following example shows how services are used.
    </p>
    <pre>
        <code class="hljs php">
            if (\hathoora\container::hasService('cache.notes'))
            {
                $service = \hathoora\container::getService('cache.notes');
                $service->doSomething();
            }
        </code>
    </pre>

    <p>
        If you have setup a service using <code class="e">method</code> parameter, then you can also pass dynamic variables to it's method. Following example demonstrate that.
    </p>

    <pre>
        <code class="hljs php">
            # builtin translation service
            service:
                translator:
                    class: \hathoora\translation\translator
                    method: t


            # where t method of \hathoora\translation\translator looks like the following:
            /**
             * Translation function
             */
            public function t($t, $arrToken = null, $lang = null)
            {}


            # using transltor service to pass params to it's method
            $this->getService('translator',
                                            // params to be passed to t()
                                            array('auth_login_failure_error',
                                                array(
                                                    'token1' => 'tokenValue'
                                                ),
                                                $lang = 'en_US'
                                            )
                             );


            $this->getService('translator',
                                            // params to be passed to t()
                                            array('generic_error_message'));
        </code>
    </pre>
    <p>
        In this example we are using <code class="e">translator</code> service and pass parameters to <code class="t">t()</code> method of <code class="inline">\hathoora\translation\translator</code> class.
    </p>
</div>
<div class="box">
    <h1>Caching</h1>
    <ul class="outline">
        <li>
            <a href="#service">Defining Service</a>
            <ul>
                <li>Memcache Based</li>
                <li>Redis Based (requires <a href="https://github.com/nrk/predis" target="_blank">Predis</a>)</li>
            </ul>
        </li>
        <li>
            <a href="#key-value">Key-Value Commands</a>
        </li>
        <li>
            <a href="#engine-specific">Engine Specifc Commands</a>
        </li>
        <li>
            <a href="#debugging">Debugging</a>
        </li>        
    </ul>

    
    <a name="service"></a>
    <h2>Defining Service</h2>
    <p>
        First step is to define a cache service. This is achived by following convention.
    </p>
    <ul>
        <li>Define cache pools (hosts, engines etc..)</li>
        <li>Cache Factory service</li>
        <li>Defining your cache service</li>
    </ul>
    <p>
        Putting it all together, this is what it looks like.
    </p>
    <pre>
        <code class="hljs Ini">
            # config_ENV.yml
            
            # Define cache pools
            hathoora:
                cache:
                    pools:
                        common_mem: { driver: 'memcache', servers: [{host: "localhost", port: 11211}]}            
                        common_redis: 
                            driver: redis
                            servers:
                            - {host: localhost, port: 6379, database: ~, connection_timeout: 2, read_write_timeout: -1, connection_async: 1, connection_persistent: 1}
                        
                       
            services:
                #Cache Factory service
                cache_factory:
                    class: \hathoora\cache\cache
                    calls:
                        setContainer: [ @container@ ]
                    type: static

                # define your cache services
                cache_common:
                    factory_service: @cache_factory@
                    factory_method: pool
                    factory_method_args: [ "common_mem" ]
                    
                # define multiple cache services
                cache.post:
                    factory_service: @cache_factory@
                    factory_method: pool
                    factory_method_args: [ "common_redis" ]                    
        </code>
    </pre>
    <p>
        In this example we have defined two cache services <code class="e">cache_common</code> and <code class="e">cache.post</code> which will use memcache and redis respectively. You can use them like so:
    </p>
    <pre>
        <code class="hljs php">
            // class extending container
            $cacheCommon = $this->getService('cache_common');
            $cacheCommon->get('someKey');
            
            // or using conatiner
            $cacheCommon = \hathoora\container::getService('cache_common');
            $cacheCommon->get('someKey');
        </code>
    </pre>
    <p>
        Cache services are lazy loaded and will be connected only server when needed. You can also define multiple hosts to connect to (for redundancy).
    </p>
    <pre>
        <code class="hljs Ini">
            
            # Define cache pools with multiple hosts
            hathoora:
                cache:
                    pools:
                        common_mem: 
                            driver: memcache
                            servers: 
                                 - {host: "localhost" , port: 11211}
                                 - {host: "localhostB" , port: 11212}
                                 - {host: "localhostC" , port: 11214}
                            
                        common_redis:
                            driver: redis
                            servers: 
                                - {host: localhostA, port: 6379, database: ~, connection_timeout: 2, read_write_timeout: -1, connection_async: 1, connection_persistent: 1}
                                - {host: localhostB, port: 6379, database: 2, connection_timeout: 2, read_write_timeout: -1, connection_async: 1, connection_persistent: 1}
                                - {host: localhostC, port: 6379, database: ~, connection_timeout: 2, read_write_timeout: -1, connection_async: 1, connection_persistent: 1}
        </code>
    </pre>    

    <a name="key-value"></a>
    <h2>Key-Value Commands</h2>
    <p>
        Basic key-value commands are shown below. You can use redis or memcache for this purpose.
    </p>
    <pre>
        <code class="hljs php">
            // following would work for both memcache & redis services
            $cacheService = $this->getService('service_name');
            
            $cacheService->get($key);
            $cacheService->set($key, $data, $expire);
            $cacheService->delete($key);
            $cacheService->increment($key, $value);
            $cacheService->decrement($key, $value);
        </code>
    </pre>
    
    
    <a name="engine-specific"></a>
    <h2>Engine Specifc Commands</h2>
    <p>
        You can specify engine specific commands as well, but they no longer will be interchangeable between memcache & redis based services.
    </p>
    <p>
        Following showns Memcache specific commands. Memcache based service is extending <a href="http://www.php.net/manual/en/class.memcache.php" target="_blank">Memcache class</a>.
    </p>
    <pre>
        <code class="hljs php">
            $memcacheService = $this->getService('cache_common');
            
            $memcacheService->setCompressThreshold(...);
            $memcacheService->flush(...);
            $memcacheService->replace(...);
        </code>
    </pre>
    <p>
        Following showns Redis specific commands - Redis based service is extending <a href="https://github.com/nrk/predis/blob/v0.8/lib/Predis/Client.php" target="_blank">Predis\Client</a>.
    </p>
    <pre>
        <code class="hljs php">
            $redisService = $this->getService('cache.post');
            
            $redisService->mset(...);
            $redisService->mget(...);
            $redisService->hmset(...);
            $redisService->pipe(...);
            ....
        </code>
    </pre>
    
    <a name="engine-specific"></a>
    <h2>Debugging</h2>
    <p>
        This <a href="/docs/v1/debugging">section</a> shows how to enable profiling for debugging purposes.
    </p>
    <p>
        If you want to view the actual output & input to cache services, then you can enable it by adding <code class="e">debug=1</code> like so:
    </p>
    <pre>
        <code class="hljs Ini">
            # config_ENV.yml
            hathoora:
                cache:
                    pools:
                        common_mem: { debug:1, driver: 'memcache', servers: [{host: "localhost", port: 11211}]}            
                        common_redis: { driver: 'redis', servers: [{host: "localhost", port: 6379}]}       
                        
        </code>
    </pre>
    <p>
        Output (in webprofiler) would look something like this:
    </p>
    <img class="imgi" src="/_assets/_hathoora/webprofiler/webprofiler_screenshot_cache_debug.png" />
</div>
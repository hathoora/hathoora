<div class="box">
    <h1>Listeners</h1>
    <ul class="outline">
        <li><a href="#basics">Basics</a></li>
        <li><a href="#listener">Registering A Listener</a></li>
        <ul>
            <li><a href="#listener-runtime">Runtime (Un)Register</a></li>
        </ul>
        <li><a href="#event">Registering An Event</a></li>
        <li><a href="#action">Listener In Action</a></li>
        <li><a href="#action">Listener In Action</a></li>
        <li><a href="#kernel">Kernel Events</a></li>
        <ul>
            <li><a href="#kernel.ready">kernel.ready</a></li>
            <li><a href="#kernel.route">kernel.route</a></li>
            <li><a href="#kernel.controller">kernel.controller</a></li>
            <li><a href="#kernel.route_unreachable">kernel.route_unreachable</a></li>
            <li><a href="#kernel.response">kernel.response</a></li>
            <li><a href="#kernel.terminate">kernel.terminate</a></li>
        </ul>
    </ul>

    <a name="basics"></a>
    <h2>Basics</h2>
    <p>
        To understand listeners, lets go through basic terminology:
    </p>
    <ul>
        <li><code class="e">Event</code>: is an action.</li>
        <li><a name="subject"></a><code class="e">Subject</code>: the object responsible for event.</li>
        <li><code class="e">Listener</code>: is a php class whose method would react to subject when an event occurs.</li>
    </ul>
    <p>
        Listeners are handled by <code class="inline">hathoora\observer</code> class.
    </p>

    <a name="listener"></a>
    <h2>Registering A Listener</h2>
    <p>
        To understand listeners, lets go through basic terminology:
    </p>
    <p>
        You can define listeners in <a href="/docs/v1/configuration#configuration">configuations</a> like so:
    </p>
    <pre>
        <code class="hljs Ini">
            # File config.yml

            listeners:
                note.save:
                    updateCache:
                        class: \namespace\cacheClass
                        method: functionToCache

                    logIt:
                        class: \namespace\logClass
                        method: functionToLog
        </code>
    </pre>
    <p>
        In this example when we added two listeners for an event called <code class="e">note.save</code>:
    </p>
    <ul>
        <li><code class="e">updateCache</code>: is a listener which is an instance of <code class="inline">\namespace\cacheClass</code> and whose method <code class="inline">functionToCache</code> will be called.</li>
        <li><code class="e">logIt</code>: is a listener which is an instance of <code class="inline">\namespace\logClass</code> and whose method <code class="inline">functionToLog</code> will be called.</li>
    </ul>


    <a name="listener-runtime"></a>:
    <h2>Runtime (Un)Register</h2>
    <p>
        In addition to registering listeners in configuration, you can also register them during run time. So to add above listeners in runtime you can use <code class="inline">addListener</code> like so:
    </p>
    <pre>
        <code class="hljs php">
            $observer = \hathoora\container::getObserver();

            $observer->addListener('note.save', 'updateCache',
                                    array(
                                        'class' => '\namespace\cacheClass',
                                        'method' => 'functionToCache'));

            $observer->addListener('note.save', 'logIt',
                                    array(
                                        'class' => '\namespace\logClass',
                                        'method' => 'functionToLog'));
        </code>
    </pre>
    <p>
        In order to remove a listener runtime, you can use the <code class="inline">removeListener</code> method.
    </p>
    <pre>
        <code class="hljs php">
            $observer = \hathoora\container::getObserver();

            $observer->removeListener('note.save', 'updateCache');
            $observer->removeListener('note.save', 'logIt');
        </code>
    </pre>


    <a name="event"></a>
    <h2>Registering An Event</h2>
    <p>
        Continue above example, to register an event for listeners to act on, you can do something like this:
    </p>
    <pre>
        <code class="hljs php">
            $subject = new \Note();

            $observer = \hathoora\container::getObserver();
            $observer->addEvent('eventName', $subject);
        </code>
    </pre>
    <p>
        In this example we registered an event called <code class="e">eventName</code> and we are passing <code class="e">$subject</code> to our listeners (via referrence).
    </p>

    <a name="action"></a>
    <h2>Listener In Action</h2>
    <p>
        Following shows sample code for what listeners would look like for above configuration:
    </p>
    <pre>
        <code class="hljs php">
            # File \namespace\cacheClass
            class cacheClass
            {
                public function functionToCache(\Note &$note)
                {
                    // cache it

                    // modify subject
                    $note->cached = true;
                }
            }


            # File \namespace\logClass
            class logClass
            {
                public function functionToLog(\Note &$note)
                {
                    // log it

                    var_dump($note->cached); // is bool true
                }
            }
        </code>
    </pre>
    <p>
        In this example the subject <code class="e">\Note $note</code> is passed to listener method's via referrences.
    </p>

    <a name="kernel"></a>
    <h2>Kernel Events</h2>
    <p>
        Hathoora PHP Framework has built-in events that are fired through the life cycle of request. It gives you the flexibility to hook on to these events to change the behaviour.
    </p>
    <ul>
        <li><a name="kernel.ready"></a><code class="e">kernel.ready</code>: this event is fired when Hathoora PHP Framework has bootstrapped the application and loaded configurations and about to dispatch request. Use cases of this event could be:
            <ul>
                <li>Start a custom session</li>
                <li>Throttling logic</li>
                <li>Check of high level application access (based on session/ip/etc)</li>
            </ul>
            <br/>
        </li>
        <li><a name="kernel.route"></a><code class="e">kernel.route</code>: this event is fired when router has been figured out and we know which controller to route request to. Use cases of this event could be:
            <ul>
                <li>To say redirect user to a recaptcha page.</li>
                <li>Redirect unregistered user's to a signup controller.</li>
                <li>Use for displaying landing pages.</li>
            </ul>
            In most cases you would need to overwrite <code class="inline">$kernel->routeDispatcher</code> for this event.
            <br/>
        </li>
        <li><a name="kernel.controller"></a><code class="e">kernel.controller</code>: when controller's action is executable and is about to be executed. Use cases of this event could be the same as <code class="e">kernel.route</code> with addition to:
            <ul>
                <li>Do logic basic on controller name.</li>
                <li>Do controller/action access logic for a DB driven permission system.</li>
            </ul>
            In most cases you would need to overwrite <code class="inline">$kernel->controller = new controller($kernel->routeDispatcher)</code> for this event.
            <br/>
        </li>
        <li><a name="kernel.route_unreachable"></a><code class="e">kernel.route_unreachable</code>: when controller's action is not executable (possible 404). Use cases of this event could be:
            <ul>
                <li>Show pretty 404 pages.</li>
            </ul>
            In this event you can either overwrite <code class="inline">$kernel->controller = new controller($kernel->routeDispatcher)</code> or simple have your listener return a <code class="inline">\hathoora\http\response</code> object.
            <br/>
        </li>
        <li><a name="kernel.response"></a><code class="e">kernel.response</code>: when we have a response object from controller's action. Use cases of this event could be:
            <ul>
                <li>Trim whitespaced from response.</li>
                <li>Pass headers back in response.</li>
            </ul>
            In most cases you would need to overwrite/modify <code class="inline">$kernel->response</code> for this event.
            <br/>
        </li>
        <li><a name="kernel.terminate"></a><code class="e">kernel.terminate</code>: event is fired when request has reached its end. Use cases of this event could be:
            <ul>
                <li>Profilling your application.</li>
            </ul>
            <br/>
        </li>
    </ul>
    <p>
        For all above kernel events, the <a href="#subject">subject</a> passed to listeners is <a href="/docs/v1/container">container</a>.
    </p>
</div>
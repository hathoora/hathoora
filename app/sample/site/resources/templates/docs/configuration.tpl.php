<div class="box">
    <h1>Configuration</h1>
    <ul class="outline">
        <li><a href="#environment">Environment</a></li>
    </ul>

    <a name="environment"></a>
    <h2>Environment</h2>
    <p>
        To setup environment for your hathoora application you can use the following options:<br/><br/>
        <ul>
            <li>1. Define <code>HATHOORA_ENV</code> in Apache. It can be defined in .htaccess of vhost configuration file.

                <pre class="js">
                    SetEnv MYVAR whatever
                </pre>
            </li>
            <li>2. By defining <code>$env</code> variable in <code>index.php</code>
                <pre class="php">
                    // use prod environment by default
                    $env = 'prod';

                    if (isset($_SERVER['HATHOORA_ENV']))
                        $env = $_SERVER['HATHOORA_ENV'];

                    $env = "my_environment";
                    use hathoora\kernel;

                    $kernel = new kernel($env);
                </pre>
            </li>
        </ul>
    </p>
    <p>
        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
    </p>

    <a name="server-requirements"></a>
    <h2>Server Requirements</h2>
    <p>
        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
    </p>
    <p>
        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
    </p>
</div>
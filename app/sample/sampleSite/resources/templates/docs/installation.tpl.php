<div class="box">
    <h1>Installation</h1>
    <ul class="outline">
        <li> <a href="#">Getting Hathoora PHP Framework</a></li>
        <li>
            <ul>
                <li><a href="#composer">Composer</a></li>
                <li><a href="#source">Source</a></li>
            </ul>
        </li>
        <li><a href="#apache">Apache Configuration</a></li>
        <li><a href="#frontController">Front Controller</a></li>
    </ul>

    <a name="composer"></a>
    <h2>Composer</h2>
    <p>
        <a href="http://getcomposer.org/" target="_blank">Composer</a> is a dependency management library for PHP, which you can use to download the Hathoora PHP Framework.
    </p>
    <p>
        Start by <a href="http://getcomposer.org/download/" target="_blank">downloading Composer</a> anywhere onto your local computer. Alternatively if you have curl installed, then you can install it like so:
    </p>
    <pre>
        <code class="hljs bash">
            curl -s https://getcomposer.org/installer | php
        </code>
    </pre>

    <p>
        Next download Hathoora PHP Framework
    </p>
    <pre>
        <code class="hljs bash">
            php composer.phar create-project -s "dev" pakeela/hathoora /some/path/hathoora
        </code>
    </pre>
    <p>
        This command may take several minutes to run as Composer downloads Hathoora PHP Framework along with all of the vendor libraries that it needs. When it finishes, you should have a directory like <a href="/sample/docs/view/organization#layout">this</a>.
    </p>


    <a name="source"></a>
    <h2>Source</h2>
    <p>
        Download the code from <a href="https://github.com/Pakeela/hathoora">GitHub</a>.
    </p>

    <a name="apache"></a>
    <h2>Apache Configuration</h2>
    <p>
        Apache (nginx or others) can be setup in many ways. Just make sure that the <code class="inline">DocumentRoot</code> is pointing to <code class="inline">HATHOORA_ROOTPATH/docroot</code>.
    </p>
    <p>
        A sample Apache vhost configuration based on installation path of <code class="inline">/some/path/hathoora/docroot</code> is shown below:
    </p>
    <pre>
        <code class="hljs apache">
            &nbsp
            &lt;VirtualHost *:80&gt;
                DocumentRoot /some/path/hathoora/docroot
                ServerName mysite.com

                SetEnv HATHOORA_ENV prod

                &lt;Directory /some/path/hathoora/docroot&gt;
                    AllowOverride All
                &lt;/Directory&gt;
            &lt;/VirtualHost&gt;
        </code>
    </pre>


    <a name="frontController"></a>
    <h2>Front Controller</h2>
    <p>
        In order for Hathoora PHP Framework to work, <a href="http://httpd.apache.org/docs/current/mod/mod_rewrite.html" target="_blank">Apache RewriteEngine</a> must be enabled. This has already been taken care of in <code class="inline">HATHOORA_ROOTPATH/.htaccess</code> file.
    </p>
    <pre>
        <code class="hljs apache">
            RewriteEngine On

            # frontController
            RewriteCond %{REQUEST_URI} !^/_assets/ [NC]
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule  .* index.php  [L,QSA]
        </code>
    </pre>
    <p>
        This would route all requests to index.php which would then pass it on to appropriate controller.
    </p>
    <br/>
    <p>
        Inspired by <a href="http://html5boilerplate.com/" target="_blank">HTML5 ★ Boilerplate</a>, in addition to above front controller rewrite, .htaccess is equipped with other goodies like gzip compression, assets expiration and prevent GIT/SVN/etc files access. You can view the complete .htaccess file by clicking <a href="https://github.com/Pakeela/hathoora/blob/master/docroot/.htaccess">here</a>.
    </p>
    <br/>
    <br/>
    <p>
        At this point you should be able to visit <a href="">http://localhost</a> to view Hathoora PHP Framework sample apps.
    </p>
</div>
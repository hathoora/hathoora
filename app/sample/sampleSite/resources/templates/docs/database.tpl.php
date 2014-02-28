<div class="box">
    <h1>Database</h1>
    <ul class="outline">
        <li><a href="#simple">Simple DB Setup</a></li>
        <li><a href="#multiple">Multiple DB Setup</a></li>
        <li><a href="#advanced">Advanced DB Setup</a></li>
        <li><a href="#operations">Common Operations</a></li>
    </ul>

    <p>
        At the moment only MySQL and its variants MariaDB & Percona Server are supported. <br/>
        Mysql connection are lazy loaded.
    </p>

    <a name="simple"></a>
    <h2>Simple DB Setup</h2>
    <p>
        In order to connect to databse, conigure dsn string in configuration.
    </p>
    <pre>
        <code class="hljs Ini">
            hathoora:
                database:
                    default: mysql://dbuser:dbpassword@dbhost:3306/dbname
        </code>
    </pre>

    <p>
        Then to use:
    </p>
    <pre>
        <code class="hljs php">
            // db connections are lazy loaded
            $db = \hathoora\database\dbAdapter::getConnection(); // getConnection() assumes default database configuration
            try
            {
                $arr = $db->fetchArray('SELECT NOW();');
                print_r($arr);
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
        </code>
    </pre>


    <a name="multiple"></a>
    <h2>Multiple DB Setup</h2>
    <p>
        In order to connect to multiple databse, conigure dsn strings in configuration.
    </p>
    <pre>
        <code class="hljs Ini">
            hathoora:
                database:
                    default: mysql://dbuser:dbpassword@dbhost:3306/dbname
                    db2: mysql://dbuser:dbpassword@dbhost:3306/dbname
        </code>
    </pre>

    <p>
        Then to use:
    </p>
    <pre>
        <code class="hljs php">
           // db connections are lazy loaded
            $db = \hathoora\database\dbAdapter::getConnection(); // getConnection() assumes default database configuration
            $db2 = \hathoora\database\dbAdapter::getConnection('db2');


            try
            {
                $arr = $db->fetchArray('SELECT NOW();');
                print_r($arr);
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }


            // db connections are lazy loaded
            try
            {
                $r = $db2->fetchValue('SELECT NOW();');
                print_r($r);
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
        </code>
    </pre>


    <a name="advanced"></a>
    <h2>Advanced DB Setup</h2>
    <p>
        For more complex database connections with multiple databases for read/write consider the following configuration:
    </p>
    <pre>
        <code class="hljs Ini">
            database:
                # simple dsn
                default: mysql://dbuser:dbpassword@dbhost:3306/dbname
                db2: mysql://dbuser:dbpassword@dbhost:3306/dbname

                # Advanced configuration:
                # If you have multiple database servers for read/write then this might be a better options.
                dbPool1:

                    # Failover logic: When a server becomes unavailable then following logics is applied:
                    #       default logic:  In default logic
                    #           Write: If master server is not reachable for write, then next writeable master (if any) will be used based on weight.
                    #           Read: If slave server is not reachable for reads, the next slave (if any) will be used based on weight.
                    #               1.  If there are no slave servers available then master read only server (if any) will be used based on weight.
                    #               2.  If there is still no read only master server, then next master with allow_read (if any) will be used based on weight
                    #       TODO custom logic: An array containing class and method to call. User can specify which db server to use.
                    #           This gives user the flexibility to pick a db based on  db health, concurrent threads etc..
                    #           Format is [\class, method]
                    failover: default

                    #list of servers in this pool
                    servers:

                        dbMaster1:
                            dsn: mysql://dbuser:dbpassword@dbhost:3306/dbname

                            # role - In advance db setup, there are two types of roles:
                            #   master - used for write (and some occasions for read, keep reading)
                            #   slave - used for read
                            role: master

                            # read_only - (default: false) 'readonly' mode would not allow any writes to specified server.  Read only
                            # servers (masters) are usually passive in nature or hot stand by. In read only mode data is not written to
                            # dsn and any query except for SELECT is ignored and result in empty result set
                            read_only: false

                            # allow_read -  (default: true) To allow reads from master when there is no slave and no read only master db
                            # Note that you cannot use allow_read & rad_only for the master
                            allow_read: true

                            # weight - For the same roles a database with higher weight is picked first.
                            weight: 1

                            # on_connect - Any sql commands to run on connect
                            on_connect:
                                - SET NAMES utf8;

                        dbMaster2:
                            dsn: mysql://dbuser:dbpassword@dbhost:3306/dbname
                            role: master
                            read_only: true
                            weight: 2
                            on_connect:
                                - SET NAMES utf8;
                                - /* Another SQL command */;

                        dbSlave1:
                            dsn: mysql://dbuser:dbpassword@dbhost:3306/dbname
                            role: slave
                            weight: 1
        </code>
    </pre>

    <p>
        Then to use:
    </p>
    <pre>
        <code class="hljs php">
            $pool = \hathoora\database\dbAdapter::getConnection('dbPool1');
            try
            {
                // will use dbSalve1
                $r = $pool->fetchArray('SELECT NOW();');

                // will use dbMaster2 because of higher weight
                $r = $pool->query('INSERT INTO TABLE ...');

                // you can also identify server manually
                $r = $pool->server('master:dbMaster1')->fetchArray('SELECT NOW();');
                $r = $pool->server('slave:dbSlave1')->fetchArray('INSERT IGNORE NOW();');

                // add comment about the query for logging in webprofiller
                $r = $pool->comment('hello world')->fetchArray('SELECT NOW();');

                // or specify the last server used
                $r = $pool->server('last')->fetchArray('SELECT NOW();');
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }

        </code>
    </pre>


    <a name="operations"></a>
    <h2>Common Operations</h2>
    <p>
        <b>Common queries</b> - example of common query operations.
    </p>
    <pre>
        <code class="hljs php">
            // lazy loaded connection
            $db = \hathoora\database\dbAdapter::getConnection();

            // for fetching single values
            $value = $db->fetchValue('SELECT field FROM TABLE WHERE field = "?" LIMIT 1', array($param1));

            // for fetching a single row of result as an array
            $arr = $db->fetchArray('SELECT * FROM TABLE WHERE field1 = "" AND field2 = "" LIMIT 1', array($param1, $param2));

            // for fetching multiple results as array
            $arr = $db->fetchArrayAll('SELECT * FROM TABLE LIMIT 1');

            // for fetching multiple results as associate array
            $arr = $db->fetchArrayAll('SELECT fieldName, name, age, country, etc.. FROM TABLE WHERE field="?" LIMIT 1',
                                      array($param1),
                                      array('pk' => fieldName));

            // inserting data, if TABLE has AUTO INCREMENT PRIMARY KEY, then $r would be the last insert id value
            $r = $db->insert('INSERT INTO TABLE...', array($param));

            // other queries, $stmt is an instance of \hathoora\database\dbResult
            $stmt = $db->query('SELECT * FROM TABLE WHERE field = "?"', array($param1));
            if ($stmt && $stmt->rowCount())
            {
                while ($row = $stmt->fetchArray())
                {
                    //
                }
            }

            // escaping string
            $escapedString = $db->escape($string);
        </code>
    </pre>
    <p>
        <b>Exceptions or not:</b> by default exception is thrown when a query fails.
    </p>
    <pre>
        <code class="hljs php">
            // db connections are lazy loaded
            $db = \hathoora\database\dbAdapter::getConnection(); // getConnection() assumes default database configuration
            try
            {
                $arr = $db->fetchArray('SELECT NOW();');
                print_r($arr);
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
        </code>
    </pre>

    <p>To disable exceptions and fail silently use the following:</p>
    <pre>
        <code class="hljs php">
            // db connections are lazy loaded
            $db = \hathoora\database\dbAdapter::getConnection(); // getConnection() assumes default database configuration

            $arr = $db->silent()->fetchArray('SELECT NOW()');
            if ($arr)
            {
                // successful query...
            }
        </code>
    </pre>

    <p>
        <b>Transaction & rollback</b>
    </p>
    <pre>
        <code class="hljs php">
            $db->beginTransaction();
            $rollBack = false;

            // using try catch
            try
            {
                $result = $db->fetchArray('UPDATE ...');
            }
            catch (\Exception $e)
            {
                $rollBack = true;
            }

            // or using silent method
            $arr = $db->silent()->fetchArray('UPDATE ...');
            if (!$arr)
                $rollBack = true;

            if ($rollBack)
                $db->rollback();
            else
                $db->commit();
        </code>
    </pre>
</div>
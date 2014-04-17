<div class="box">
    <h1>Model</h1>
    <ul class="outline">
        <li><a href="#basics">Basics</a></li>
        <li><a href="#modelSAR">Model SAR (Simple Active Records)</a></li>
        <li><a href="#cntr">Accessing Container</a></li>
    </ul>

    <a name="basics"></a>
    <h2>Basics</h2>
    <p>
        Model class in Hathoora PHP Framework is nothing more than the extension of <a href="/docs/v1/container">container</a> and provides an easy way to get database connection using <code class="inline">getDBConnection()</code> function.
    </p>
    <p>
        Model class gives you the freedom to implement your business logic in anyway you want.
    </p>
    <p>

    </p>
    <p>
        A sample model class is shown below in which we are extending <code class="inline">hathoora\model\model</code> class.
    </p>
    <pre>
        <code class="hljs php">
            namespace appNAME\controller
            {
                use hathoora\model\model

                class businessLogic extends model
                {
                    public function getSomething()
                    {
                        $db = $this->getDBConnection();

                        return $db->fetchArray('...');
                    }

                    // or use statically
                    public static function getSomethingElse()
                    {
                        $db = parent::getDBConnection();

                        return $db->fetchArray('...');
                    }
                }
            }
        </code>
    </pre>
    <p>
        For further reading, checkout <a href="/docs/v1/database">database</a> and <a href="/docs/v1/grid">grid</a>.
    </p>

    <a name="modelSAR"></a>
    <h2>Model SAR</h2>
    <p>
        Hathoora PHP Framework also ships with a simple <a href="http://en.wikipedia.org/wiki/Active_record_pattern" target="_blank">Active Records</a> implementation. In order to use it simply extend <code class="inline">hathoora\model\modelSAR</code> and define the following:
    </p>
    <ul>
        <li><code class="e">public $_tableName</code> property</li>
        <li><code class="e">public $_primaryKey</code> property</li>
        <li><code class="e">public $_fields</code> array of fields</li>
        <li><code class="e">public $_fields</code> array of fields</li>
    </ul>
    <p>
        You can also define the following optional functions:
    </p>
    <ul>
        <li><code class="e">customDBConnection()</code>: to return a db connection (<code class="inline">hathoora\database\db</code>) to be used. This is useful if you are doing sharding.</li>
        <li><code class="e">postSave()</code>: a function that is called when you save an object. This is useful of adding events for <a href="/docs/v1/listeners">listeners</a> to do something.</li>
    </ul>
    <p>
        Lets walk through a few examples using the following <code class="e">note</code> class.
    </p>
    <pre>
        <code class="hljs php">
            namespace appNAME\model
            {
                use hathoora\model\modelSAR

                class note extends modelSAR
                {
                    ####################################################
                    ##
                    ##      modelSAR Supported
                    ##
                    public $_tableName = 'note';
                    public $_primaryKey = 'note_id';
                    public $_fields = array(
                            'note_id',
                            'user_id',
                            'title',
                            'content',
                            'status',
                            'date_added',
                            'date_modified');

                    /**
                     * Sharding stuff
                     */
                    public static function customDBConnection($user_id = null)
                    {
                        // allows us to call this function manually
                        if (is_null($user_id))
                            $user_id = $this->user_id;

                        if ($user_id  % 2 == 0)
                            return parent::getDBConnection('eventConnection');
                        else
                            return parent::getDBConnection('oddConnection');
                    }

                    /**
                     * Notify event listeners
                     */
                    public function postSave()
                    {
                        if ($this->getSaveType() == 'add')
                            $this->getObserver()->addEvent('note.add', $this);
                        else if ($this->getSaveType() == 'update')
                            $this->getObserver()->addEvent('note.update', $this);
                        else if ($this->getSaveType() == 'delete')
                            $this->getObserver()->addEvent('note.delete', $this);
                    }



                    ####################################################
                    ##
                    ##      Custom Stuff
                    ##
                    /**
                     * Validation (custom function)
                     */
                    public function validation()
                    {
                        $arrValidations = array(
                            'user_id' => array(
                                'required' => true,
                            ),
                            'title' => array(
                                'required' => true,
                                'rules' => array(
                                    array(
                                        'minlength' => 3,
                                        'maxlength' => 150,
                                        'message' => 'Please enter a valid title.',
                                    ),
                                )
                            ),
                            'content' => array(
                                'required' => true,
                                'rules' => array(
                                    array(
                                        'callback' => array($this, 'validateRuleContent'),
                                    ),
                                )
                            )
                        );

                        return validation::validate($arrValidations, $this);
                    }

                    /**
                     * Content validation rule
                     */
                    public function validateRuleContent()
                    {
                        $noError = true;

                        if (businessRuleFailed)
                            $noError = 'Please try again after resolving the issue.';

                        return $noError;
                    }
                }
            }
        </code>
    </pre>
    <p>
        <a name="modelSAR_new"></a>
        <b>New Record:</b> For storing a new record, you can do something like the following:
    </p>
    <pre>
        <code class="hljs php">
            $note = new \appNAME\model\note();
            $note->user_id = 1;
            $note->title = 'Some title';
            $note->content = 'This is the content.';

            // or you can also do
            $note  = new \appNAME\model\note(array(
                'user_id' => 1,
                'title' => 'Some title',
                'content' => 'This is the content.'
            ));

            $arrError = note->validate();

            if (!is_array($arrError))
                $note_id = $note->save();
            else
                print_r($arrError);
        </code>
    </pre>
    <p>
        <a name="modelSAR_fetch"></a>
        <b>Fetch Record:</b> For fetching record, you can do something like the following:
    </p>
    <pre>
        <code class="hljs php">
            $note = \appNAME\model\note::fetch(array(
                                                    'whereRow' => array('note_id' => 1)));

            // manually figure out which shared to use
            $dbToUse = \appNAME\model\note::customDBConnection(1);
            $note = \appNAME\model\note::fetch(array(
                                                    'dsn' => &$dbToUse,
                                                    'whereRow' => array('note_id' => 1)));

            echo $post->content;
        </code>
    </pre>
    <p>
        In this example we are building query using components of <a href="/docs/v1/grid">grid</a>.
    </p>
    <br/>
    <p>
        <a name="modelSAR_delete"></a>
        <b>Delete Record:</b> For deleting a record, you can do something like the following:
    </p>
    <pre>
        <code class="hljs php">
            $note = \appNAME\model\note::delete(array(
                                                    'whereRow' => array('note_id' => 1)));

            // manually figure out which shared to use
            $dbToUse = \appNAME\model\note::customDBConnection(1);
            $note = \appNAME\model\note::delete(array(
                                                    'dsn' => &$dbToUse,
                                                    'whereRow' => array('note_id' => 1)));

            // or if you already have a note object, then do
            $note->delete();
        </code>
    </pre>

    <h2>Accessing Container</h2>
    <a name="cntr"></a>
        By extending <code class="inline">hathoora/model/*</code> you have access to <a href="/docs/v1/container">container</a>.
    </p>
</div>
<div class="box">
    <h1>Validation</h1>
    <ul class="outline">
        <li><a href="#basics">Basics</a></li>
        <ul>
            <li><a href="#result">Validation Result</a></li>
            <li><a href="#ref">Values Array By Referrence</a></li>
            <li><a href="#trim">Triming</a></li>
            <li><a href="#translations">Translations</a></li>
            <li><a href="#messages">Default Validation Messages</a></li>
        </ul>
        <li><a href="#rules">Filters</a></li>
        <li><a href="#rules">Rules</a></li>
        <li><a href="#modelSAR">Validation For ModelSAR Class</a></li>
    </ul>

    <a name="basics"></a>
    <h2>Basics</h2>
    <p>
        In Hathoora PHP Framework validation is not coupled with forms or data models. Validation is a stand-alone process that can be used for anything.
    </p>
    <p>
        <code class="inline">hathoora\form\validation</code> is the main validation class. In order to use validation, an array defining validation parameters is required. There are three main paramerts for validation:
    </p>
    <ul>
        <li><code class="inline">required</code> - boolean value - when true, an empty value will fail validation.
        <li><code class="inline">filters</code> - array of filters/methods that must be applied (in order defined) to the value before validation.
        <li><code class="inline">rules</code> - array of rules that must be matched (in order defined) against the given value.
    </ul>
    <p>
        Below is an example of validation rules for a valid blog post:
    </p>
    <pre>
        <code class="hljs php">
            $arrValidations = array(
                ...
                'title' => array(
                    'required' => true,
                    'rules' => array(
                        array(
                            'minlength' => 3,
                            'maxlength' => 100,
                            'message' => 'Title must be 3-100 characters long',
                        ),
                    )
                ),
                'tags' => array(
                    'rules' => array(
                        array(
                            'maxlength' => 255,
                            'callback' => array(&$this, 'validateRuleTags'),
                            'message' => 'Too many tags.',
                        ),
                    )
                ),
                'slug' => array(
                    'filters' => array(
                        'contentImage' => array(&$this, 'filterSlug'),
                    ),
                )
                ...
            );
        </code>
    </pre>

    <p>
        In above example we are trying to validate the following fields:
    </p>
        <ul>
            <li>title</li>
            <li>tags</li>
            <li>slug</li>
        </ul>
    <p>
        In order to validate use <code class="inline">validation::validate($arrValidations, &$arrValues)</code>. Where $arrValues must be an associate array containing fields we are validating against.
    </p>
    <pre>
        <code class="hljs php">
            $arrValidations = array(
                ...
            );
            $arrValues = array(
                'title' => 'Hello World',
                'tags' => 'Hello, World',
                'slug' => 'Hello World',
            );

            $mixedResult = validation::validate($arrValidations, &$arrValues);
        </code>
    </pre>

    <p>
        <a name="result"></a>
        <b>Validation Result</b> - <code class="inline">validation::validate($arrValidations, &$arrValues)</code> returns the following results:
    </p>
    <ul>
        <li><code class="inline">TRUE</code> when validation passed</li>
        <li><code class="inline">Associative Array</code> of error messages where key is the field being validated.</li>
    </ul>
    <br/>

    <p>
        <a name="ref"></a>
        <b>Values Array By Referrence</b> - the array of values we are trying to validate must be passed as a referrence because of trimming and filters.
    </p>
    <br/>

    <p>
        <a name="trim"></a>
        <b>Triming</b> - by detault array of values will be trimmed. If you want to disable trimming, then use:
    </p>
    <pre>
        <code class="hljs php">
            $arrValidations = array(
                ...
            );
            $arrValues = array(
                ...
            )

            $mixedResult = validation::validate($arrValidations, &$arrValues, $trimming = false);
        </code>
    </pre>
    <p>
        <a name="translations"></a>
        <b>Translations</b> - you can use <a href="/sample/does/translations">translations service</a> to translate <code class="inline">rule messages</code> like following:
    </p>
    <pre>
        <code class="hljs php">
            $arrValidations = array(
                'title' => array(
                    'required' => true,
                    'rules' => array(
                        array(
                            'minlength' => 3,
                            'maxlength' => 100,
                            'message' => 'title_form_error',
                        ),
                    )
            );
            $arrValues = array(
                ...
            )

            $mixedResult = validation::validate($arrValidations, &$arrValues, $trimming = false, $translation = true);
        </code>
    </pre>
    <p>
        In above example <code class="inline">title_form_error</code> will be translated accordingly. You can also use dynamic <code class="inline">{{field}}</code> inside translations.
    </p>
    <br/>

    <p>
        <a name="messages"></a>
        <b>Default Validation Messages</b> are used in the following scenarios:
    </p>
    <ul>
        <li><code class="inline">validation_field_value_empty</code> when a field is required but has no value</li>
        <li><code class="inline">validation_field_general_error</code> validation rule failed, but there is no message specified for it.</li>
        <li><code class="inline">validation_empty_form_submitted_error</code> all values to be validated are empty/nonexistent.</li>
    </ul>
    <p>
        If you have <a href="translations">translation</a> enabled then above would be translated accordingly. If you are not using translations then you can define these messages inside application configurations like so:
    </p>
    <pre>
        <code class="hljs Ini">
            # File HATHOORA_ROOTPATH/app/directory/namespace/config/config_HATHOORA_ENV.yml

            hathoora:
                validation:
                    messages:
                        validation_field_value_empty: 'Field is required.'
                        validation_field_general_error: 'Please enter a value.'
                        validation_empty_form_submitted_error: 'Make sure you have submitted the form correctly.'

        </code>
    </pre>

    <a name="filters"></a>
    <h2>Filters</h2>
    <p>
        Filters allows you to apply a series of filter to a field's value before it is being validated. See the examples below:
    </p>
    <pre>
        <code class="hljs php">
            # Validation
            $arrValidations = array(
                ...
                'slug' => array(
                    ...
                    'filters' => array(
                        'slugify' => array(&$this, 'filterSlug'),
                    )
                    ...
                ),
                'title' => array(
                    ...
                    'filters' => array(
                        // basically pass anything that you would to call_user_func_array()
                        'prefix' => array(__NAMESPACE__, 'staticPrefixFunction'),
                        'suffix' => array(__NAMESPACE__, 'staticSuffixFunction')
                    )
                    ...
                )
                ...
            );



            # Fields to be validated against
            $arrValues = array(
                'title' => 'This is a title',
                'slug' => 'Hello World',
            );



            # Filter functions
            /**
             * $value is the value of field
             * $arrValues is the array of all values
             */
            public function filterSlug($value, &$arrValues)
            {
                // $arrValues['slug'] would become 'hello-world'
                return \hathoora\helper\stringHelper::slugify($value);
            }

            /**
             * $value is the value of field
             * $arrValues is the array of all values
             */
            public static function staticPrefixFunction($value, &$arrValues)
            {
                // $arrValues['title'] would become 'PREFIX This is a title'
                return 'PREFIX ' . $value;
            }

            /**
             * $value is the value of field
             * $arrValues is the array of all values
             */
            public static function staticSuffixFunction($value, &$arrValues)
            {
                // $arrValues['title'] would become 'PREFIX This is a title SUFFIX'
                return $value . ' SUFFIX';
            }

            $mixedResult = validation::validate($arrValidations, &$arrValues);
        </code>
    </pre>
    <p>
        Whether validation passes or not, at the end of above validation, $arrValues would be transformed into:
    </p>
   <pre>
        <code class="hljs php">
            $arrValues = array(
                'title' => 'PREFIX This is a title SUFFIX',
                'slug' => 'hello-world',
            );
        </code>
    </pre>

    <a name="rules"></a>
    <h2>Rules</h2>
    <p>
        You can apply a series of validation rules for a field to be checked against. These rules are matched in the order specified and would stop (per field basis) when a rule is not validated.
    </p>
    <p>
        Following are builtin rules that Hathoora PHP Framework ships with:
    </p>
    <ul>
        <li>minlength</li>
        <li>maxlength</li>
        <li>url</li>
        <li>min</li>
        <li>max</li>
        <li>numeric</li>
        <li>alpha</li>
        <li>regex</li>
        <li>noHtml</li>
        <li>notTheseHtmlTags</li>
        <li>callback</li>
    </ul>
    <p>
        Rules are demonstrated in the following sample code.
    </p>
    <pre>
        <code class="hljs php">
            $arrValidations = array(
                ...
                '...' => array(
                    ...
                    'rules' => array(
                        array(
                            'minlength' => 3,
                            'maxlength' => 100,

                            # must be a valid URL
                            'url' => true,

                            # minimum value to be 3
                            'min' => 3,

                            # maximum value to be 10
                            'minlength' => 10,

                            'numeric' => true,
                            'alpha' => true,

                            # valid when passes the following regex
                            'regex' => '^[a-z0-9]{3,}$',

                            # field cannot have HTML
                            'noHtml' => true,

                            # or following tags are valid, any other tag would fail validation
                            'noHtml' => 'a,i,b',

                            # field validation when following fields are present
                            'notTheseHtmlTags' => 'javascript, iframe',

                            # custom validation (call_user_func_array() syntax)
                            'callback' => 'callback' => array($this, 'validateRuleXYZ'),
                        )
                    )
                )
            );


            /**
             * Sample callback validation rule
             */
            public function validateRuleXYZ($value, &$arrForm)
            {
                ... some validation ...
                if (noErrors)
                    return true;
                else
                    return 'String of error message';
            }
        </code>
    </pre>

    <a name="modelSAR"></a>
    <h2>Validation For ModelSAR Class</h2>
    <p>
        If you are using a <a href="/sample/docs/view/model#modelSAR">ModelSAR class</a>, then same rules apply at above, however you have the convineance of passing an object instead of array of values.
    </p>

    <pre>
        <code class="hljs php">

            $post = new Post();
            $post->title = 'Some title';

            $arrValidations = array(
                ...
                'title' => array(
                    'required' => true,
                    'rules' => array(
                        array(
                            'minlength' => 3,
                            'maxlength' => 100,
                            'callback' => array(&$post, 'validateField'),
                            'message' => 'Title must be 3-100 characters long',
                        ),
                    )
                )
                ...
            );

            return validation::validate($arrValidations, $post);
        </code>
    </pre>
</div>
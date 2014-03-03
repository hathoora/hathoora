<div class="box">
    <h1>Validation</h1>
    <ul class="outline">
        <li><a href="#basics">Basics</a></li>
        <ul>
            <li><a href="#result">Validation Result</a></li>
            <li><a href="#ref">Values Array By Referrence</a></li>
            <li><a href="#trim">Triming</a></li>
            <li><a href="#translations">Translations</a></li>
        </ul>
        <li><a href="#rules">Filters</a></li>
        <li><a href="#rules">Rules</a></li>
        <li><a href="#application">Source Application Structure</a></li>
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

            $mixedResponse = validation::validate($arrValidations, &$arrValues);

        </code>
    </pre>

    <p>
        <a name="result"></a>
        <b>Validation Result</b> - the array of values we are trying to validate must be passed as a referrence because of trimming and filters.
    </p>
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

            $mixedResponse = validation::validate($arrValidations, &$arrValues, $trimming = false);
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

            $mixedResponse = validation::validate($arrValidations, &$arrValues, $trimming = false, $translation = true);
        </code>
    </pre>
    <p>
        In above example <code class="inline">title_form_error</code> will be translated as needed.
    </p>



    <a name="filters"></a>
    <h2>Filters</h2>
    <p>

    </p>
</div>
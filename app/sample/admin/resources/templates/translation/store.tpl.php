<?php
    $this->start('body');
    $this->load('components/leftPanel.tpl.php');
?>
    <div class="rightPanel docs">
        <div class="box">
            <form method="post" class="smart-green" id="translationForm">
                <input type="hidden" name="translation_id" value="<?php echo $translation_id; ?>" />
                <h1>Translations: <?php echo $arrForm['translation_key']; ?></h1>
                <label>
                    <span>Translation Key:</span>
                    <input type="text" name="translation_key" value="<?php echo $arrForm['translation_key']; ?>" />
                    <div class="desc">Translation key must be lower case and without any spaced.</div>
                 </label>

                <label>
                    <span>Routes:</span>
                    <span class="e" id="addRoute" style="margin-left: 200px; margin-right: 4px;">+</span>
                    <span class="e" id="removeRoute">-</span>
                    <select multiple="true" id="routes" name="routes[]" class="multi">
                        <?php
                            if (isset($arrForm['routes']) && is_array($arrForm['routes']))
                            {
                                foreach ($arrForm['routes'] as $_route)
                                {
                                    echo '<option value="'. $_route .'">'. $_route .'</option>';
                                }
                            }
                        ?>
                     </select>
                    <div class="desc">For getting all translation keys associated to a page in a <a href="/docs/v1/translation#singleCall">single call</a>.</div>
                </label>

                <?php
                    if (is_array($arrLanguages))
                    {
                        foreach($arrLanguages as $_i => $_lang)
                        {
                            $_v = (isset($arrForm['languages']) && isset($arrForm['languages'][$_lang]) ? $arrForm['languages'][$_lang] : null);


                            echo '
                            <label>
                                <span>Translation in '. $_lang.':</span>
                                <div class="clearfix"></div>';

                            if ($_i != 1)
                            {
                                echo '
                                    <textarea class="translation" name="languages['. $_lang.']">'. htmlentities($_v) .'</textarea>
                                    <div class="desc" style="margin-top:5px;">Using HTML as translation for demonstration.</div>';
                            }
                            else
                            {
                                echo '
                                    <input type="text" name="languages['. $_lang.']" value="'. htmlentities($_v) .'" />
                                    <div class="desc">Using another example of translation as simple text.</div>';
                            }

                            echo '
                            </label>';
                        }
                    }
                ?>

                <label>
                    <span>&nbsp;</span>
                    <input type="submit" class="button" value="Submit" /><br/>
                    <div class="desc" style="margin-top:3px;">Once updated related cache keys are re-seeded and related route caches are invalidated.</div>
                </label>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>

<?php
    $this->end('body');

    $this->start('js');
?>
    <script src="/_assets/admin/ckeditor/ckeditor.js"></script>
    <script src="/_assets/admin/ckeditor/adapters/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            // when submitting form do these
            $('#translationForm').submit(function()
            {
                // select all of them so we can do backend logic..
                $('#routes option').attr('selected', true);

                // for each ckEditor update textarea with data
                for(var instanceName in CKEDITOR.instances){
                    CKEDITOR.instances[instanceName].updateElement();
                }
            });

            // add route
            $('#addRoute').click(function()
            {
                route = prompt('Enter a new route, format is app:controller:action or you can use any other name for manual retrieval')
                if (route)
                {
                    var o = new Option(route, route);
                    $('#routes').append(o);
                }
            });

            // remove route
            $('#removeRoute').click(function()
            {
                if ($('#routes').val())
                {
                    $('#routes option:selected').remove();
                }
                else
                    alert('Select a route first.');
            });

            $('textarea.translation').ckeditor({
                height: 100,
                enterMode : CKEDITOR.ENTER_BR,
                shiftEnterMode: CKEDITOR.ENTER_BR
            });
        } );
    </script>

<?php
    $this->end('js');

    // use the template from sample/docs/resouces/templates/
    $this->extend($this->getRouteRequest()->getAppDirectory('docs') . 'resources/templates/layout.tpl.php');
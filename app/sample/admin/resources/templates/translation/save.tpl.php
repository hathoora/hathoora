<?php
    $this->start('body');
    $this->load('components/leftPanel.tpl.php');
?>
    <div class="rightPanel docs">
        <div class="box">
            <h1>Translations: <?php echo $arrForm['item']; ?></h1>

            <form method="post">
                <b>Item</b>:<br/>
                <input type="text" name="item" value="<?php echo $arrForm['item']; ?>" /> <br/>
                <span class="gray">Lowercase, no space</span><br/><br/>

                <b>Routes:</b><br/>
                <select multiple="true" style="width:150px;" id="routes" name="routes">
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
                <button id="addRoute">+</button>
                <button id="removeRoute">-</button>
                <br/>
                <span class="gray">For getting all translation keys associated to a page in a single call.</span><br/><br/>
                <?php
                    if (is_array($arrLanguages))
                    {
                        foreach($arrLanguages as $_lang)
                        {
                            $_v = (isset($arrForm['languages']) && isset($arrForm['languages'][$_lang]) ? $arrForm['languages'][$_lang] : null);
                            echo '<b>'. $_lang.'</b><br/>
                            <textarea class="translation" name="'. $_lang.'">'. $_v .'</textarea><br/><br/>';
                        }
                    }
                ?>
                <input type="submit" value="Submit" />
            </form>

            <script src="/_assets/admin/ckeditor/ckeditor.js"></script>
            <script src="/_assets/admin/ckeditor/adapters/jquery.js"></script>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    // add route
                    $('#addRoute').click(function()
                    {
                        route = prompt('Enter a new route, format is app:controller:action or you can use any other name for manual retrieval')
                        if (route)
                        {
                            var o = new Option(route, route);
                            $('#routes').append(o);
                        }

                        return false;
                    });

                    // remove route
                    $('#removeRoute').click(function()
                    {
                        if ($('#routes').val())
                        {
                            $('#routes option:selected').remove();
                        }
                        else
                            alert('Select a route first');

                        return false;
                    });

                    ckConfig =
                        {
                            height: 180
                        };
                    $('textarea.translation').ckeditor(ckConfig);
                } );
            </script>
        </div>
    </div>
    <div class="clearfix"></div>


<?php
    $this->end('body');

    // use the template from sample/docs/resouces/templates/
    $this->extend($this->getRouteRequest()->getAppDirectory('docs') . 'resources/templates/layout.tpl.php');
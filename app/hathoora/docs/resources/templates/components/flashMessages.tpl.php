<?php
    $arrMessages = $this->getFlashMessage();
    $arrCheckThese = array(
        'info', 'error', 'warning', 'success'
    );
    foreach ($arrCheckThese as $type)
    {
        if (isset($$type))
            $arrMessages[$type] = $$type;
    }

    if (is_array($arrMessages))
    {
        foreach ($arrMessages as $type => $arrMessage)
        {
            echo '
            <div class="flash '. $type .'">';

                if (is_array($arrMessage) || is_object($arrMessage))
                {
                    echo '<p>There was an error processing your request.</p>';

                    echo '<ul>';
                    foreach ($arrMessage as $k => $v)
                    {
                        echo '<li class="'. $k .'">' . $v . '</li>';
                    }
                    echo '</ul>';
                }
                else
                    echo '<p>'. $arrMessage .'</p>';

            echo '
            </div>';
        }
    }
?>
<style>
    #container #body .leftPanel { position: fixed; left: 0px; width: 130px; }
    #container #body .rightPanel { margin-left: 0px; width: 100%; }
    #container #body .rightPanel code.hljs { width:964px; }
</style>
<div class="leftPanel">
    <?php
        if (isset($arrNav) && is_array($arrNav))
        {
            echo '
                <div class="nav box">
                    <ul>';
            foreach($arrNav as $nav => $_arrNav)
            {
                echo '
                        <li>'. $nav . '
                            <ul>';
                foreach($_arrNav as $navItem => $navName)
                {
                    $_selected = isset($currentNav) && $currentNav == $navItem ? ' selected ' : null;
                    $_url = '/admin/'. $navItem;
                    if (substr($navItem, 0, 1) == '/')
                        $_url = $navItem;

                    echo '
                    <li>
                        <a href="'. $_url .'" class="'. $_selected  .'">'. $navName .'</a>';

                    if (isset($currentNav) && $currentNav == $navItem && isset($selectedSubNav) && is_array($selectedSubNav))
                    {
                        echo '<ul>';
                        foreach($selectedSubNav as $subNavItem => $subNavName)
                        {
                            $__selected = isset($currentSubNav) && $currentSubNav == $subNavItem ? ' selected ' : null;
                            echo '
                            <li>
                                <a href="/admin/'. $navItem .'/' . $subNavItem .'" class="'. $__selected  .'">'. $subNavName .'</a>';
                        }
                        echo '</ul>';
                    }

                    echo '
                    </li>';
                }
                echo '
                            </ul>
                        </li>';
            }
            echo '
                    </ul>
                </div>';
        }
    ?>
</div>
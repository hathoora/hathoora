<?php
    $this->start('body');
?>
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
                    $_url = '/docs/v1/'. $navItem;
                    if (substr($navItem, 0, 1) == '/')
                        $_url = $navItem;

                    $_selected = isset($currentNav) && $currentNav == $navItem ? ' selected ' : null;
                    echo '<li><a href="'. $_url .'" class="'. $_selected  .'">'. $navName .'</a></li>';
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
    <div class="rightPanel docs">
        <?php $this->load('docs/' . $version .'/' . $currentNav .'.tpl.php'); ?>
    </div>
    <div class="clearfix"></div>


<?php
    $this->end('body');
    $this->extend('layout.tpl.php');
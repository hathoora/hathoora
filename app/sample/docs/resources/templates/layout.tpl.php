<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
        $this->load($this->getRouteRequest()->getAppDirectory('docs') . '/resources/templates/components/htmlMeta.tpl.php'); ?>
</head>
<body class=" ">
    <div id="container">
        <div id="header">
            <div id="header_inner">
                <h1><a href="http://hathoora.org">Hathoora PHP Framework</a></h1>
                <ul class="menu">
                    <li><a href="http://www.hathoora.org/docs/v1/installation">Download</a></li>
                    <li><a href="http://www.hathoora.org/admin">Admin Site</a></li>
                </ul>

                <!--
                <form name="search" action="/search" method="get">
                    <label for="search_q">Search</label>
                    <input id="search_q" type="text" name="q" value="" placeholder="type here to search..."/>
                </form>
                -->
            </div>
        </div>
        <div id="body">
            <?php
                $this->load($this->getRouteRequest()->getAppDirectory('docs') . '/resources/templates/components/flashMessages.tpl.php');
                $this->block('body');
            ?>
        </div>
        <div id="footer"> </div>

        <?php
            // block for js stuff
            $this->block('js');
        ?>

        <script>
            $(document).ready(function()
            {
                hljs.initHighlightingOnLoad();
            });
        </script>
    </div>
</body>
</html>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hathoora PHP Framework</title>
    <meta name="description" content="Hathoora PHP Framework">
    <style>
        /* meyerweb CSS reset v2.0
        ==================================================*/
        html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6,blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video { margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline; }
            /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section { display: block; }
        body { line-height: 1; }
        ol, ul { list-style: none; }
        blockquote, q { quotes: none; }
        blockquote:before, blockquote:after, q:before, q:after {	content: ''; content: none; }
        table { border-collapse: collapse; border-spacing: 0; }

        body { background:#E9F4E4; font-size:14px; font-family: "Lucida Sans Unicode","Lucida Grande",Verdana,Arial,Helvetica,sans-serif; color:#333; }
        a, a:hover, a:visited { color:#006699; text-decoration: none; }
        p {line-height:18px; }
        #container #header { display:block; margin:0 auto; width:auto; height:70px; background:#4A842D;
            -webkit-box-shadow: 0px 4px 3px rgba(50, 50, 50, 0.3);
            -moz-box-shadow:    0px 4px 3px rgba(50, 50, 50, 0.3);
            box-shadow:         0px 4px 3px rgba(50, 50, 50, 0.3); }
        #container #header #header_inner { width:980px; margin:0 auto;  }
        #container #header #header_inner h1 { float:left; color: #FFFFFF; font-size: 40px; padding:15px 0 0 0; text-shadow: 0 1px 2px #000; }
        #container #header #header_inner h1 a { color:#fff; }
        #container #header #header_inner ul.menu { float: right; font-size: 16px; list-style: none; margin: 17px 0 0 22px;}
        #container #header #header_inner ul.menu li { display:block; float:left; padding: 4px 8px; margin-right:5px; background: none repeat scroll 0 0 #990000; text-transform: uppercase; }
        #container #header #header_inner ul.menu li a { color:#C4847B;  }
        #container #header #header_inner ul.menu li a:hover { color:#fff; }
        #container #header #header_inner form { float:right;  margin-top:25px; }
        #container #header #header_inner form label { margin-right:10px; color:#fff; }
        #container #header #header_inner form input { padding:1px 2px; background:#898989; border:1px solid #666666; color:#F4F4F4; }

        #container #body { min-height:500px; width:980px; margin:0 auto; margin-top:25px; margin-bottom:15px; }
        #container #body .rightPanel { float:left; margin-left:20px; width:760px; }
        #container #body .leftPanel { float:left; width:200px; }
        #container #body .box {
            background:#fff; border:1px solid #D1E6C8;
            display:block;
            padding:15px 15px;
            margin-bottom:12px;
            -moz-box-shadow: 0 0 3px #ccc;
            -webkit-box-shadow: 0 0 3px #ccc;
            box-shadow: 0 0 3px #ccc;
        }

        .nav ul > li { font-weight: bold; }
        .nav ul li ul { margin-top:6px; margin-bottom: 20px; }
        .nav ul li ul li { font-weight: normal; margin-bottom:4px; }
        .nav ul li ul li a.selected { color:#4A842D; }
        .nav ul li ul li ul li { margin-left:25px; list-style: circle; }
        .docs h1 { color:#4A842D; font-size:28px; margin-bottom:10px; font-weight: bold;}
        .docs ul { margin-left:40px; }
        .docs ul li { margin-bottom:8px; list-style: disc; }
        .docs ul li:last-child { margin-bottom:0px; }
        .docs ul.outline { margin-left:0px; margin-bottom:40px; }
        .docs ul.outline li { margin-bottom:6px; list-style: none;  }
        .docs h2 { color:#006699; font-size:22px; margin-bottom:10px; margin-top:40px; font-weight: bold;}
         #container #footer { width:980px; margin:0 auto; }
         code.hljs { overflow:auto; margin-left:-15px; width:744px; }
         .e { color: #AA1144; }
         code.inline {
            background-color: #F8F8F8;
            color:#4A842D;
            border: 1px solid #DDDDDD;
            border-radius: 3px;
            display: inline-block;
            line-height: 1.3;
            margin: 0;
            max-width: 100%;
            overflow: auto;
            padding: 0;
            vertical-align: middle; }

        .clearfix {  *zoom: 1; }
        .clearfix:before, .clearfix:after { display: table; content: ""; }
        .clearfix:after { clear: both; }



        /** hathoora table */
        .hathooraTitle { font-size:18px; padding:10px 0px; font-weight: bold; }
        .hathooraGrid .noResults { padding:10px; background:#FFF2AC; border:1px solid #FF9100; color:#1D2859; }
        .hathooraGrid table.hathooraTable { width: 100%; border:1px solid #DFDFDF; border-top:none;}
        .hathooraGrid table.hathooraTable tbody td,
        .hathooraGrid table.hathooraTable thead th { border-left: 1px solid #DFDFDF; }
        .hathooraGrid table.hathooraTable tbody td:first-child,
        .hathooraGrid table.hathooraTable thead th:first-child { border-left: none; }
        .hathooraGrid table.hathooraTable thead th { text-align: left; padding: 3px 5px 2px 10px; color: #909090; background: #eee; border-top:1px solid #CDCDCD; }
        .hathooraGrid table.hathooraTable tbody td { padding: 7px 11px; vertical-align: middle; }
        .hathooraGrid table.hathooraTable tbody tr:hover td,
        .hathooraGrid table.hathooraTable tbody tr { border-top: 1px solid #DFDFDF; }
        .hathooraGrid table.hathooraTable tbody tr:first-child { box-shadow: 0 1px 0px #fff inset; -webkit-box-shadow: 0 1px 0px #fff inset; -moz-box-shadow: 0 1px 0px #fff inset; }
        .hathooraGrid table.hathooraTable tbody tr:nth-child(even) { background: #f2f2f2; }

    </style>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/default.min.css"> 
    <script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
</head>
<body class=" ">
    <div id="container">
        <div id="header">
            <div id="header_inner">
                <h1><a href="http://hathoora.org">Hathoora PHP Framework</a></h1>
                <ul class="menu">
                    <li><a href="https://github.com/pakeela/hathoora">Download</a></li>
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
                $this->block('body');
            ?>
        </div>
        <div id="footer"> </div>
        <script>
            $(document).ready(function()
            {
                hljs.initHighlightingOnLoad();
            });
        </script>
    </div>
</body>
</html>
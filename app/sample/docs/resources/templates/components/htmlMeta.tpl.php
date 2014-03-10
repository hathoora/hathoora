<?php
    if (isset($arrHTMLMetas) && is_array($arrHTMLMetas))
    {
        foreach ($arrHTMLMetas as $_htmlMetaKey => $arrHTMLMeta)
        {
            if (empty($arrHTMLMeta['type']) || empty($arrHTMLMeta['value']))
                continue;

             if ($arrHTMLMeta['type'] == 'title')
                echo '<title>Hathoora PHP Framework - '. $arrHTMLMeta['value'] .'</title>';
            else if ($arrHTMLMeta['type'] == 'meta')
                echo '<meta name="'. $_htmlMetaKey .'" content="'. $arrHTMLMeta['value'] .'">';
            else if ($arrHTMLMeta['type'] == 'link')
                echo '<link rel="'. $_htmlMetaKey .'" href="'. $arrHTMLMeta['value'] .'">';
        }
    }
?>

    <meta id="viewport" name="viewport" content="initial-scale=.47" />
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
         .gray { color:#ccc; }
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
        .hathooraGrid table.hathooraTable thead th { text-align: left; padding: 3px 0px 2px 0px; color: #909090; background: #eee; border-top:1px solid #CDCDCD; }
        .hathooraGrid table.hathooraTable tbody td { padding: 7px 11px; vertical-align: middle; }
        .hathooraGrid table.hathooraTable tbody tr:hover td,
        .hathooraGrid table.hathooraTable tbody tr { border-top: 1px solid #DFDFDF; }
        .hathooraGrid table.hathooraTable tbody tr:first-child { box-shadow: 0 1px 0px #fff inset; -webkit-box-shadow: 0 1px 0px #fff inset; -moz-box-shadow: 0 1px 0px #fff inset; }
        .hathooraGrid table.hathooraTable tbody tr:nth-child(even) { background: #f2f2f2; }

        .hathooraGrid table.hathooraTable thead th.s { width: 3%; }
        .hathooraGrid table.hathooraTable thead th.m { width: 8%; }
        .hathooraGrid table.hathooraTable thead th.1 { width: 12%; }
        .hathooraGrid table.hathooraTable tbody td.d { text-align: right; }

        /* coulmn sort & delete */
        .hathooraGrid .hathooraColumnName { float: left; padding-left:8px; margin-right: 40px; }
        .hathooraGrid .hathooraColumnOptions { float:right; width:40px; overflow:hidden; margin-left:-40px; }
        .hathooraGrid .hathooraColumnSort { display: none; font-weight: bold; float: right; cursor: pointer; margin-right: 5px; }
        .hathooraGrid .hathooraColumnDel { display: none; font-weight: bold; float: right; cursor: pointer; color:red; margin-right: 5px; }
        /* column is sorted */
        .hathooraGrid .hathooraColumnSorted { background:#FFF2AC !important; }
        .hathooraGrid .hathooraColumnSorted .hathooraColumnSort { display:block; }

        .hathooraGrid table.hathooraTable thead th:hover .hathooraColumnSort,
        .hathooraGrid table.hathooraTable thead th:hover .hathooraColumnDel { display: block; }

        .hathooraPreTable { margin-bottom:10px; }
        .hathooraPostTable { margin-top:10px; }
        .hathooraPaginatorInfo { color:#333; }
        .hathooraPaginator { text-align: right; margin-top:-10px; }
        .hathooraPaginator a { display:inline-block;  padding:3px; background: #F1F1F1; margin-right: 2px; }
        .hathooraPaginator a.hathooraPagiActive { background: #FFF2AC; }

        /* flash messages */
        .flash {
            margin:0 auto;
            background-color: #FCF8E3;
            border: 1px solid #FBEED5;
            color: #C09853;
            margin-bottom: 18px;
            padding: 8px 20px;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5); }
        .flash > p { padding:0px !important; }
        .flash.error { background-color: #F2DEDE;
            border-color: #EED3D7;
            color: #B94A48; }
        .flash.success {  background-color: #DFF0D8;
            border-color: #D6E9C6;
            color: #468847; }
        .flash.info {   background-color: #D9EDF7;
            border-color: #BCE8F1;
            color: #3A87AD; }


        /* http://www.sanwebe.com/2013/10/css-html-form-styles */
        /*######## Smart Green ########*/
         .smart-green {
             margin-right: auto;
             margin-left: auto;
             background: #FFF;
             padding: 30px 30px 20px 30px;
             /*
             box-shadow: rgba(194, 194, 194, 0.7) 0 3px 10px -1px;
             -webkit-box-shadow: rgba(194, 194, 194, 0.7) 0 3px 10px -1px;
             */
             border-radius: 5px;
             -webkit-border-radius: 5px;
             -moz-border-radius: 5px;
        }
         .smart-green h1 {
             font-size: 24px;
             padding: 20px 0px 20px 40px;
             display: block;
             margin: -30px -30px 10px -30px;
             /*background: #9DC45F;*/
             /*text-shadow: 1px 1px 1px #949494; */
             border-radius: 5px 5px 0px 0px;
             -webkit-border-radius: 5px 5px 0px 0px;
             -moz-border-radius: 5px 5px 0px 0px;
             border-bottom:1px solid #ccc;

        }
         .smart-green h1>span {
             display: block;
             font-size: 11px;
             color: #333;
        }

         .smart-green label {
             display: block;
             margin: 0px 0px 5px;
             margin-bottom: 15px;
        }
         .smart-green label>span {
             float: left;
             margin-top: 10px;
        }
         .smart-green input[type="text"], .smart-green input[type="email"], .smart-green textarea, .smart-green select {
             height:24px;
             width: 96%;
             padding: 3px 3px 3px 10px;
             margin-top: 2px;
             margin-bottom: 5px;
             border: 1px solid #E5E5E5;
             background: #FBFBFB;
             outline: 0;
             -webkit-box-shadow: inset 1px 1px 2px rgba(238, 238, 238, 0.2);
             box-shadow: inset 1px 1px 2px rgba(238, 238, 238, 0.2);
             font: normal 14px/14px Arial, Helvetica, sans-serif;
        }
         .smart-green textarea{
             height:100px;
             width: 100%;
             padding-top: 10px;
             clear: both;
             display: block;
        }
         .smart-green select {
             background: url('down-arrow.png') no-repeat right, -moz-linear-gradient(top, #FBFBFB 0%, #E9E9E9 100%);
             background: url('down-arrow.png') no-repeat right, -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FBFBFB), color-stop(100%,#E9E9E9));
            appearance:none;
             -webkit-appearance:none;
            -moz-appearance: none;
             text-indent: 0.01px;
             text-overflow: '';
             width:100%;
             height:30px;
        }
         .smart-green .button {
             background-color: #9DC45F;
             border-radius: 5px;
             -webkit-border-radius: 5px;
             -moz-border-border-radius: 5px;
             border: none;
             padding: 10px 25px 10px 25px;
             color: #FFF;
             text-shadow: 1px 1px 1px #949494;
        }
         .smart-green .button:hover {
             background-color:#80A24A;
        }
         /* custom overwrites */
         .smart-green .desc { color:#ccc; font-size: 90%;}
         .smart-green select.multi { clear:both; display:block; width:275px; height: 60px; overflow: auto; }
    </style>
    <link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/default.min.css">

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
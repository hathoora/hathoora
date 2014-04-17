<?php
namespace hathoora\admin\controller;

use hathoora\controller\controller;

/**
 * base controller
 */
class baseController extends controller
{
    public function __construct()
    {
        $arrNav = array(
            'Admin Nav' => array(
                '/admin' => 'Home',
                'translation' => 'Translations'
            )
        );

        // assign global variables to the template for Meta tags (inside <head>)
        $this->arrHTMLMetas = array(
            'title' => array(
                'type' => 'title',
                'value' => ''
            ),
            'description' => array(
                'type' => 'meta',
                'value' => ''
            ),
            'keywords' => array(
                'type' => 'meta',
                'value' => ''
            )
        );

        $this->setTplVarsByRef('arrHTMLMetas', $this->arrHTMLMetas);
        $this->setTplVarsByRef('arrNav', $arrNav);
    }
}
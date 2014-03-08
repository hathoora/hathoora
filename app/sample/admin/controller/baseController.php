<?php
namespace admin\controller;

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

        $this->setTplVarsByRef('arrNav', $arrNav);
    }
}
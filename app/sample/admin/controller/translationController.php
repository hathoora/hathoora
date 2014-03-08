<?php
namespace admin\controller;

use admin\grid\translation;

/**
 * Translation controller
 */
class translationController extends baseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Translation's home
     */
    public function index()
    {
        $request = $this->getRequest();
        $arrTplParams = array();

        $arrGridPrepare = array(
            'table' => array(
                'title' => 'Table using <code class="inline">admin\grid\translation\translation::getTranslations()</code>'
            )
        );
        $arrTplParams['grid'] = translation::getTranslations($arrGridPrepare, $request->getParam(), $render = true);


        // this is not request for hathoora grid (via ajax)
        if (!$request->isAjax() && !$request->getParam('pajax'))
        {
            $arrTplParams['currentNav'] = 'translation';
            $arrTplParams['selectedSubNav'] = array(
                                                         'add' => 'Add New'
                                                    );


            $template = $this->template('translation/index.tpl.php', $arrTplParams);
            $response = $this->response($template);
        }
        else
        {
            $response = $this->response($arrTplParams['grid']);
        }

        return $response;
    }
}

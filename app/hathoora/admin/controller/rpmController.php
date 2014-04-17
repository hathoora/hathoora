<?php
namespace hathoora\admin\controller;

use hathoora\admin\logic\rpm\menu,
    hathoora\admin\logic\rpm\permission,
    hathoora\admin\logic\rpm\role,
    hathoora\admin\grid\menuGrid,
    hathoora\admin\grid\permissionGrid,
    hathoora\admin\grid\roleGrid;

/**
 * RPM controller
 */
class rpmController extends baseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * RPM permission
     */
    public function permission($do = null, $id = null)
    {
        $response = null;
        $arrTplParams = array();
        $request = $this->getRequest();

        // permission home
        if (is_null($do))
        {
            $arrGridPrepare = array();
            $arrTplParams['grid'] = translation::getTranslations($arrGridPrepare, $request->getParam(), $render = true);

            // check for ajax grid request
            if (!$request->isAjax())
            {
                $template = $this->template('rpm/permission/index.tpl.php', $arrTplParams);
                $response = $this->response($template);
            }
            else
                $response = $this->response($arrTplParams['grid']);
        }
        else if ($do == 'add')
        {

        }

        return $response;
    }
}
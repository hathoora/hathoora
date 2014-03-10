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
        if (!$request->isAjax())
        {
            $this->navMenuHelper($arrTplParams);
            $template = $this->template('translation/index.tpl.php', $arrTplParams);
            $response = $this->response($template);
        }
        else
        {
            $response = $this->response($arrTplParams['grid']);
        }

        return $response;
    }

    /**
     * edit translation
     */
    public function edit($id = null)
    {
        return $this->store('edit', $id);
    }

    /**
     * add translation
     */
    public function add($id = null)
    {
        return $this->store('add', $id);
    }

    /**
     * Add edit helper
     * @param $action
     * @param null $id
     */
    private function store($action, $id = null)
    {
        $arrInfo = null;
        $response = $this->response();

        if ($action == 'edit')
            $arrInfo = $this->getService('translation')->info($id);

        // id doesn't exist..
        if ($action == 'edit' && !is_array($arrInfo))
            $response->forward('/admin/translation', 'Incorrect translation id', 'error');
        else
        {
            $request = $this->getRequest();
            $arrTplParams = array();
            $this->navMenuHelper($arrTplParams);
            $arrTplParams['translation_id'] = $id;
            $arrTplParams['arrLanguages'] = $this->getService('translation')->getLanguages();
            $arrTplParams['arrForm'] =& $arrInfo;

            // form submitted?
            if ($request->getRequestType() == 'POST')
            {
                $arrForm = $request->postParam();

                if ($arrStoreResult = $this->getService('translation')->store($action, $arrForm))
                {
                    if (is_array($arrStoreResult['error']))
                    {
                        $response->setFlash($arrStoreResult['error'], 'error');
                    }
                }

                $arrTplParams['arrForm'] =& $arrForm;
            }

            $template = $this->template('translation/store.tpl.php', $arrTplParams);
            $response->set($template);
        }

        return $response;
    }

    /**
     * navmnu helper..
     */
    private function navMenuHelper(&$arrTplParams)
    {
        $this->arrHTMLMetas['title']['value'] = 'Translations';
        $arrTplParams['currentNav'] = 'translation';
        $arrTplParams['selectedSubNav'] = array(
                                                     'add' => 'Add New'
                                                );
    }
}

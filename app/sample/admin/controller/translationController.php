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
     * Translation's home
     */
    public function routes()
    {
        $request = $this->getRequest();
        $arrTplParams = array();

        $arrGridPrepare = array(
            'table' => array(
                'title' => 'Table using <code class="inline">admin\grid\translation\translation::getTranslationRoute()</code>'
            )
        );
        $arrTplParams['grid'] = translation::getTranslationRoute($arrGridPrepare, $request->getParam(), $render = true);


        // this is not request for hathoora grid (via ajax)
        if (!$request->isAjax())
        {
            $this->navMenuHelper($arrTplParams);
            $template = $this->template('translation/routes.tpl.php', $arrTplParams);
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
    public function view($id = null)
    {
        return $this->store('view', $id);
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
     * add translation
     */
    public function delete($id = null)
    {
        return $this->store('delete', $id);
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

        if ($action == 'delete')
        {
            // don't let others delete demo tks
            if ($id <= 4)
            {
                $response->forward('/admin/translation', 'You cannot delete demo translation keys.', 'error');

                return $response;
            }

            $response->redirect('/admin/translation');
            $arrForm = array('translation_id' => $id);

            if ($arrStoreResult = $this->getService('translation')->store('delete', $arrForm))
                $response->setFlash($arrStoreResult['message'], $arrStoreResult['status']);
        }
        else if ($action == 'edit' || $action == 'view')
            $arrInfo = $this->getService('translation')->info($id);

        // id doesn't exist..
        if ($action == 'edit' && !is_array($arrInfo))
            $response->forward('/admin/translation', 'Incorrect translation id', 'error');
        else if ($action == 'add' || $action == 'edit' || $action == 'view')
        {
            $request = $this->getRequest();
            $arrTplParams = array();
            $arrTplParams['action'] = $action;

            $this->navMenuHelper($arrTplParams);
            $arrTplParams['translation_id'] = $id;
            $arrTplParams['arrLanguages'] = $this->getService('translation')->getLanguages();
            $arrTplParams['arrForm'] =& $arrInfo;

            // form submitted?
            if (($action == 'add' || $action == 'edit') && $request->getRequestType() == 'POST')
            {
                if ($id && $id <= 4)
                {
                    $response->forward('/admin/translation', 'You cannot edit demo translation keys.', 'error');
                    return $response;
                }

                $arrForm = $request->postParam();
                if ($arrStoreResult = $this->getService('translation')->store($action, $arrForm))
                {
                    $response->setFlash($arrStoreResult['message'], $arrStoreResult['status']);

                    // redirect when added ot deleted
                    if ($arrStoreResult['status'] == 'success' && $action == 'add')
                    {
                        $response->redirect('/admin/translation/edit/' . $arrStoreResult['translation_id']);
                        return $response;
                    }
                }

                $arrTplParams['arrForm'] =& $arrForm;
            }

            $template = $this->template('translation/store.tpl.php', $arrTplParams);
            $response->setContent($template);
        }

        return $response;
    }

    /**
     *  Example page
     */
    public function example()
    {
        $arrTplParams = array();

        // get translation for hathoora_hello_world
        $helloTranslation = $this->getService('translation')->t(
            'hathoora_hello_world', array('name' => 'World')
        );

        // get translations for route
        $routeTranslations = $this->getService('translation')->getRouteTranslations('hathoora_translation_route',
            array(
                'hathoora_route_example_title' => array(
                    'date' => date('m/d/y H:i:s')
                ),
                'hathoora_route_example_body' => array(
                    'link' => 'http://hathoora.org'
                )
            )
        );

        $arrTplParams['helloTranslation'] =& $helloTranslation;
        $arrTplParams += (array) $routeTranslations;


        $this->navMenuHelper($arrTplParams);
        $template = $this->template('translation/example.tpl.php', $arrTplParams);
        $response = $this->response($template);

        return $response;
    }

    /**
     * Toggle user selected language
     */
    public function toggleLanguage()
    {
        $request = $this->getRequest();
        $prefLang = $request->sessionParam('language');
        $redirectURL = '/admin/translation/example';

        if ($request->serverParam('HTTP_REFERER'))
            $redirectURL = $request->serverParam('HTTP_REFERER');

        if ($prefLang == 'fr_FR')
            $newLang = 'en_US';
        else
            $newLang = 'fr_FR';

        $request->sessionParam('language', $newLang);

        $response = $this->response();
        $response->forward($redirectURL, 'Your language has been switched to <b>'. $newLang .'</b>', 'success');

        Return $response;
    }

    /**
     * navmnu helper..
     */
    private function navMenuHelper(&$arrTplParams)
    {
        $this->arrHTMLMetas['title']['value'] = 'Translations';
        $arrTplParams['currentNav'] = 'translation';
        $arrTplParams['selectedSubNav'] = array(
                                                    'add' => 'Add New',
                                                    'routes' => 'Routes',
                                                    'example' => 'Usage'
                                                );
    }
}

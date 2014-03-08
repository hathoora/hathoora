<?php
namespace admin\grid;

use hathoora\grid\grid;

class translation extends grid
{
    ####################################################
    #
    #               available grids
    #
    /**
     * Returns everything
     *
     * @param array $arrGridPrepare for overriding stuff
     * @param array $arrFormData
     * @param bool $render or return array of results
     * @return array|mixed
     */
    public static function getTranslations($arrGridPrepare = array(), $arrFormData = array(), $render = true)
    {
        $table_id = 'tk_grd';

        $arrGridPrepare['table']['fields']['jail'] = array('translation_id', 'item', 'language', 'translationFieldNameNotInDbField','notes', 'date_added', 'date_modified');
        $arrGridPrepare['table']['fields']['default'] = array('translation_id', 'item', 'language', 'translationFieldNameNotInDbField');
        self::translationGridHelper($table_id, $arrGridPrepare);

        // Sanitize grid (sort, order, limit and where criteria)
        self::prepare($arrGridPrepare, $arrFormData);

        return parent::getService('translator')->grid($arrGridPrepare, $render);
    }

    /**
     * Prepares arrGridPrepare so its in one place for various grids
     *
     * @param string $table_id
     * @param array arrGridPrepare
     */
    private static function translationGridHelper($table_id, &$arrGridPrepare)
    {
        $arrGridPrepare['table'] = array(
            'id' => $table_id,
            'idRow' => $table_id .'_{{translation_id}}',
            'title' => !empty($arrGridPrepare['table']['title']) ? $arrGridPrepare['table']['title'] : null,
            'class' => !empty($arrGridPrepare['table']['class']) ? $arrGridPrepare['table']['class'] : null,
            'message' => array(
                'noResults' => !empty($arrGridPrepare['table']['message']['noResults']) ? $arrGridPrepare['table']['message']['noResults'] : 'No results found, click <a href="/admin/translation/add">here</a> to add one.',
            ),
            'fields' => array(
                'available' => array(__CLASS__, 'fields'),
                'jail' => !empty($arrGridPrepare['table']['fields']['jail']) ? $arrGridPrepare['table']['fields']['jail'] : null,
                'default' => !empty($arrGridPrepare['table']['fields']['default']) ? $arrGridPrepare['table']['fields']['default'] : null,
                'dynamic' => false,
            ),
            'limit' => array(
                'default' => !empty($arrGridPrepare['table']['limit']['default']) && $arrGridPrepare['table']['limit']['default'] <= 20 ? $arrGridPrepare['table']['limit']['default'] : 1,
                'limits' => array(20, 50),
                'max' => 50,
            ),
            'sort' => array(
                'default' => !empty($arrGridPrepare['table']['sort']['default']) ? $arrGridPrepare['table']['sort']['default'] : 'translation_id',
                'url' => !empty($arrGridPrepare['table']['sort']['url']) ? $arrGridPrepare['table']['sort']['url'] : null,
            ),
            'order' => array(
                'default' => 'DESC',
            ),
            'options' => array(
                // don't display table thead
                'noTableHead' => false,
                // top pager is disabled by default
                'topPager' => isset($arrGridPrepare['table']['options']['topPager']) ? $arrGridPrepare['table']['options']['topPager'] : true,
                // bottom pager is enabled by default
                'bottomPager' => isset($arrGridPrepare['table']['options']['bottomPager']) ? $arrGridPrepare['table']['options']['bottomPager'] : true,
            )
        );

        return $arrGridPrepare;
    }


    ####################################################
    #
    #               the fields
    #
    /**
     * returns an array of all the valid fields that can be used to select, sort, search etc..
     */
    public static function fields()
    {
        $arrFields = array(
            'translation_id' => array(
                'name' => 'ID',
                'classTH' => 's'
            ),
            'item' => array(
                'name' => 'Item',
                'classTH' => 'm'
            ),
            'language' => array(
                'name' => 'Lang',
                'classTH' => 's'
            ),
            'translationFieldNameNotInDbField' => array(
                'name' => 'Translation',
                'classTH' => 'l',
                'dependency' => array(
                    'selectField' => array(
                        'translation' => 'translation'
                    )
                )

            ),
            'notes' => array(
                'name' => 'Names',
                'classTH' => 'l'
            ),
            'date_added' => array(
                'name' => 'Added',
                'classTD' => 'd'
            ),
            'date_modified' => array(
                'name' => 'Modified',
                'classTD' => 'd'
            )
        );


        return $arrFields;
    }

    ####################################################
    #
    #               rendering column functions
    #
    /**
     * This function renders HTML of a translation key
     *
     * @param mixed $value of the current column
     * @param array $arrRow (by ref) the current row
     * @param array $arrGridData (by ref) the entire data
     * @internal param array $context (by ref) context if send any
     *
     * @return null
     */
    public static function renderTKHTML($value, &$arrRow, &$arrGridData)
    {
        //print_r($arrRow);
        #ob_start();
        #include(parent::getRouteRequest()->getAppDirectory('minePkChat') .'/resources/templates/components/room_render.tpl.php');
        #$html = ob_get_clean();

    }
}

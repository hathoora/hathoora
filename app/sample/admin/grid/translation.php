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

        $arrGridPrepare['table']['fields']['jail'] = array('item', 'language', 'translationFieldNameNotInDbField', 'actions');

        // default columns to show when hathoora grid renders for first time
        $arrGridPrepare['table']['fields']['default'] = array('item', 'language', 'translationFieldNameNotInDbField', 'actions');

        // allow users to change columns (delete, reorder etc..);
        $arrGridPrepare['table']['fields']['dynamic'] = true;

        self::translationGridHelper($table_id, $arrGridPrepare);

        // Sanitize grid (sort, order, limit and where criteria)
        self::prepare($arrGridPrepare, $arrFormData);

        return parent::getService('translation')->grid($arrGridPrepare, $render);
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
            'idRow' => $table_id .'_{{uniq_id}}',
            'title' => !empty($arrGridPrepare['table']['title']) ? $arrGridPrepare['table']['title'] : null,
            'class' => !empty($arrGridPrepare['table']['class']) ? $arrGridPrepare['table']['class'] : null,
            'message' => array(
                'noResults' => !empty($arrGridPrepare['table']['message']['noResults']) ? $arrGridPrepare['table']['message']['noResults'] : 'No results found, click <a href="/admin/translation/add">here</a> to add one.',
            ),
            'fields' => array(
                'available' => array(__CLASS__, 'fields'),
                'jail' => !empty($arrGridPrepare['table']['fields']['jail']) ? $arrGridPrepare['table']['fields']['jail'] : null,
                'default' => !empty($arrGridPrepare['table']['fields']['default']) ? $arrGridPrepare['table']['fields']['default'] : null,
                'dynamic' => !empty($arrGridPrepare['table']['fields']['dynamic']) ? $arrGridPrepare['table']['fields']['dynamic'] : false,
            ),
            'limit' => array(
                'default' => !empty($arrGridPrepare['table']['limit']['default']) && $arrGridPrepare['table']['limit']['default'] <= 20 ? $arrGridPrepare['table']['limit']['default'] : 20,
                'limits' => array(20, 50),
                'max' => 50,
            ),
            'sort' => array(
                'default' => !empty($arrGridPrepare['table']['sort']['default']) ? $arrGridPrepare['table']['sort']['default'] : 'tk.translation_id',
                'url' => !empty($arrGridPrepare['table']['sort']['url']) ? $arrGridPrepare['table']['sort']['url'] : null,
            ),
            'order' => array(
                'default' => 'DESC',
            ),
            'options' => array(
                'noTableHead' => false,
                'topPager' => false,
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
            'item' => array(
                'name' => 'Item',
                'classTH' => 'm',
                'sort' => true,
                'content' => array(
                    'link' => '/admin/translation/edit/{{translation_id}}'
                )
            ),
            'language' => array(
                'name' => 'Lang',
                'classTH' => 's'
            ),
            'translationFieldNameNotInDbField' => array(
                'name' => 'Translation',
                'classTH' => 'l',
                'sort' => true,
                'dbField' => 'tv.translation',
                'content' => array(
                    'function' => array(__CLASS__, 'renderTranslationField')
                ),
                'dependency' => array(
                    'selectField' => array(
                        'translation' => 'translation'
                    )
                )

            ),
            'actions' => array(
                'name' => 'Actions',
                'dbField' => false,
                'classTH' => 's',
                'content' => array(
                    'function' => array(__CLASS__, 'renderTranslationActions', array('exampleContext' => 1))
                )
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
     *
     * @return mixed
     */
    public static function renderTranslationField($value, &$arrRow, &$arrGridData)
    {
        return htmlentities($arrRow['translation']);
    }

    /**
     * This function renders actions
     *
     * @param mixed $value of the current column
     * @param array $arrRow (by ref) the current row
     * @param array $arrGridData (by ref) the entire data
     * @param array $context (by ref) context if send any
     *
     * @return mixed
     */
    public static function renderTranslationActions($value, &$arrRow, &$arrGridData, $context = null)
    {
        // logic as needed, who can view delete button?
        return '<a href="/admin/translation/edit/{{translation_id}}">Edit</a> | <a href="/admin/translation/delete/{{translation_id}}">Del</a>';
    }
}

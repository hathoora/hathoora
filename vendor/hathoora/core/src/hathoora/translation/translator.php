<?php
namespace hathoora\translation;

use hathoora\model\model;

class translator extends model
{
    /**
     * holds container
     */
    private $container;
    
    /**
     * Translator constructor
     */
    public function __construct()
    { 
        $this->db = $this->getDBConnection();
    }
    
    /**
     * Set dic container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
    
    /**
     * Returns the value of token
     *
     * @param string $token that we want to get the value for ex: {{name}}
     * @param array $arrTokens that has a key of token ex: 'name' = 'xyz'
     */
    public function deTokenize($token, &$arrTokens)
    {
        return \hathoora\helper\stringHelper::deTokenize($token, $arrTokens);
    }

    /**
     * Translation function
     */
    public function t($t, $arrToken = null, $lang = null)
    {
        $t = trim($t);
        $translation = null;
        $cacheService = $this->getCacheService();
        $prefLang = $this->getRequest()->sessionParam('language');
        $lang =  $prefLang ? $prefLang : 'en_US';
        
        if ($cacheService)
        {
            $cacheKey = 'translation:' . $t . ':'. $lang;
            $translation = $cacheService->get($cacheKey);
        }
        
        if ($this->db && is_null($translation))
        {
            $translationDB = $this->db->fetchValue('
                            SELECT translation
                            FROM translation_item
                            WHERE item = "?" AND language = "?"
                            LIMIT 1', array($t, $lang));
            
            if ($translationDB)
                $translation = $translationDB;
            else
                $translation = $t;
            
            // cache it
            if ($cacheService)
                $cacheService->set($cacheKey, $translation, 86400 * 5);
        }
        
        if (is_null($translation))
            $translation = $t;
        
        $translation = $this->deTokenize($translation, $arrToken);
        
        return $translation;
    }
    
    /**
     * Returns cache service
     */
    public function getCacheService()
    {
        if ($this->container->hasService('cache.translation'))
            return $this->container->getService('cache.translation');
            
        return false;
    }
}
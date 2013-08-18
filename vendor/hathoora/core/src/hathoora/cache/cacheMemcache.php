<?php
namespace hathoora\cache;
 
class cacheMemcache extends \Memcache implements cacheInterface
{
    /**
     * constructor
     *
     * @param array $arrConfig ex:
     *
     *    Array
     *     (
     *        [servers] => Array
     *            (
     *                [0] => Array
     *                    (
     *                        [host] => locahost
     *                        [port] => 3306
     *                    )
     *                [1] => Array
     *                    (
     *                        [host] => locahost
     *                        [port] => 3306
     *                    )
     *            )
     *    )    
     */
    public function __construct($arrConfig)
    {
        if (is_array($arrConfig['servers']) && $this->canCache())
        {
            foreach($arrConfig['servers'] as $i => $arrServer)
            {
                $this->addServer(trim($arrServer['host']), trim($arrServer['port']));
            }
        }
 
        # @maybe throw exception?
        #if (!$this->getVersion() && $this->canCache())
        #    throw new Exception('Unable to connect to memcache.'); 
    }
 
    /**
     * to disconnect a connection
     */
    public function disconnect()
    { }
 
   
   /**
    * Returns the state about whether or not it can cache things or return cached objects etc..
    */
    public function canCache()
    {
        return true;
    }
 
    /**
     * Store in cache
     * 
     * @param string $key
     * @param mixed $data to store
     * @param int $expire time in seconds
     * @param array $arrExtra for extra logic
     */
    public function set($key, $data, $expire, $arrExtra = array())
    {
        if (!$this->canCache())
            return false;
       
        $flag = false;
        if (isset($arrExtra['flag']))
            $flag = $arrExtra['flag'];
        
        return parent::set($key, $data, $flag, $expire);
   }      
 
    /**
     * Return cached content
     * @return NULL (instead of FALSE) when key is not found, otherwise the value, for all other errors false     
     */
    public function get($key)
    {
        if (!$this->canCache())
            return false;
           
        $return = @parent::get($key);
        
        if (!$return)
            $return = null;
            
        return $return;
    }
 
    /**
     * Delete cache..
     */
    public function delete($key)
    {
        if (!$this->canCache())
            return false;
 
        return parent::delete($key);
    }
 
    /**
     * Increment 
     */
    public function increment($key, $value = 1)
    {
        if (!$this->canCache())
            return false;

        return parent::increment($key, $value);
    }
 
   /**
    * decrement 
    */
    public function decrement($key, $value = 1)
    {
        if (!$this->canCache())
            return false;
 
        return parent::decrement($key, $value);
    }
}
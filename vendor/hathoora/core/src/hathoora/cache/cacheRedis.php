<?php
namespace hathoora\cache;
 
use Predis;
 
class cacheRedis extends Predis\Client implements cacheInterface
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
     *                        [port] => 6379
     *                        [password] => secret
     *                    )
     *                [1] => Array
     *                    (
     *                        [host] => locahost
     *                        [port] => 6379
     *                        [password] => secret
     *                    )
     *            )
     *    )    
     */
    public function __construct($arrConfig)
    {
        if (is_array($arrConfig['servers']) && $this->canCache())
        {
            $arrServers = array();
           
            foreach($arrConfig['servers'] as $i => $arrServer)
                $arrServers[] = array_map("trim", $arrServer);
           
            if (count($arrServers > 0))
            {
                parent::__construct($arrServers);
            }
        }
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
    public function set($key, $data, $expire = 86400, $arrExtra = array())
    {
        if (!$this->canCache())
            return false;
        
        $setRetVal = parent::set($key, (is_array($data) ? serialize($data) : $data));
       
        if (($expire <= time()) && (parent::exists($key)))
            $expireRetVal = parent::expire($key, $expire);
        else
            $expireRetVal = parent::expireat($key, $expire);
           
        return array('set_return_value' => $setRetVal, 'expire_return_value' => $expireRetVal);
    }      
 
   /**
     * Return cached content
     */
    public function get($key)
    {
        if (!$this->canCache())
            return false;
       
        $rawValue = parent::get($key);
        $unserializedValue = @unserialize($rawValue);  
       
        if (($rawValue === 'b:0;') || ($unserializedValue !== false))
            return $unserializedValue;
        else
            return $rawValue;
    }
 
    /**
     * Delete cache..
     */
    public function delete($key)
    {
        if (!$this->canCache())
            return false;
 
        if (is_array($key))
            call_user_func(__NAMESPACE__ . 'parent::del', $key);
        else
            return parent::del($key);
    }
 
    /**
     * Increment 
     */
    public function increment($key, $value = 1)
    {
        if ((!$this->canCache()) || ($value < 1))
            return false;
 
        if ($value == 1)
            return parent::incr($key);
        else
            return parent::incrby($key, $value);
    }
 
    /**
     * decrement 
     */
    public function decrement($key, $value = 1)
    {
        if ((!$this->canCache()) || ($value < 1))
            return false;
 
        if ($value == 1)
            return parent::decr($key);
        else
            return parent::decrby($key, $value);
    }
}
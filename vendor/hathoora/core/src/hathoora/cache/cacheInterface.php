<?php
namespace hathoora\cache;
 
/**
 * An interface for key-value cache classes
 */
interface cacheInterface
{
   /**
     * construct, connect to db.. must throw exception if unable to connect
     *
     * @param array $arrConfig ex:
     *
     *    Array
     *     (
     *        [extra..] => extra..
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
   public function __construct($arrConfig);
 
   /**
     * to disconnect a connection
     */
   public function disconnect();
 
   /**
     * Returns the state about whether or not it can cache things or return cached objects etc..
     */
   public function canCache();
 
   /**
     * Store in cache
     *
     * @param string $key
     * @param mixed $data to store
     * @param int $expire time in seconds
     * @param array $arrExtra for extra logic
     */
   public function set($key, $data, $expire, $arrExtra = array());
 
   /**
     * Returns cached content
     *
     * @param string $key
     * @return NULL (instead of FALSE) when key is not found, otherwise the value, for all other errors false
     */
   public function get($key);
 
   /**
     * Delete cached content
     *
     * @param string $key
     */
   public function delete($key);
 
   /**
     * Increment cache content
     *
     * @param string $key
     */
   public function increment($key, $value = 1);
 
   /**
     * decrement cache content
     *
     * @param string $key
     */
   public function decrement($key, $value = 1);
}
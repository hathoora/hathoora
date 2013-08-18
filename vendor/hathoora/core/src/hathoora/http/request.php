<?php
namespace hathoora\http;

use hathoora\registry;

/**
 * request class
 */
class request
{
    // stores cookie object
    protected $cookieData;
    
    // stores $_GET data
    protected $getData;
    
    // stores $_POST data
    protected $postData;    

    // stores $_FILES data
    protected $filesData;   
    
    // stores $_SERVER data
    protected $serverData;    
    
    // stores $_SESSION data
    protected $sessionData;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->getData =& $_GET;
        $this->postData =& $_POST;
        $this->serverData =& $_SERVER;
        $this->sessionData =& $_SESSION;
        $this->cookieData =& $_COOKIE;
        $this->filesData =& $_FILES;
    }
    
    /**
     * Make the request object and returns
     */
    public static function make()
    {
        if (is_object(registry::get('hathooraRequestObject')))
            return registry::get('hathooraRequestObject');
        else
        {
            $request = new request();
            registry::set('hathooraRequestObject', $request);
            return registry::get('hathooraRequestObject');
        }
    }
    
    /**
     * Sets\gets POST param
     * 
     * @param string $name to get POST value, if this is not passed then returns POST array
     * @param string $value to store
     */
    public function postParam($name = null, $value = null, $unset = false)
    {
        if ($name)
        {
            if ($unset && isset($this->postData[$name]))
            {
                unset($this->postData[$name]);
                return true;
            }
            else
            {
                if (isset($value))
                    return $this->postData[$name] = $value;
                else
                {
                    if (isset($this->postData[$name]))
                        return $this->postData[$name];
                    else
                        return false;
                }
            }
        }
        else
            return $this->postData;
    }
    
    /**
     * Sets\gets GET param
     * 
     * @param string $name to get GET value, if this is not passed then returns GET array
     * @param string $value to store
     */
    public function getParam($name = null, $value = null, $unset = false)
    {
        if ($name)
        {
            if ($unset && isset($this->getData[$name]))
            {
                unset($this->getData[$name]);
                return true;
            }
            else
            {
                if (isset($value))
                    return $this->getData[$name] = $value;
                else
                {
                    if (isset($this->getData[$name]))
                        return $this->getData[$name];
                    else
                        return false;
                }
            }
        }
        else
            return $this->getData;
    }

    /**
     * gets SERVER param
     * 
     * @param string $name to get GET value, if this is not passed then returns GET array
     * @param string $value to store
     */
    public function serverParam($name = null)
    {
        if ($name)
        {
            $name = strtoupper($name);
            
            if (isset($this->serverData[$name]))
                return $this->serverData[$name];
            else
                return false;
        }
        else
            return $this->serverData;
    }
    
    /**
     * Gets FILES param
     * 
     * @param string $name to get GET value, if this is not passed then returns GET array
     * @param string $value to store
     */
    public function filesParam($name = null)
    {
        if ($name)
        {
            if (isset($this->filesData[$name]))
                return $this->filesData[$name];
            else
                return false;
        }
        else
            return $this->filesData;
    }
    
    /**
     * Sets\gets SESSION param
     * 
     * @param string $name to get SESSION value, if this is not passed then returns SESSION array
     * @param string $value to store
     * @param string $unset the value
     */
    public function sessionParam($name = null, $value = null, $unset = false)
    {
        if ($name)
        {
            if ($unset && isset($this->sessionData[$name]))
            {
                unset($this->sessionData[$name]);
                return true;
            }
            else
            {
                if (isset($value))
                    return $this->sessionData[$name] = $value;
                else
                {
                    if (isset($this->sessionData[$name]))
                        return $this->sessionData[$name];
                    else
                        return false;
                }
            }
        }
        else
            return $this->sessionData;
    }
    
    /**
     * Set\Gets a cookie. Silently does nothing if headers have already been sent.
     *
     * @param string $name
     * @param string $value
     * @param mixed $expiry
     * @param string $path
     * @param string $domain
     * @return bool
     */
    public function cookieParam($name, $value = null, $expiry = 31536000, $path = '/', $domain = false)
    {
        $retval = false;
        
        // only return $name's value
        if (!isset($value))
            $retval = (isset($this->cookieData[$name]) ? $this->cookieData[$name] : false);
        // set cookie
        else
        {
            if (!headers_sent())
            {
                if ($domain === false)
                    $domain = $this->serverParam('HTTP_HOST');

                if ($expiry === -1)
                    $expiry = 1893456000; // Lifetime = 2030-01-01 00:00:00
                elseif (is_numeric($expiry))
                    $expiry += time();

                $retval = @setcookie($name, $value, $expiry, $path, $domain);
                if ($retval)
                    $this->cookieData[$name] = $value;
            }
        }
        
        return $retval;
    }

    /**
     * Delete a cookie.
     *
     * @param string $name
     * @param string $path
     * @param string $domain
     * @param bool $remove_from_global Set to true to remove this cookie from this request.
     * @return bool
     */
    public function deleteCookie($name, $path = '/', $domain = false, $remove_from_global = true)
    {
        $retval = false;
        if (!headers_sent())
        {
          if ($domain === false)
            $domain = $this->serverParam('HTTP_HOST');
          $retval = setcookie($name, '', time() - 3600, $path, $domain);

          if ($remove_from_global)
            unset($this->cookieData[$name]);
        }
        
        return $retval;
    }

    /**
     * Return request type
     */
    public function getRequestType()
    {
        if (isset($this->serverData['REQUEST_METHOD']))
            return $this->serverData['REQUEST_METHOD'];
        
        return false;
    }

    /**
     * Returns the root url from which this request is executed.
     *
     * The base URL never ends with a /.
     */
    public function getBaseUrl()
    {
    
    }
    
    /**
     * Returns IP address of  the user by taking proxy in consideration
     */
    public function guessRealIPAddress()
    {   
        $ip = null;
        
        if (isset($_SERVER['HTTP_X_CLIENT_IP']))
            $ip = $_SERVER['HTTP_X_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip =  $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ip = $_SERVER['HTTP_FORWARDED'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
            
        return $ip;
    }
    
    /**
     * Returns true if its an ajax call
     */
    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            return true;
            
        return false;
    }
}
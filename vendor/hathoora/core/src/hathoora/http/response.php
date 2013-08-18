<?php
namespace hathoora\http;

class response
{
    /**
     * stores content that we will send out to the browser
     */
    private $content;
    
    private $status;
    
    // stores redirct info
    private $redirectURL;
    
    // stores header
    private $arrHeaders;
    
    /**
     * Constructor
     * 
     * @param mixed $content string or \hathoora\template\template object
     * @param string $arrHeaders
     * @param string $status http status
     */
    public function __construct($content = false, $arrHeaders = array(), $status = 200)
    {
        if ($content)
        {
            if (is_object($content) && $content instanceof \hathoora\template\template)
                $this->content = $content->fetch();
            else
                $this->content = $content;
            $this->setStatus($status);
            
            // default headers
            if (!$arrHeaders || !count($arrHeaders))
                $this->setDefaultHeaders();
            
            $this->setHeaders($arrHeaders);
        }
        
        return $this;
    }
    
    /**
     * render response and send it to client
     */
    public function render()
    {
        echo $this->content;
    }

    /**
     * Returns the rendered output as string
     */
    public function toString()
    {
        return $this->content;
    }
    
    /**
     * Redirect
     *
     * @param mixed $url
     * @param int $code 301 = permanent, 302 = temporary
     */
    public function redirect($url, $code = 302)
    {
        $this->redirectURL = $url;
        $this->setStatus($code);
    }
    
    /**
     * Forward with a flash message
     *
     * @param mixed $url
     * @param int $code 301 = permanent, 302 = temporary
     */
    public function forward($url, $message, $type = 'info')
    {
        $this->setFlash($message, $type);
        $this->redirect($url);
    }

    /**
     * Set flash message
     * 
     * @param string $message 
     * @param string $type (info|warning|error|success)
     */
    public function setFlash($message, $type = 'info')
    {
        $request = request::make();
        $httpFlash = $request->sessionParam('httpFlash');
        if (is_array($httpFlash))
            $httpFlash[$type] = $message;
        else
            $httpFlash[$type] = $message;
        $request->sessionParam('httpFlash', $httpFlash);
    }
    
    /**
     * Removes all due flash messages.
     */
    public function removeFlash() 
    {
        $request = request::make();
        $request->sessionParam('httpFlash', null, true);
    }

    /**
     * Gets a flash message, that has the given $type
     *
     * @param bool $remove, when true will remove flash message from session
     */
    public function getFlash($remove = true) 
    {
        $request = request::make();
        $flash = $request->sessionParam('httpFlash');
        if ($remove)
            $this->removeFlash();
            
        return $flash;
    }

    /**
     * Executes a response, whether its diplay to redirect
     *
     * @param bool $sessionWriteClose see http://php.net/manual/en/function.session-write-close.php
     */
    public function send($sessionWriteClose = true)
    {
        // redirect
        if (isset($this->redirectURL))
            $this->setHeader('Location', urldecode($this->redirectURL)); 
            
        $this->sendHeaders();
        
        // echo
        if (!isset($this->redirectURL))
            $this->render();
        else
        {
            if ($sessionWriteClose) 
                session_write_close();
        }
    }
    
    /**
     * this function sends headers at the time of send()
     */
    private function sendHeaders()
    {
        if (!headers_sent())
        {
            if (is_array($this->arrHeaders))
            {
                foreach($this->arrHeaders as $k => $v)
                {
                    if (!$v)
                        header($k, true, $this->status);
                    else
                        header($k.':'. $v, true, $this->status);
                }
            }
        }
    }
    
    /**
     * Public function for resetting headers
     */
    public function resetHeaders()
    {
        $this->arrHeaders = array();
        $this->setStatus(0);
    }
    
    /**
     * Sets various cache related headers
     *
     * @param array $arrParams to which one can pass
     *      - etag
     *      - last_modified Y-m-d h:i:s format
     *      - public
     *      - s_maxage
     *      - max_age
     *      - expires       Y-m-d h:i:s format
     */
    public function setCache($arrParams)
    {
        $etag = isset($arrParams['etag']) ? $arrParams['etag'] : null;
        $last_modified = isset($arrParams['last_modified']) ? $arrParams['last_modified'] : null;
        $public = isset($arrParams['public']) ? $arrParams['public'] : null;
        $s_maxage = isset($arrParams['s_maxage']) ? $arrParams['s_maxage'] : null;
        $max_age = isset($arrParams['max_age']) ? $arrParams['max_age'] : null;
        $expires = isset($arrParams['expires']) ? $arrParams['expires'] : null;
        
        if ($etag)
            $this->setHeader('Etag', $etag);
        
        if ($last_modified)
            $this->setHeader('Last-Modified', $last_modified);

        if ($public || $s_maxage || $max_age)
        {
            $cacheControl = ($max_age ? 'max-age='. $max_age.', ' : null);
            $cacheControl .= ($public ? 'public, ' : null);
            $cacheControl .= ($s_maxage ? 's-maxage='. $s_maxage.', ' : null);
            $this->setHeader('Cache-Control', $cacheControl);
        }        
        
        if ($expires)
            $this->setHeader('Expires', $expires);        
    }
    
    /**
     * Set response status
     */
    public function setStatus($code)
    {
        $this->status = trim($code);
    }
    
    /**
     * Get response status
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Sets header 
     * 
     * @param array $arrHeaders
     */
    private function setHeaders($arrHeaders)
    {
        if (is_array($arrHeaders))
        {
            foreach($arrHeaders as $name => $value)
            {
                $this->setHeader($name, $value);
            }
        }
    }
    
    /**
     * Set default headers
     */
    public function setDefaultHeaders()
    {
        $this->setHeader('Content-Type', 'text/html; charset=UTF-8');
    }
    
    /**
     * set header
     * 
     * @param string $name specifies the name of the header 
     * @param string $value for header
     */
    public function setHeader($name, $value = null)
    {   
        // content type needs to be 'Content-Type'
        if (strtolower($name) == 'content-type')
            $name = 'Content-Type';
            
        $this->arrHeaders[$name] = $value;
    }

    /**
     * Get header
     * 
     * @param string $name specifies the name of the header to read; if empty or omitted, 
     * an associative array with all headers will be returned
     */
    public function getHeader($name = null)
    {
        if (is_null($name))
            return $this->arrHeaders;
        else
        {
            if (isset($this->arrHeaders[$name]))
                return $this->arrHeaders[$name];
        }
        
        return false;
    }
}
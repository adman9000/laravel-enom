<?php namespace onethirtyone\enomapi;

class EnomAPI {

    /**
     * Domain
     * @var array
     */
    protected $domain = array();

    /**
     * API URL String
     * @var [type]
     */
    protected $apiString;

    /**
     * API Parameters
     * @var array
     */
    protected $params = array();

    
    /**
     * Parse domain for API calls
     * @param  string $domain 
     * @return void         
     */
    public function parseUrl($domain) 
    {
        if(strpos($domain,"://") === false && substr($domain,0,1) != "/") $domain =  "http://". $domain;

        $parts = parse_url($domain);
        $split = explode('.', str_replace('www.','',$parts['host']));

        $this->domain['host'] = $parts['host'];
        $this->domain['scheme'] = $parts['scheme'];
        $this->domain['path'] = array_key_exists('path', $parts) ? $parts['path'] : null;
        $this->domain['sld'] = $split[0];
        $this->domain['tld'] = $split[1];

        return $this->domain;

    } // parseUrl


    /**
     * Make API Call
     * @param  string $command    
     * @param  array  $attributes 
     * @return void             
     */
    public function apiCall()
    {
       return $this->xmlResponse =  simplexml_load_file($this->getApiString());
    } // apiCall

    /**
     * Return last XML response code
     * @return int 
     */
    public function getLastResponseCode() 
    {
        return (int)$this->xmlResponse->RRPCode;
    } // getLastResponseCode

    /**
     * Return API url string
     * @return string 
     */
    public function getApiString() 
    {
        $urlString = config('enomapi.api_url');
        foreach($this->params as $key => $value)
        {
            $urlString .= "&{$key}={$value}";
        }
        return $this->setApiString($urlString);
    } // getApiString

    private function setApiString($string) 
    {
        $this->apiString = $string;
        return $this->apiString;
    } // setApiString

    /**
     * Gets the value of params.
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Checks for API Error
     * @return boolean 
     */
    public function hasError() 
    {
        return (int)$this->xmlResponse->ErrCount !== 0;
    } // hasError

    /**
     * Returns last API error message
     * @return string 
     */
    public function getLastError() 
    {
        return 'API Error: ' . $this->xmlResponse->errors->Err1;
    } // getLastError

    /**
     * Sets the value of params.
     *
     * @param mixed $params the params
     *
     * @return self
     */
    public function setParams($params)
    {       
        $this->params = $params;
        $this->params['uid'] = config('enomapi.enom_username');
        $this->params['pw'] = config('enomapi.enom_password');
        $this->params['responseType'] = array_key_exists('responseType', $params) ? $params['responseType'] : 'XML';
        $this->params['Display'] = array_key_exists('Display', $params) ? $params['Display'] : 100;
 
        return $this;
    }
}

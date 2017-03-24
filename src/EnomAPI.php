<?php namespace onethirtyone\enomapi;

use onethirtyone\enomapi\classes\Domain;

class EnomAPI {

    /**
     * url
     * @var array
     */
    protected $url = array();

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
     * API Url String
     * @var string
     */
    protected $urlString = null;

    /**
     * @var string
     */
    protected $xmlResponse;
 

    /**
     * Constructor
     */
    public function __construct() 
    {
       $this->params = ['uid' => config('enomapi.enom_username'),
                        'pw' => config('enomapi.enom_password'),
                        'responseType' => 'XML',
                        'Display' => 100
                        ];
    }
    /**
     * Instantiates domain related api functions
     * @return Domain
     */
    public function domain() 
    {
        return new Domain($this);
    } 

    /**
     * Parse url for API calls
     * @param  string $url 
     * @return void         
     */
    public function parseUrl($url) 
    {
        if(strpos($url,"://") === false && substr($url,0,1) != "/") $url =  "http://". $url;

        $parts = parse_url($url);
        $split = explode('.', str_replace('www.','',$parts['host']));

        return $this->setParam(['sld' => $split[0], 'tld' => $split[1]]);

    } // parseUrl

    /**
     * Make API Call
     * @return response
     */
    public function call($params = null)
    {
        if($params) 
        {
            $this->setParam($params);
        }

         return $this->xmlResponse = json_decode(json_encode(simplexml_load_file($this->getApiString())), TRUE);
    } // apiCall

    /**
     * Return last XML response code
     * @return int 
     */
    public function getLastResponseCode() 
    {
        return (int) $this->xmlResponse['RRPCode'];
    } // getLastResponseCode

    /**
     * Return API url string
     * @return string 
     */
    public function getApiString() 
    {
        return $this->urlString ?: $this->setApiString();
    } // getApiString

    /**
     * Sets the API URL String
     */
    private function setApiString() 
    {
        $this->urlString = config('enomapi.api_url');

        foreach($this->params as $key => $value)
        {
            $this->urlString .= "&{$key}={$value}";
        }

        return $this->urlString;
    } // setApiString

    /**
     * Checks for API Error
     * @return boolean 
     */
    public function hasError() 
    {
        return (int) $this->xmlResponse['ErrCount'] !== 0;
    } // hasError

    /**
     * Returns last API error message
     * @return string 
     */
    public function getLastError() 
    {
        return 'API Error: ' . $this->xmlResponse['errors']['Err1'];
    } // getLastError

    /**
     * Sets the value of params.
     *
     * @param mixed $params the params
     *
     * @return self
     */
    public function setParam($param, $value = null)
    {       
        if(is_array($param))
        {
          return  $this->params = array_replace($this->params, $param);
        }

        $this->params[$param] = $value;
        
        return  $this->params;
    }

    /**
     * Gets paramaters array
     * @return array 
     */
    public function getParams() 
    {
        return $this->params;
    }
}

<?php 

namespace onethirtyone\enomapi\classes;

class Domain {

	/**
	 * Enom API Class
	 * @var EnomAPI
	 */
	protected $api;

	/**
	 * Domain Available
	 * @var boolean
	 */
	protected $available;

	/**
	 * Constructor
	 * @param EnomAPI $api 
	 */
	public function __construct($api) 
	{
		$this->api = $api;
	}

	/**
	 * Check availablity of domain
	 * @param  string  $url 
	 * @return boolean      
	 */
	public function isAvailable($url) 
	{
		return $this->api->call(['command' => 'check'])->response()['RRPCode'] == 210 ? true : false;
	}

    /**
     * @param $url
     */
    public function purchase($url, $params = array())
	{
	    if(! array_has($params, ['NS1','NS2','NS3','UseDNS']))
        {
            $this->api->setParam('UseDNS','default');
        }

	    $this->api->parseUrl($url);
	    $params['command'] = 'purchase';

	    return $this->api->call($params);
	}

	/**
	 * Lists all unexpired domains for a reseller account
	 * @param  boolean $expired 
	 * @return array           
	 */
	public function list($expired = false) 
	{
		return $this->api->call(['command' => ($expired ? 'GetExpiredDomains' : 'GetDomains')])->response();
	}

	/**
	 * Lists expired domains for a reseller account
	 * @return array 
	 */
	public function listExpired() 
	{
		return $this->list(true);
	}

	/**
	 * Displays detailed information about a domain
	 * @param  string $url 
	 * @return array      
	 */
	public function info($url) 
	{
		$this->api->parseUrl($url);
		return $this->api->call(['command' => 'GetDomainInfo'])->response();
	}
}
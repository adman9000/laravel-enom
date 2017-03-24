<?php 

namespace onethirtyone\enomapi\classes;

class Domain {

	protected $api;
	protected $available;

	public function __construct($api) 
	{
		$this->api = $api;
	}

    /**
     * @param $url
     * @return int
     */
    public function check($url)
	{
		$this->api->parseUrl($url);
		$this->api->setParam(['command' =>'check']);
		return $this->api->call()['RRPCode'] == 210 ? 1 : 0;
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
	    $this->api->setParam('command','purchase');
	    $this->api->setParam($params);
	    return $this->api->getApiString();
	    return $this->api->call();
	}

	/**
	 * Lists all unexpired domains for a reseller account
	 * @param  boolean $expired 
	 * @return array           
	 */
	public function list($expired = false) 
	{
		return $this->api->call(['command' => ($expired ? 'GetExpiredDomains' : 'GetDomains')]);
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
		return $this->api->call(['command' => 'GetDomainInfo']);
	}





}
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
		$this->api->parseUrl($url);
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
	
	/**
	 * Displays status of domain order
	 * @param  string $url 
	 * @return array      
	 */
	public function getStatus($url) 
	{
		$this->api->parseUrl($url);
		return $this->api->call(['command' => 'GetDomainStatus'])->response();
	}

	/**
	 * Displays domain registration price
	 * @param  string $url 
	 * @return array      
	 */
	public function getPrice($url) 
	{
		$this->api->parseUrl($url);
         $this->api->setParam('ProductType','10');
		return $this->api->call(['command' => 'PE_GetProductPrice'])->response();
	}


	/**
	 * Get Domain hosts records (not inc mx records)
	 * @param  string $url 
	 * @return array      
	 */
	public function getRegHosts($url) {

		$this->api->parseUrl($url);
		return $this->api->call(['command' => 'GetRegHosts'])->response();

	}

	/**
	 * Set Domain hosts records
	 * @param  string $url 
	 * @param  array $hosts 
	 * @return array      
	 */
	public function setHosts($url, $hosts) {

		$this->api->parseUrl($url);

		$i = 0;
		foreach($hosts as $host) {
			$i++;
			$this->api->setParam("HostName".$i, $host['HostName']);
			$this->api->setParam("RecordType".$i, $host['RecordType']);
			$this->api->setParam("Address".$i, $host['Address']);
			if(isset($host['MXPref'])) $this->api->setParam("MXPref".$i, $host['MXPref']);
		}

		return $this->api->call(['command' => 'SetHosts'])->response();

	}

	/**
	 * Set nameservers
	 * @param  string $url 
	 * @param  array $nameservers 
	 * @return array      
	 */
	public function ModifyNS($url, $nameservers) {
		
		$this->api->parseUrl($url);

		$i = 0;
		foreach($nameservers as $nameserver) {
			$i++;
			$this->api->setParam("NS".$i, $nameserver);
		}

		return $this->api->call(['command' => 'ModifyNS'])->response();

	}
}
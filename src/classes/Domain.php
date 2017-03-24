<?php 

namespace onethirtyone\enomapi\classes;

class Domain {

	protected $api;
	protected $available;

	public function __construct($api) 
	{
		$this->api = $api;
	}

	public function check($url) 
	{
		$this->api->parseUrl($url);
		$this->api->setParam(['command' =>'check']);
		return $this->api->call();
		//return $this->api->call()->RRPCode == 210 ? 1 : 0;


	}

}
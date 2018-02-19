<?php
/**
 * @author  Adman9000 <myaddressistaken@googlemail.com>
 */
 
return [
	/**
	 * Your enom reseller username
	 */
	'enom_username'	=>	env('ENOM_USER'),


	/**
	 * Your enom reseller password
	 */
	'enom_password'	=>	env('ENOM_PASSWORD'),


	/**
	 * The URL for your API calls.  For local development use resellertest
	 */
	'api_url'	=>	env('APP_ENV') == 'local' 
					? 'http://resellertest.enom.com/interface.asp?' 
					: 'http://enom.com/interface.asp?',

];
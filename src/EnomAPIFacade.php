<?php 
/**
 * @author  Adman9000 <myaddressistaken@googlemail.com>
 */
 namespace adman9000\enom;


 
use Illuminate\Support\Facades\Facade;

class EnomAPIFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'enom-api';
	}
}
<?php namespace otostudios\enomapi;

use Illuminate\Support\Facades\Facade;

class EnomAPIFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'enom-api';
	}
}
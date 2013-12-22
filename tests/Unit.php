<?php

class Unit extends PHPUnit_Framework_TestCase {

	public function subject($params = array()) {
		$class = 'alfmarks\\' . str_replace('Test', '', get_called_class());
		return new $class($params);
	}

}
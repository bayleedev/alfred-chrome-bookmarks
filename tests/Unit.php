<?php

class Unit extends PHPUnit_Framework_TestCase {

	public function subject($params = array(), $options = array()) {
		$options += array(
			'mock' => false,
			'methods' => array(),
		);
		$class = 'alfmarks\\' . str_replace('Test', '', get_called_class());
		if ($options['mock']) {
			return $this->getMock($class, $options['methods'], $params);
		}
		return new $class($params);
	}

}
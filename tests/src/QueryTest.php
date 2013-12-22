<?php

use alfmarks\Query;

class QueryTest extends Unit {

	public function testSetsModel() {
		$this->assertEquals('foo', $this->subject(array('model' => 'foo'))->model);
	}

	public function testSetsTerm() {
		$this->assertEquals('foo', $this->subject(array('term' => 'foo'))->term);
	}

	public function testTerm() {
		$this->assertEquals('/foo/i', $this->subject(array('term' => 'foo'))->term());
	}

}
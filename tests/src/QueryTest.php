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
		$this->assertEquals('/.*?((f).*?(o).*?(o)).*?|.*?((f).*?(o)).*?|.*?((o).*?(o)).*?/i', $this->subject(array('term' => 'foo'))->regex());
	}

	public function testTermWithCharacters() {
		$this->assertEquals('/.*?((\]).*?(\[).*?(\^)).*?|.*?((\]).*?(\[)).*?|.*?((\[).*?(\^)).*?/i', $this->subject(array('term' => '][^'))->regex());
	}

}

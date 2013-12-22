<?php

use alfmarks\BookmarkCollection;

class BookmarkCollectionTest extends Unit {

	public function testReturnsEmptyItemArray() {
		$result = $this->subject()->to_xml();
		$expected = "<?xml version=\"1.0\"?>\n<items/>\n";
		$this->assertEquals($result, $expected);
	}

	public function callsToXmlOnNodes() {
		$stub = $this->getMockBuilder('stdclass')->getMock();
		$stub->expects($this->once())->method('to_xml');
		$this->subject(array($stub))->to_xml();
	}

}
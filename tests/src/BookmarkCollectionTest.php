<?php

use alfmarks\BookmarkCollection;

class BookmarkCollectionTest extends Unit {

	public function testReturnsEmptyItemArray() {
		$result = $this->subject()->to_xml();
		$expected = "<?xml version=\"1.0\"?>\n<items/>\n";
		$this->assertEquals($result, $expected);
	}

}

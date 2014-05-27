<?php

use alfmarks\BookmarkModel;

class BookmarkModelTest extends Unit {

	public function testToXml() {
		$result = $this->subject(array(
			'url' => 'http://google.com',
			'id' => '10',
			'name' => 'Google',
		))->to_xml()->asXML();
		$expected = '<item arg="http://google.com" uid="10"><title>Google</title><subtitle>http://google.com</subtitle></item>';
		$this->assertEquals($expected, $result);
	}

	public function testToXmlWithAmp() {
		$result = $this->subject(array(
			'url' => 'http://google.com?foo&bar',
			'id' => '10',
			'name' => 'Google',
		))->to_xml()->asXML();
		$expected = '<item arg="http://google.com?foo&amp;bar" uid="10"><title>Google</title><subtitle>http://google.com?foo&amp;bar</subtitle></item>';
		$this->assertEquals($expected, $result);
	}

}
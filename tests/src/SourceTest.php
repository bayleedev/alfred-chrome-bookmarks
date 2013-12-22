<?php

use org\bovigo\vfs\vfsStream;
use alfmarks\Source;
use alfmarks\Query;
use alfmarks\BookmarkModel;

class SourceTest extends Unit {

	public function createProfile($arr) {
		$this->root = vfsStream::setup('home');
		$file = vfsStream::url('home/profile.json');
		file_put_contents($file, json_encode($arr));
		return $file;
	}

	public function testRead() {
		$_SERVER['profile'] = $this->createProfile(array(
			array(
				'id' => 1,
				'name' => 'Google',
				'url' => 'http://google.com',
			),
			array(
				'id' => 2,
				'name' => 'Gmail',
				'url' => 'http://mail.google.com',
			),
			array(
				'id' => 3,
				'name' => 'Yahoo!',
				'url' => 'http://yahoo.com',
			),
		));
		$nodes = $this->subject()->read(new Query(array(
			'model' => 'alfmarks\BookmarkModel',
			'term' => 'Goo',
		)));
		$expected = array(
			new BookmarkModel(array(
				'id' => 1,
				'name' => 'Google',
				'url' => 'http://google.com',
			)),
			new BookmarkModel(array(
				'id' => 2,
				'name' => 'Gmail',
				'url' => 'http://mail.google.com',
			)),
		);
		$this->assertEquals($expected, $nodes);
	}

	public function testNormalizeGivesBackNodes() {
		$result = $this->subject()->normalize(range(1,2), function() {
			return 'yes';
		});
		$expected = array('yes');
		$this->assertEquals($expected, $result);
	}

}
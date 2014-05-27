<?php

namespace alfmarks;

use SimpleXMLElement;

class BookmarkCollection {

	public $nodes;

	public function __construct($nodes = array()) {
		$this->nodes = $nodes;
	}

	public function to_xml() {
		$document = new SimpleXMLElement('<items />');
		foreach ($this->nodes as $node) {
			$node->to_xml($document);
		}
		return $document->asXML();
	}
}

class BookmarkModel {

	public $data;

	public function __construct($data = array()) {
		$this->data = $data;
	}

	public function to_xml($parent = null) {
		if ($parent === null) {
			$parent = new SimpleXMLElement('<items />');
		}
		$item = $parent->addChild('item');
		$item->addAttribute('arg', $this->data['url']);
		$item->addAttribute('uid', $this->data['id']);
		$item->title = $this->data['name'];
		$item->subtitle = $this->data['url'];
		return $item;
	}

	public static function find($term) {
		$source = new Source();
		$data = $source->read(new Query(array('term' => $term, 'model' => __CLASS__)));
		return new BookmarkCollection($data);
	}

}

class Query {

	public $model;

	public $term;

	public function __construct($options = array()) {
		if (!empty($options['model'])) {
			$this->model = $options['model'];
		}
		if (!empty($options['term'])) {
			$this->term = $options['term'];
		}
	}

	/**
	 * Converts the term to a fuzzy regex.
	 *
	 * term('fo /O') => "/.*f.*o.*o.*\/i"
	 *
	 * @return string
	 */
	public function term() {
		// 'fo /O'.replace(/[^a-z]/ig, '').replace(/(.)/g, '.*$1') + '.*'
		return '/' . $this->term . '/i';
	}

}

class Source {

	public function read($query) {
		$file = $this->normalizeFile($_SERVER['PROFILE']);
		$json = json_decode(file_get_contents($file), true);
		return $this->normalizeData($json, function($obj) use($query) {
			if (preg_grep($query->term(), array_filter($obj, 'is_string'))) {
				return new $query->model($obj);
			}
			return null;
		});
	}

	public function normalizeFile($file) {
		return realpath(str_replace('~/', $_SERVER['HOME'] . '/', $file));
	}

	public function normalizeData($obj, $callback) {
		$nodes = array();
		if ($item = $callback($obj)) {
			$nodes[] = $item;
		}
		foreach ($obj as $value) {
			if (is_array($value)) {
				$nodes = array_merge($nodes, $this->normalizeData($value, $callback));
			}
		}
		return $nodes;
	}

}

// echo BookmarkModel::find($_SERVER['argv'][1])->to_xml();
<?php

namespace alfmarks;

use SimpleXMLElement;

class BookmarkCollection {

	public $nodes;

	public function __construct($nodes = array()) {
		$this->nodes = $nodes;
	}

	public function sort() {
		usort($this->nodes, function($a, $b) {
			return strcmp($b->score, $a->score);
		});
		return $this;
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

	public $score;

	public function __construct($data = array(), $score = 0) {
		$this->data = $data;
		$this->score = $score;
	}

	public function to_xml($parent = null) {
		if ($parent === null) {
			$parent = new SimpleXMLElement('<items />');
		}
		$item = $parent->addChild('item');
		$item->addAttribute('arg', $this->data['url']);
		$item->addAttribute('uid', $this->data['id'] . $this->score);
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

	public $regex;

	public $accuracy = 0.5;

	public function __construct($options = array()) {
		if (!empty($options['model'])) {
			$this->model = $options['model'];
		}
		if (!empty($options['term'])) {
			$this->term = $options['term'];
		}
	}

	public function multiScore(array $words) {
		$max = 0;
		foreach ($words as $word) {
			$max = max($max, $this->score($word));
		}
		return $max;
	}

	public function score($string) {
		preg_match($this->regex(), $string, $matches);
		$matches = array_values(array_filter($matches, 'strlen'));
		if (empty($matches)) return;
		$primary = strlen(implode('', array_slice($matches, 2))) / strlen($this->term);
		$secondary = abs(strlen($matches[1]) - strlen($this->term)) / 100;
		return $primary - $secondary;
	}

	public function regex() {
		return $this->regex = $this->regex ?: '/' . implode('|', array_map(function($el) {
			return implode('|', array_map(function($el) {
				$word = preg_replace('(.)', '.*?(\0)', $el) . '.*?';
				return preg_replace('/^(.{3})(.*)(.{3})$/', '\1(\2)\3', $word);
			}, $el));
		}, $this->grams())) . '/i';
	}

	public function grams() {
		$max = strlen($this->term);
		$min = ceil($this->accuracy * $max);
		$grams = array();
		foreach (range($max, $min) as $length) {
			$grams[$length] = $this->gramsByLength($length);
		}
		return $grams;
	}

	public function gramsByLength($length) {
		$ngrams = array();
		$stop = strlen($this->term) - $length;
		foreach (range(0, $stop) as $pos) {
			$ngrams[] = substr($this->term, $pos, $length);
		}
		return $ngrams;
	}

}

class Source {

	const MIN_MATCH = 0.25;

	public function read($query) {
		$file = $this->normalizeFile($_SERVER['PROFILE']);
		$json = json_decode(file_get_contents($file), true);
		$min = self::MIN_MATCH;
		return $this->normalizeData($json, function($obj) use($query, $min) {
			if (!isset($obj['url'], $obj['id'], $obj['name'])) return;
			$words = array_filter($obj, 'is_string');
			if (($score = $query->multiScore($words)) > $min) {
				return new $query->model($obj, $score);
			}
			return;
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

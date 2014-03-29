<?php

App::uses('AppHelper', 'View/Helper');

/**
 * @author Stefan Dickmann <stefan@php-engineer.de>
 */
class MetaHelper extends AppHelper {

	/**
	 * Helpers
	 *
	 * @var array
	 */
	public $helpers = array('Html');

	/**
	 * Meta
	 *
	 * @var array
	 */
	public $meta = array();

	/**
	 * Constructor
	 *
	 * @param View $View
	 * @param array $settings
	 */
	public function __construct(View $View, $settings = array()) {
		if (isset($View->viewVars['_meta'])) {
			$this->meta = (array)$View->viewVars['_meta'];
		}
		parent::__construct($View, $settings);
	}

	/**
	 * MetaHelper::render()
	 */
	public function render($name = null) {
		if ($name) {
			if (!isset($this->meta[$name])) {
				return null;
			}
			if ($name === 'title') {
				return $this->Html->tag(
					'title', $this->meta['title']
				);
			}
			return $this->Html->meta(array(
					'name' => $name,
					'content' => $this->meta[$name]
			));
		}
		foreach ($this->meta as $key => $value) {
			$meta[] = $this->render($key);
		}
		return isset($meta) ? implode(PHP_EOL, $meta) : null ;
	}
}

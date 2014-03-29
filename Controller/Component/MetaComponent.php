<?php

App::uses('Component', 'Controller');

/**
 * @author Stefan Dickmann <stefan@php-engineer.de>
 */
class MetaComponent extends Component {

	/**
	 * Default settings.
	 *
	 * @var array
	 */
	protected $_settings = array(
		'prefix' => 'meta',
		'cache' => true
	);

	/**
	 * Flag will be set to true after initialize. Necessary to
	 * be sure that cache engine could be loaded.
	 *
	 * @var boolean
	 */
	public $useCache = false;

	/**
	 * Constructor
	 *
	 * @param ComponentCollection $collection
	 * @param unknown $settings
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = Hash::merge($this->_settings, $settings);
		parent::__construct($collection, $settings);
	}

	/**
	 * @see Component::initialize()
	 */
	public function initialize(Controller $controller) {
		$this->Controller = $controller;
	}

	/**
	 * @see Component::startup()
	 */
	public function startup(Controller $controller) {
		$this->useCache = $this->_initCache();
	}

	/**
	 * @see Component::beforeRender()
	 */
	public function beforeRender(Controller $controller) {
		if (!($meta = $this->_readMetaCache())) {
			$methods = array(
				'action' => $this->settings['prefix'] . ucfirst($this->Controller->request->params['action']),
				'controller' => $this->settings['prefix'] . ucfirst($this->Controller->request->params['controller']),
				'fallback' => $this->settings['prefix'] . 'Fallback'
			);
			foreach ($methods as $method) {
				if (method_exists($controller, $method)) {
					$meta = $controller->$method();
					break;
				}
			}
		}
		if (!isset($meta) || !is_array($meta)) {
			throw new CakeException(sprintf(
				'Invalid type "%s" provided; must be of type "%s"', gettype($meta), 'array'
			));
		}
		$this->_writeMetaCache($meta);
		$this->Controller->set('_meta', $meta);
	}

	/**
	 * MetaComponent::_readMetaCache()
	 *
	 * @return false|array
	 */
	protected function _readMetaCache() {
		if ($this->useCache === true) {
			return Cache::read(
				$this->Controller->request->url,
				$this->settings['prefix']
			);
		}
		return false;
	}

	/**
	 * MetaComponent::_writeMetaCache()
	 *
	 * @return boolean
	 */
	protected function _writeMetaCache(array $meta = array()) {
		if ($this->useCache === true && !empty($meta)) {
			return Cache::write(
				$this->Controller->request->url,
				$meta, $this->settings['prefix']
			);
		}
		return false;
	}

	/**
	 * MetaComponent::_initCache()
	 *
	 * @return boolean
	 */
	protected function _initCache() {
		if (Cache::isInitialized($this->_settings['prefix'])) {
			return true;
		}
		if ($this->settings['cache'] === true) {
			return (bool)Cache::config(
				$this->settings['prefix'],
				array(
					'engine' => 'File',
					'duration' => '+2 minutes',
					'prefix' => $this->settings['prefix']
				)
			);
		}
		if (is_array($this->settings['cache'])) {
			return (bool)Cache::config(
				$this->settings['prefix'],
				$this->settings['cache']
			);
		}
		return false;
	}

}

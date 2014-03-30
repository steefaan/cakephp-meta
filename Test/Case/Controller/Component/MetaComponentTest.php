<?php

App::uses('MetaComponent', 'Meta.Controller/Component');
App::uses('Controller', 'Controller');

/**
 * @author Stefan Dickmann <stefan@php-engineer.de>
 */
class MetaComponentTest extends CakeTestCase {
	public $MetaComponent = null;
	public $Controller = null;

	/**
	 * @see CakeTestCase::setUp()
	 */
	public function setUp() {
		parent::setUp();

		$this->MetaComponent = new TestMetaComponent(
			new ComponentCollection()
		);
		$this->Controller = new TestMetaController(
			new CakeRequest('/test'), new CakeResponse()
		);
		$this->MetaComponent->initialize($this->Controller);
		$this->MetaComponent->startup($this->Controller);
	}

	/**
	 * MetaComponentTest::testReadMetaCacheFalse()
	 *
	 * @return void
	 */
	public function testReadMetaCacheFalse() {
		$this->MetaComponent->useCache = false;
		$result = $this->MetaComponent->readMetaCache();
		$this->assertFalse($result);
	}

	/**
	 * MetaComponentTest::testReadMetaCacheTrue()
	 *
	 * @return void
	 */
	public function testReadMetaCacheTrue() {
		$this->MetaComponent->writeMetaCache(array('test'));
		$result = $this->MetaComponent->readMetaCache();
		$this->assertTrue(is_array($result));
	}

	/**
	 * MetaComponentTest::testWriteMetaCacheFalse()
	 *
	 * @return void
	 */
	public function testWriteMetaCacheFalse() {
		$result = $this->MetaComponent->writeMetaCache(array());
		$this->assertFalse($result);

		$this->MetaComponent->useCache = false;
		$result = $this->MetaComponent->writeMetaCache(array('test'));
		$this->assertFalse($result);
	}

	/**
	 * MetaComponentTest::testWriteMetaCacheTrue()
	 *
	 * @return void
	 */
	public function testWriteMetaCacheTrue() {
		$result = $this->MetaComponent->writeMetaCache(array('test'));
		$this->assertTrue($result);
	}

	/**
	 * MetaComponentTest::testInitCacheFalse()
	 *
	 * @return void
	 */
	public function testInitCacheFalse() {
		Cache::drop($this->MetaComponent->settings['prefix']);
		$this->MetaComponent->settings['cache'] = false;
		$result = $this->MetaComponent->initCache();
		$this->assertFalse($result);
	}

	/**
	 * MetaComponentTest::testInitCacheTrue()
	 *
	 * @return void
	 */
	public function testInitCacheTrue() {
		Cache::drop($this->MetaComponent->settings['prefix']);
		$this->MetaComponent->settings['cache'] = true;
		$result = $this->MetaComponent->initCache();
		$this->assertTrue($result);
	}

	/**
	 * MetaComponentTest::testInitCacheConfig()
	 *
	 * @return void
	 */
	public function testInitCacheConfig() {
		Cache::drop($this->MetaComponent->settings['prefix']);
		$this->MetaComponent->settings['cache'] = array(
			'engine' => 'File',
		);
		$result = $this->MetaComponent->initCache();
		$this->assertTrue($result);
	}

	/**
	 * @see CakeTestCase::tearDown()
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->MetaComponent);
		unset($this->Controller);
	}
}

class TestMetaComponent extends MetaComponent {
	public function readMetaCache() {
		return $this->_readMetaCache();
	}
	public function writeMetaCache(array $meta = array()) {
		return $this->_writeMetaCache($meta);
	}
	public function initCache() {
		return $this->_initCache();
	}
}

class TestMetaController extends Controller {

}

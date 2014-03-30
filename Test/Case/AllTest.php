<?php

/**
 * @author Stefan Dickmann <stefan@php-engineer.de>
 */
class AllTest extends PHPUnit_Framework_TestSuite {

	/**
	 * Suite method, defines tests for this suite.
	 *
	 * @return void
	 */
	public static function suite() {
		$suite = new CakeTestSuite('All tests');
		$testCases = array(
			'Controller', 'View'
		);
		foreach ($testCases as $testCase) {
			$suite->addTestDirectoryRecursive(App::pluginPath('Meta') . 'Test' . DS . 'Case' . DS);
		}
		return $suite;
	}
}

<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Tests\Framework\Unit_Test_Case class
 *
 * @package WP_GDPR_Cookie_Notice
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Tests\Framework;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;

/**
 * Unit test case base class.
 */
class Unit_Test_Case extends TestCase {

	use MockeryPHPUnitIntegration;

	/**
	 * Sets up the environment before each test.
	 */
	protected function setUp() {
        parent::setUp();
        Monkey\setUp();
    }


	/**
	 * Tears down the environment after each test.
	 */
    protected function tearDown() {
        Monkey\tearDown();
        parent::tearDown();
    }
}

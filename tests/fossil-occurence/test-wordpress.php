<?php
/**
 * ./tests/fossil-occurence/test-wordpress.php
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/tests
 */

namespace myFOSSIL\Plugin\Specimen;

class FossilOccurenceWordPressTest extends Tests\myFOSSIL_Specimen_Test {

    /**
     * FossilOccurence object to test against.
     *
     * @since   0.0.1
     * @access  public
     * @var     \myFOSSIL\Plugin\Specimen\FossilOccurence $fossil
     */
    public $fossil;

    /**
     * Setup test environment.
     *
     * @since   0.0.1
     * @access  public
     */
    public function setUp()
    {
        parent::setUp();
        $this->fossil = new FossilOccurence;
    }

    /**
     * Test activation of the FossilOccurence object
     *
     * @since   0.0.1
     * @access  public
     */
    public function testWpActivateDeactivate()
    {
        // Assert that there is a table in there
        $this->assertContains( 'Created table ' . $this->fossil->table_name,
            $this->fossil->activate() );
    
        // Assert that we deleted no data
        $this->assertNull( $this->fossil->deactivate() );

        // Assert that we DID delete the table and data
        $this->assertTrue( $this->fossil->deactivate( true ) );

    }

}

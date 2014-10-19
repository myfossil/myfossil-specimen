<?php
/**
 * ./tests/fossil-occurence/test-crud.php
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/tests
 */

namespace myFOSSIL\Plugin\Specimen;

class FossilOccurenceCrudTest extends Tests\myFOSSIL_Specimen_Test {

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

        // Create temporary table and load some data
        $this->fossil = new FossilOccurence;
        $this->fossil->activate();
        $this->fossil->pbdbid = 1001;
        $this->fossil->name = "Loaded Fossil";
        $this->fossil->created_at = date( "Y-m-d H:i:s" );
        $this->fossil->save();
    }

    /**
     * Test activation of the FossilOccurence object
     *
     * @since   0.0.1
     * @access  public
     */
    public function tearDown()
    {
        parent::tearDown();

        // Assert that we DID delete the table and data
        $this->fossil->deactivate( true );
    }

    /**
     * Test creation of a new object.
     */
    public function testCreate()
    {
        // Create new Fossil
        $fossil = new FossilOccurence;
        $fossil->pbdbid = 1001;
        $fossil->name = "Test Fossil";

        global $wpdb;
        $tpl = "SELECT * FROM %s WHERE name = '%s'";
        $sql = sprintf( $tpl, $fossil->table_name, $fossil->name );

        // Assert that we don't exist yet
        $this->assertNull( $wpdb->get_row( $sql ) );

        // Assert that we actually saved something
        $fossil->save();
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        // Assert that we got the ID back
        $tpl = "SELECT id FROM %s WHERE name = '%s'";
        $sql = sprintf( $tpl, $fossil->table_name, $fossil->name );
        $this->assertEquals( (int) $fossil->id, (int) $wpdb->get_var( $sql ) );
    }

    /**
     * Test load of the FossilOccurence object in the database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testUpdate()
    {
        global $wpdb;
        $tpl = "SELECT %s FROM %s WHERE name = '%s'";

        // Assert that we at least exist in the database
        $sql = sprintf( $tpl, 'id', $this->fossil->table_name,
            $this->fossil->name );
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        // Change the name of the fossil...
        $this->fossil->name = "Updated Fossil";

        // Assert that we don't already exist in the database
        $sql = sprintf( $tpl, 'id', $this->fossil->table_name,
            $this->fossil->name );
        $this->assertNull( $wpdb->get_row( $sql ) );

        $this->fossil->save();
        $sql = sprintf( $tpl, 'id', $this->fossil->table_name,
            $this->fossil->name );
        $this->assertNotNull( $wpdb->get_row( $sql ) );
    }

    /**
     * Test load of the FossilOccurence object from the database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testLoad()
    {
        $local_fossil = new FossilOccurence;
        $local_fossil->id = $this->fossil->id;
        $local_fossil->load();

        foreach ( array( 'id', 'pbdbid', 'name', 'created_at' ) as $p )
            $this->assertEquals( $local_fossil->{ $p }, $this->fossil->{ $p } );
    }

    /**
     * Test delete of object.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testDelete()
    {
        $tpl = "SELECT * FROM %s WHERE id = %d";
        $sql = sprintf( $tpl, $this->fossil->table_name, $this->fossil->id );

        global $wpdb;
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        $this->fossil->delete();
        $this->assertNull( $wpdb->get_row( $sql ) );
    }
}

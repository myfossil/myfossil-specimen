<?php
/**
 * ./tests/geological/test-crud-interval.php
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/tests
 */

namespace myFOSSIL\Plugin\Specimen;

class GeologicalTimeIntervalCrudTest extends Tests\myFOSSIL_Specimen_Test {

    /**
     * GeologicalTimeInterval object to test against.
     *
     * @since   0.0.1
     * @access  public
     * @var     \mytime_interval\Plugin\Specimen\GeologicalTimeInterval $time_interval
     */
    public $time_interval;

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
        $this->time_interval = new GeologicalTimeInterval;
        $this->time_interval->activate();
        $this->time_interval->pbdbid = 1001;
        $this->time_interval->name = "Loaded time_interval";
        $this->time_interval->created_at = date( "Y-m-d H:i:s" );
        $this->time_interval->save();
    }

    /**
     * Test activation of the GeologicalTimeInterval object
     *
     * @since   0.0.1
     * @access  public
     */
    public function tearDown()
    {
        parent::tearDown();

        // Assert that we DID delete the table and data
        $this->time_interval->deactivate( true );
    }

    /**
     * Test creation of a new object.
     */
    public function testCreate()
    {
        // Create new time_interval
        $time_interval = new GeologicalTimeInterval;
        $time_interval->pbdbid = 1001;
        $time_interval->name = "Test time_interval";

        global $wpdb;
        $tpl = "SELECT * FROM %s WHERE name = '%s'";
        $sql = sprintf( $tpl, $time_interval->table_name, $time_interval->name );

        // Assert that we don't exist yet
        $this->assertNull( $wpdb->get_row( $sql ) );

        // Assert that we actually saved something
        $time_interval->save();
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        // Assert that we got the ID back
        $tpl = "SELECT id FROM %s WHERE name = '%s'";
        $sql = sprintf( $tpl, $time_interval->table_name, $time_interval->name );
        $this->assertEquals( (int) $time_interval->id, (int) $wpdb->get_var( $sql ) );
    }

    /**
     * Test load of the GeologicalTimeInterval object in the database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testUpdate()
    {
        global $wpdb;
        $tpl = "SELECT %s FROM %s WHERE name = '%s'";

        // Assert that we at least exist in the database
        $sql = sprintf( $tpl, 'id', $this->time_interval->table_name,
            $this->time_interval->name );
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        // Change the name of the time_interval...
        $this->time_interval->name = "Updated time_interval";

        // Assert that we don't already exist in the database
        $sql = sprintf( $tpl, 'id', $this->time_interval->table_name,
            $this->time_interval->name );
        $this->assertNull( $wpdb->get_row( $sql ) );

        $this->time_interval->save();
        $sql = sprintf( $tpl, 'id', $this->time_interval->table_name,
            $this->time_interval->name );
        $this->assertNotNull( $wpdb->get_row( $sql ) );
    }

    /**
     * Test load of the GeologicalTimeInterval object from the database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testLoad()
    {
        $local_time_interval = new GeologicalTimeInterval;
        $local_time_interval->id = $this->time_interval->id;
        $local_time_interval->load();

        foreach ( array( 'id', 'pbdbid', 'name', 'created_at' ) as $p )
            $this->assertEquals( $local_time_interval->{ $p }, $this->time_interval->{ $p } );
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
        $sql = sprintf( $tpl, $this->time_interval->table_name, $this->time_interval->id );

        global $wpdb;
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        $this->time_interval->delete();
        $this->assertNull( $wpdb->get_row( $sql ) );
    }
}

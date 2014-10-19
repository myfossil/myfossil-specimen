<?php
/**
 * ./tests/geological/test-crud-scale.php
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/tests
 */

namespace myFOSSIL\Plugin\Specimen;

class GeologicalTimeScaleCrudTest extends Tests\myFOSSIL_Specimen_Test {

    /**
     * GeologicalTimeScale object to test against.
     *
     * @since   0.0.1
     * @access  public
     * @var     \mytime_scale\Plugin\Specimen\GeologicalTimeScale $time_scale
     */
    public $time_scale;

    /**
     * Setup tests.
     *
     * @since   0.0.1
     * @access  public
     */
    public function setUp()
    {
        parent::setUp();

        // Create temporary table and load some data
        $this->time_scale = new GeologicalTimeScale;
        $this->time_scale->activate();
        $this->time_scale->pbdbid = 1001;
        $this->time_scale->name = "Loaded time_scale";
        $this->time_scale->created_at = date( "Y-m-d H:i:s" );
        $this->time_scale->save();
    }

    /**
     * Test activation of the GeologicalTimeScale object
     *
     * @since   0.0.1
     * @access  public
     */
    public function tearDown()
    {
        parent::tearDown();

        // Assert that we DID delete the table and data
        $this->time_scale->deactivate( true );
    }

    /**
     * Test creation of a new object.
     */
    public function testCreate()
    {
        // Create new time_scale
        $time_scale = new GeologicalTimeScale;
        $time_scale->pbdbid = 1001;
        $time_scale->name = "Test time_scale";

        global $wpdb;
        $tpl = "SELECT * FROM %s WHERE name = '%s'";
        $sql = sprintf( $tpl, $time_scale->table_name, $time_scale->name );

        // Assert that we don't exist yet
        $this->assertNull( $wpdb->get_row( $sql ) );

        // Assert that we actually saved something
        $time_scale->save();
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        // Assert that we got the ID back
        $tpl = "SELECT id FROM %s WHERE name = '%s'";
        $sql = sprintf( $tpl, $time_scale->table_name, $time_scale->name );
        $this->assertEquals( (int) $time_scale->id, (int) $wpdb->get_var( $sql ) );
    }

    /**
     * Test load of the GeologicalTimeScale object in the database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testUpdate()
    {
        global $wpdb;
        $tpl = "SELECT %s FROM %s WHERE name = '%s'";

        // Assert that we at least exist in the database
        $sql = sprintf( $tpl, 'id', $this->time_scale->table_name,
            $this->time_scale->name );
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        // Change the name of the time_scale...
        $this->time_scale->name = "Updated time_scale";

        // Assert that we don't already exist in the database
        $sql = sprintf( $tpl, 'id', $this->time_scale->table_name,
            $this->time_scale->name );
        $this->assertNull( $wpdb->get_row( $sql ) );

        $this->time_scale->save();
        $sql = sprintf( $tpl, 'id', $this->time_scale->table_name,
            $this->time_scale->name );
        $this->assertNotNull( $wpdb->get_row( $sql ) );
    }

    /**
     * Test load of the GeologicalTimeScale object from the database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testLoad()
    {
        $local_time_scale = new GeologicalTimeScale;
        $local_time_scale->id = $this->time_scale->id;
        $local_time_scale->load();

        foreach ( array( 'id', 'pbdbid', 'name', 'created_at' ) as $p )
            $this->assertEquals( $local_time_scale->{ $p }, $this->time_scale->{ $p } );
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
        $sql = sprintf( $tpl, $this->time_scale->table_name, $this->time_scale->id );

        global $wpdb;
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        $this->time_scale->delete();
        $this->assertNull( $wpdb->get_row( $sql ) );
    }
}

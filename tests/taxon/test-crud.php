<?php
/**
 * ./tests/taxon/test-crud.php
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/tests
 */

namespace myFOSSIL\Plugin\Specimen;

class TaxonCrudTest extends Tests\myFOSSIL_Specimen_Test {

    /**
     * Taxon object to test against.
     *
     * @since   0.0.1
     * @access  public
     * @var     \mytaxon\Plugin\Specimen\Taxon $taxon
     */
    public $taxon;

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
        $this->taxon = new Taxon;
        $this->taxon->activate();
        $this->taxon->pbdbid = 1001;
        $this->taxon->name = "Loaded taxon";
        $this->taxon->created_at = date( "Y-m-d H:i:s" );
        $this->taxon->save();
    }

    /**
     * Test activation of the Taxon object
     *
     * @since   0.0.1
     * @access  public
     */
    public function tearDown()
    {
        parent::tearDown();

        // Assert that we DID delete the table and data
        $this->taxon->deactivate( true );
    }

    /**
     * Test creation of a new object.
     */
    public function testCreate()
    {
        // Create new taxon
        $taxon = new Taxon;
        $taxon->pbdbid = 1001;
        $taxon->name = "Test taxon";

        global $wpdb;
        $tpl = "SELECT * FROM %s WHERE name = '%s'";
        $sql = sprintf( $tpl, $taxon->table_name, $taxon->name );

        // Assert that we don't exist yet
        $this->assertNull( $wpdb->get_row( $sql ) );

        // Assert that we actually saved something
        $taxon->save();
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        // Assert that we got the ID back
        $tpl = "SELECT id FROM %s WHERE name = '%s'";
        $sql = sprintf( $tpl, $taxon->table_name, $taxon->name );
        $this->assertEquals( (int) $taxon->id, (int) $wpdb->get_var( $sql ) );
    }

    /**
     * Test load of the Taxon object in the database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testUpdate()
    {
        global $wpdb;
        $tpl = "SELECT %s FROM %s WHERE name = '%s'";

        // Assert that we at least exist in the database
        $sql = sprintf( $tpl, 'id', $this->taxon->table_name,
            $this->taxon->name );
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        // Change the name of the taxon...
        $this->taxon->name = "Updated taxon";

        // Assert that we don't already exist in the database
        $sql = sprintf( $tpl, 'id', $this->taxon->table_name,
            $this->taxon->name );
        $this->assertNull( $wpdb->get_row( $sql ) );

        $this->taxon->save();
        $sql = sprintf( $tpl, 'id', $this->taxon->table_name,
            $this->taxon->name );
        $this->assertNotNull( $wpdb->get_row( $sql ) );
    }

    /**
     * Test load of the Taxon object from the database.
     *
     * @since   0.0.1
     * @access  public
     */
    public function testLoad()
    {
        $local_taxon = new Taxon;
        $local_taxon->id = $this->taxon->id;
        $local_taxon->load();

        foreach ( array( 'id', 'pbdbid', 'name', 'created_at' ) as $p )
            $this->assertEquals( $local_taxon->{ $p }, $this->taxon->{ $p } );
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
        $sql = sprintf( $tpl, $this->taxon->table_name, $this->taxon->id );

        global $wpdb;
        $this->assertNotNull( $wpdb->get_row( $sql ) );

        $this->taxon->delete();
        $this->assertNull( $wpdb->get_row( $sql ) );
    }
}

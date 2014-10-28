<?php
namespace myFOSSIL\Plugin\Specimen\Tests;

use myFOSSIL\Plugin\Specimen;

/**
 * Fossil class.
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/tests
 */
class FossilTest extends myFOSSIL_Specimen_Test {

    public function testSaveGetFossil() {
        $taxon = new Specimen\Taxon( null, array( 'common_name' => 'whale' ) );
        $location = new Specimen\FossilLocation( null, array( 'latitude' => 10.2, 'longitude' => 20.3 ) );
        $dimension = new Specimen\FossilDimension( null, array( 'length' => 10, 'width' => 20, 'height' => 30 ) );
        $reference = new Specimen\Reference( null, array( 'year' => 2014 ) );
        $stratum = new Specimen\Stratum( null, array( 'color' => '#c0c0c0' ) );
        $time_interval = new Specimen\TimeInterval( null, array( 'early_age' => 20, 'late_age' => 30 ) );

        foreach ( array( $taxon, $location, $dimension, $reference, $stratum,
                    $time_interval ) as $obj )
            $obj->save();

        $fossil = new Specimen\Fossil(
                null,
                array(
                    'taxon_id' => $taxon->id,
                    'location_id' => $location->id,
                    'dimension_id' => $dimension->id,
                    'reference_id' => $reference->id,
                    'stratum_id' => $stratum->id,
                    'time_interval_id' => $time_interval->id
                )
            );

        $this->assertGreaterThan( 0, $fossil->save() );

        $this->assertEquals( $taxon->common_name, $fossil->taxon->common_name );

        $this->assertEquals( $location->latitude, $fossil->location->latitude );
        $this->assertEquals( $location->longitude, $fossil->location->longitude );

        $this->assertEquals( $dimension->length, $fossil->dimension->length );
        $this->assertEquals( $dimension->width, $fossil->dimension->width );
        $this->assertEquals( $dimension->height, $fossil->dimension->height );

        $this->assertEquals( $dimension->length, $fossil->dim->length );
        $this->assertEquals( $dimension->width, $fossil->dim->width );
        $this->assertEquals( $dimension->height, $fossil->dim->height );

        $this->assertEquals( $reference->year, $fossil->reference->year );

        $this->assertEquals( $stratum->color, $fossil->stratum->color );

        $this->assertEquals( $time_interval->early_age, $fossil->time_interval->early_age );
        $this->assertEquals( $time_interval->late_age, $fossil->time_interval->late_age );
    }

}

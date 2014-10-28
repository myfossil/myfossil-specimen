<?php
namespace myFOSSIL\Plugin\Specimen\Tests;

use myFOSSIL\Plugin\Specimen\FossilDimension;

/**
 * FossilDimension class.
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/tests
 */
class FossilDimensionTest extends myFOSSIL_Specimen_Test {

    public $fossil_dimension;

    public function setUp() {
        parent::setUp();
        $this->fossil_dimension = new FossilDimension(
                null,
                array(
                    'length_meters' => 0.100,
                    'width_meters'  => 0.200,
                    'height_meters' => 0.300,
                )
            );
        $this->fossil_dimension->save();
    }

    public function testSaveDimension() {
        $fossil_dimension = new FossilDimension(
                null,
                array(
                    'length_meters' => 0.100,
                    'width_meters'  => 0.200,
                    'height_meters' => 0.300,
                )
            );
        $fossil_dimension->save();
        $this->assertGreaterThan( 0, $fossil_dimension->save() );
        $this->assertGreaterThan( 0, $fossil_dimension->wp_post->ID );
    }

    public function testGetDimension() {
        $dimension = new FossilDimension( $this->fossil_dimension->wp_post->ID );
        $this->assertEquals( $dimension->length, $this->fossil_dimension->length );
        $this->assertEquals( $dimension->width, $this->fossil_dimension->width );
        $this->assertEquals( $dimension->height, $this->fossil_dimension->height );
    }

    public function testGetDimensionAsCm() {
        $dimension = new FossilDimension( $this->fossil_dimension->wp_post->ID );
        $this->assertEquals( $dimension->length * 100, $dimension->as_cm( 'length' ) );
        $this->assertEquals( $dimension->width * 100, $dimension->as_cm( 'width' ) );
        $this->assertEquals( $dimension->height * 100, $dimension->as_cm( 'height' ) );
    }
}

<?php

namespace myFOSSIL\Plugin\Specimen\Tests;

use myFOSSIL\Plugin\Specimen\Stratum;
use myFOSSIL\Plugin\Specimen\FossilLithostratigraphy;

/**
 * FossilLithostratigraphy class.
 *
 * @author      Brandon Wood <bwood@atmoapps.com>
 * @package     myFOSSIL
 * @subpackage  myFOSSIL/tests
 */
class FossilLithostratigraphyTest extends myFOSSIL_Specimen_Test {

    public function testSaveFossilLithostratigraphy()
    {
        $stratum = new stratum;
        $fgeo = new FossilLithostratigraphy;
        $fgeo->stratum_id_group = $stratum->save();
        $this->assertGreaterThan( 0, $fgeo->save() );
        $this->assertGreaterThan( 0, $fgeo->wp_post->ID );
    }

    public function testGetFossilLithostratigraphy()
    {
        $stratum = new stratum;
        $stratum->name = "Test stratum";
        $fgeo = new FossilLithostratigraphy;
        $this->assertGreaterThan( 0, count( $fgeo->_meta_keys ) );
        foreach ( $fgeo->_meta_keys as $k ) {
            $fgeo->{ $k } = $stratum->save();
            $nfgeo = new FossilLithostratigraphy( $fgeo->save() );
            $this->assertEquals( $fgeo->{ $k }, $nfgeo->{ $k } );
            $this->assertGreaterThan( 0, $fgeo->{ $k } );
            $this->assertGreaterThan( 0, $nfgeo->{ $k } );
            $this->assertInternalType( 'int', $fgeo->{ $k } );
        }

        foreach ( FossilLithostratigraphy::get_ranks() as $k ) {
            $this->assertInstanceOf( 'myFOSSIL\Plugin\Specimen\stratum',
                $fgeo->{ $k } );
        }
    }

    public function testSetFossilLithostratigraphy()
    {
        $fgeo = new FossilLithostratigraphy;
        foreach ( FossilLithostratigraphy::get_ranks() as $k ) {
            $stratum = new stratum;
            $keys = array( 'level', 'color', 'late_age_ma', 'early_age_ma' );
            foreach ($keys as $k )
                $stratum->{ $k } = $k;
            $stratum->save();
            $fgeo->{ $k } = $stratum;
        }
        $nfgeo = new FossilLithostratigraphy( $fgeo->save() );

        foreach ( $fgeo->_meta_keys as $k ) {
            $fgeo->{ $k } = $stratum->save();
            $nfgeo = new FossilLithostratigraphy( $fgeo->save() );
            $this->assertEquals( $fgeo->{ $k }, $nfgeo->{ $k } );
            $this->assertGreaterThan( 0, $fgeo->{ $k } );
            $this->assertGreaterThan( 0, $nfgeo->{ $k } );
            $this->assertInternalType( 'int', $fgeo->{ $k } );
        }
        foreach ( FossilLithostratigraphy::get_ranks() as $k ) {
            $this->assertInstanceOf( 'myFOSSIL\Plugin\Specimen\stratum',
                $fgeo->{ $k } );
        }
    }
}

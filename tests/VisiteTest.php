<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Visite;

/**
 * Description of VisiteTest
 *
 * @author blemeill
 */
class VisiteTest extends TestCase{
    
    public function testGetDatecreationString() {
        $visite = new Visite();
        $visite->setDatecreation(new \DateTime("2024-04-24"));
        $this->assertEquals("24/04/2024", $visite->getDatecreationString());
    }
}

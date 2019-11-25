<?php

namespace tests\Supplier;

use PHPUnit\Framework\TestCase;
use Graphp\Util\SupplierUtil;
use Graphp\Util\Supplier;
use Graphp\Edge\DefaultEdge;
use Graphp\Edge\DefaultWeightedEdge;

class SupplierTest extends TestCase
{
    public function testDefaultEdgeSupplier(): void
    {
        $es = SupplierUtil::createDefaultEdgeSupplier();
        $this->assertInstanceOf(DefaultEdge::class, $es->get());
    }

    public function testDefaultWeightedEdgeSupplier(): void
    {
        $es = SupplierUtil::createDefaultWeightedEdgeSupplier();
        $this->assertInstanceOf(DefaultWeightedEdge::class, $es->get());
    }

    public function testEdgeSuppliers(): void
    {
        $es = SupplierUtil::createSupplier(DefaultEdge::class);
        $this->assertInstanceOf(DefaultEdge::class, $es->get());

        $es = SupplierUtil::createSupplier(DefaultWeightedEdge::class);
        $this->assertInstanceOf(DefaultWeightedEdge::class, $es->get());
    }

    public function testPrimitiveSuppliers(): void
    {
        $es = SupplierUtil::createIntegerSupplier();
        $this->assertEquals(0, $es->get());
        $this->assertEquals(1, $es->get());
        $this->assertEquals(2, $es->get());
        $this->assertTrue(is_int($es->get()));

        $es = SupplierUtil::createStringSupplier();
        $this->assertEquals('0', $es->get());
        $this->assertEquals('1', $es->get());
        $this->assertEquals('2', $es->get());
        $this->assertTrue(is_string($es->get()));
    }
}

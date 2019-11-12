<?php

namespace tests\edge;

use PHPUnit\Framework\TestCase;
use graphp\edge\DefaultEdge;
use graphp\edge\DefaultWeightedEdge;
use graphp\edge\EdgeArraySetFactory;
use graphp\edge\specifics\UniformEdgeSpecifics;
use graphp\edge\specifics\WeightedEdgeSpecifics;
use graphp\edge\EdgeSet;
use graphp\vertex\Vertex;

class EdgeBasicTest extends TestCase
{
    public function testEdgeGetHash(): void
    {
        $edge = new DefaultEdge();
        $this->assertNotNull($edge->getHash());

        $edge = new DefaultWeightedEdge();
        $this->assertNotNull($edge->getHash());
    }

    public function testEdgeEquals(): void
    {
        $edge1 = new DefaultEdge();
        $edge2 = new DefaultEdge();
        $this->assertTrue($edge1->equals($edge1));
        $this->assertFalse($edge1->equals($edge2));

        $edge1 = new DefaultWeightedEdge();
        $edge2 = new DefaultWeightedEdge();
        $this->assertTrue($edge1->equals($edge1));
        $this->assertFalse($edge1->equals($edge2));
    }

    public function testEdgeWeight(): void
    {
        $edge = new DefaultWeightedEdge();
        $edge->setWeight(10.0);
        $this->assertEquals(10.0, $edge->getWeight());
    }

    public function testEdgeSpecifics(): void
    {
        $es = new UniformEdgeSpecifics();
        $edge = new DefaultEdge();
        $this->assertFalse($es->containsEdge($edge));

        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $es->add($edge, $v1, $v2);
        $this->assertTrue($es->containsEdge($edge));
        $this->assertTrue($edge->equals($es->getEdge($edge)));

        $this->assertCount(1, $es->getEdgeSet());

        $this->assertTrue($v1->equals($es->getEdgeSource($edge)));
        $this->assertTrue($v2->equals($es->getEdgeTarget($edge)));
        
        $es->remove($edge);
        $this->assertCount(0, $es->getEdgeSet());

        $es = new WeightedEdgeSpecifics();
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $edge = new DefaultWeightedEdge();
        $es->add($edge, $v1, $v2);

        $es->setEdgeWeight($edge, 10.0);
        $this->assertEquals(10.0, $es->getEdgeWeight($edge));
    }

    public function testEdgeArraySetFactory(): void
    {
        $v = new Vertex(1);
        $ef = new EdgeArraySetFactory();
        $es = $ef->createEdgeSet($v);
        $this->assertTrue($es instanceof EdgeSet);
    }
}
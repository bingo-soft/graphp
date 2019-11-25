<?php

namespace tests\Edge;

use PHPUnit\Framework\TestCase;
use BadMethodCallException;
use Graphp\Edge\DefaultEdge;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Edge\EdgeArraySetFactory;
use Graphp\Edge\Specifics\UniformEdgeSpecifics;
use Graphp\Edge\Specifics\WeightedEdgeSpecifics;
use Graphp\Edge\EdgeSet;
use Graphp\Vertex\Vertex;

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
        $edge2 = new DefaultEdge();
        $this->assertFalse($es->containsEdge($edge));

        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $es->add($edge, $v1, $v2);
        $this->assertFalse($es->add($edge, $v1, $v2));
        $this->assertTrue($es->containsEdge($edge));
        $this->assertNotNull($es->getEdge($edge));
        $this->assertNull($es->getEdge($edge2));
        $this->assertTrue($edge->equals($es->getEdge($edge)));
        $this->assertEquals(1, $es->getEdgeWeight($edge));
       
        $this->assertCount(1, $es->getEdgeSet());

        $this->assertNotNull($es->getEdgeSource($edge));
        $this->assertNotNull($es->getEdgeTarget($edge));
        $this->assertTrue($v1->equals($es->getEdgeSource($edge)));
        $this->assertTrue($v2->equals($es->getEdgeTarget($edge)));
        $this->expectException(BadMethodCallException::class);
        $es->setEdgeWeight($edge, 10.0);
        
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

    public function testEdgePrint(): void
    {
        $es = new UniformEdgeSpecifics();
        $edge = new DefaultEdge();
        $this->assertFalse($es->containsEdge($edge));

        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $es->add($edge, $v1, $v2);

        $this->assertEquals("(1 : 2)", $edge->__toString());
        $this->assertEquals("(1 : 2)", (string) $edge);
    }
}

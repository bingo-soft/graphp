<?php

namespace tests\edge;

use PHPUnit\Framework\TestCase;
use graphp\graph\DefaultGraphType;
use graphp\graph\specifics\DirectedSpecifics;
use graphp\graph\specifics\UndirectedSpecifics;
use graphp\graph\graphs\DefaultDirectedGraph;
use graphp\edge\DefaultEdge;
use graphp\edge\DefaultWeightedEdge;
use graphp\vertex\Vertex;
use graphp\util\Supplier;

class DefaultDirectedGraphTest extends TestCase
{
    public function testDefaultDirectedGraphCreation(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $this->assertNotNull($graph);
        $this->assertTrue($graph instanceof DefaultDirectedGraph);
    }

    public function testVertexMethods(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);

        $v11 = $graph->addVertex($v1);
        $v21 = $graph->addVertex($v2);
        $this->assertNotNull($v11);
        $this->assertNull($graph->addVertex($v2));
        $this->assertTrue($graph->containsVertex($v1));
        $this->assertTrue($graph->containsVertex($v2));
        $this->assertFalse($graph->containsVertex($v3));
    }

    public function testEdgeMethods(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);

        $graph->addVertex($v1);
        $graph->addVertex($v2);

        $edge = $graph->addEdge($v1, $v2);

        $this->assertNotNull($edge);
        $this->assertNull($graph->addEdge($v1, $v2));

        $this->assertNotNull($graph->getEdge($v1, $v2));
        $this->assertNull($graph->getEdge($v2, $v1));

        $this->assertTrue($graph->getType() instanceof DefaultGraphType);

        $this->assertTrue($graph->containsEdge($v1, $v2));
        $this->assertTrue($graph->containsEdge(null, null, $edge));

        $this->assertCount(1, $graph->edgesOf($v1));
        $this->assertCount(1, $graph->edgesOf($v2));

        $this->assertCount(1, $graph->getAllEdges($v1, $v2));
        $this->assertCount(0, $graph->getAllEdges($v2, $v1));

        $this->assertNull($graph->getVertexSupplier());

        $this->assertCount(1, $graph->outgoingEdgesOf($v1));
        $this->assertCount(1, $graph->incomingEdgesOf($v2));
        $this->assertCount(0, $graph->outgoingEdgesOf($v2));
        $this->assertCount(0, $graph->incomingEdgesOf($v1));

        $this->assertCount(2, $graph->vertexSet());
        $this->assertCount(1, $graph->edgeSet());

        $this->assertNotNull($graph->getEdgeSupplier());
        $this->assertTrue($graph->getEdgeSupplier() instanceof Supplier);

        $this->assertTrue($v1->equals($graph->getEdgeSource($edge)));
        $this->assertFalse($v2->equals($graph->getEdgeSource($edge)));
        $this->assertTrue($v2->equals($graph->getEdgeTarget($edge)));
    }
}

<?php

namespace tests\edge;

use PHPUnit\Framework\TestCase;
use Graphp\Graph\DefaultGraphType;
use Graphp\Graph\Specifics\DirectedSpecifics;
use Graphp\Graph\Specifics\UndirectedSpecifics;
use Graphp\Graph\Types\DefaultDirectedGraph;
use Graphp\Graph\GraphUtils;
use Graphp\Edge\DefaultEdge;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Edge\Specifics\UniformEdgeSpecifics;
use Graphp\Edge\Specifics\WeightedEdgeSpecifics;
use Graphp\Vertex\Vertex;
use Graphp\Util\Supplier;

class DefaultDirectedGraphTest extends TestCase
{
    public function testDefaultDirectedGraphMethods(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $this->assertNotNull($graph);
        $this->assertTrue($graph instanceof DefaultDirectedGraph);

        $spec = $graph->createSpecifics(true);
        $this->assertTrue($spec instanceof DirectedSpecifics);

        $spec = $graph->createEdgeSpecifics(true);
        $this->assertTrue($spec instanceof WeightedEdgeSpecifics);

        $spec = $graph->createEdgeSpecifics(false);
        $this->assertTrue($spec instanceof UniformEdgeSpecifics);

        $this->assertNull($graph->getVertexSupplier());
        $this->assertTrue($graph->getType() instanceof DefaultGraphType);
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

        $graph->removeAllVertices($graph->vertexSet());
        $this->assertCount(0, $graph->vertexSet());

        $v11 = $graph->addVertex($v1);
        $this->assertCount(1, $graph->vertexSet());
        $this->assertTrue($graph->removeVertex($v1));
        $this->assertFalse($graph->removeVertex($v1));
        $this->assertCount(0, $graph->vertexSet());
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

        $graph->removeAllEdges($v1, $v2);
        $this->assertCount(0, $graph->edgeSet());

        $edge = $graph->addEdge($v1, $v2);
        $this->assertNotNull($graph->getEdge($v1, $v2));
        $graph->removeEdge($v1, $v2);
        $this->assertNull($graph->getEdge($v1, $v2));

        $edge = $graph->addEdge($v1, $v2);
        $this->assertNotNull($graph->getEdge($v1, $v2));
        $graph->removeEdge(null, null, $edge);
        $this->assertNull($graph->getEdge($v1, $v2));
    }
}

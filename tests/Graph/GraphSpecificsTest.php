<?php

namespace tests\Graph;

use PHPUnit\Framework\TestCase;
use Graphp\Graph\DefaultGraphType;
use Graphp\Graph\Specifics\DirectedSpecifics;
use Graphp\Graph\Specifics\DirectedEdgeContainer;
use Graphp\Graph\Specifics\UndirectedSpecifics;
use Graphp\Graph\Specifics\UndirectedEdgeContainer;
use Graphp\Graph\Types\DefaultDirectedGraph;
use Graphp\Graph\GraphUtils;
use Graphp\Edge\DefaultEdge;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Edge\Specifics\UniformEdgeSpecifics;
use Graphp\Edge\Specifics\WeightedEdgeSpecifics;
use Graphp\Vertex\Vertex;
use Graphp\Util\Supplier;

class GraphSpecificsTest extends TestCase
{
    public function testSpecificsVertexMethods(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $ds = new DirectedSpecifics($graph);
        $v1 = new Vertex(1);
        $ds->addVertex($v1);
        $this->assertCount(1, $ds->getVertexSet());
        $ds->removeVertex($v1);
        $this->assertCount(0, $ds->getVertexSet());

        $graph2 = new DefaultDirectedGraph(DefaultEdge::class);
        $ds2 = new UndirectedSpecifics($graph2);
        $v2 = new Vertex(1);
        $ds2->addVertex($v2);
        $this->assertCount(1, $ds2->getVertexSet());
        $ds2->removeVertex($v2);
        $this->assertCount(0, $ds2->getVertexSet());
    }

    public function testSpecificsEdgeMethods(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $ds = new DirectedSpecifics($graph);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $graph->addVertex($v1);
        $graph->addVertex($v2);
        $edge = $graph->addEdge($v1, $v2);
        $ds->addVertex($v1);
        $ds->addVertex($v2);
        $ds->addEdgeToTouchingVertices($edge);
        $this->assertCount(1, $ds->getAllEdges($v1, $v2));
        $this->assertNotNull($ds->getEdge($v1, $v2));
        $this->assertNull($ds->getEdge($v2, $v1));
        $this->assertCount(1, $ds->edgesOf($v1));
        $this->assertCount(1, $ds->edgesOf($v2));
        $this->assertCount(0, $ds->incomingEdgesOf($v1));
        $this->assertCount(1, $ds->outgoingEdgesOf($v1));
        $this->assertCount(1, $ds->incomingEdgesOf($v2));
        $this->assertCount(0, $ds->outgoingEdgesOf($v2));
        $this->assertEquals(0, $ds->inDegreeOf($v1));
        $this->assertEquals(1, $ds->outDegreeOf($v1));
        $this->assertEquals(1, $ds->degreeOf($v1));
        $this->assertEquals(1, $ds->inDegreeOf($v2));
        $this->assertEquals(1, $ds->degreeOf($v2));
        $this->assertEquals(0, $ds->outDegreeOf($v2));
        $ec = $ds->getEdgeContainer($v1);
        $this->assertTrue($ec instanceof DirectedEdgeContainer);

        $graph2 = new DefaultDirectedGraph(DefaultEdge::class);
        $ds2 = new UndirectedSpecifics($graph2);
        $v12 = new Vertex(1);
        $v22 = new Vertex(2);
        $graph2->addVertex($v12);
        $graph2->addVertex($v22);
        $edge2 = $graph2->addEdge($v12, $v22);
        $ds2->addVertex($v12);
        $ds2->addVertex($v22);
        $ds2->addEdgeToTouchingVertices($edge2);
        $this->assertNotNull($ds2->getEdge($v12, $v22));
        $this->assertNotNull($ds2->getEdge($v22, $v12));
        $this->assertCount(1, $ds2->edgesOf($v12));
        $this->assertCount(1, $ds2->edgesOf($v22));
        $this->assertCount(1, $ds2->incomingEdgesOf($v12));
        $this->assertCount(1, $ds2->outgoingEdgesOf($v12));
        $this->assertCount(1, $ds2->incomingEdgesOf($v22));
        $this->assertCount(1, $ds2->outgoingEdgesOf($v22));
        $this->assertEquals(1, $ds2->inDegreeOf($v12));
        $this->assertEquals(1, $ds2->degreeOf($v12));
        $this->assertEquals(1, $ds2->inDegreeOf($v22));
        $this->assertEquals(1, $ds2->degreeOf($v22));
        $this->assertEquals(1, $ds2->outDegreeOf($v12));
        $this->assertEquals(1, $ds2->outDegreeOf($v22));
        $ec2 = $ds2->getEdgeContainer($v12);
        $this->assertTrue($ec2 instanceof UndirectedEdgeContainer);
    }
}

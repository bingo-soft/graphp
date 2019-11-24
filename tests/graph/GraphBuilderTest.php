<?php

namespace tests\edge;

use PHPUnit\Framework\TestCase;
use Graphp\Graph\Builder\GraphBuilder;
use Graphp\Graph\Specifics\DirectedSpecifics;
use Graphp\Graph\Specifics\UndirectedSpecifics;
use Graphp\Graph\Types\DefaultDirectedGraph;
use Graphp\Graph\GraphUtils;
use Graphp\Edge\DefaultEdge;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Edge\Specifics\UniformEdgeSpecifics;
use Graphp\Edge\Specifics\WeightedEdgeSpecifics;
use Graphp\Vertex\Vertex;
use Graphp\Vertex\VertexSet;
use Graphp\Util\Supplier;

class GraphBuilderTest extends TestCase
{
    public function testGraphBuilderCreation(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $gb = new GraphBuilder($graph);
        $this->assertNotNull($gb);

        $build = $gb->build();
        $this->assertTrue($build instanceof DefaultDirectedGraph);
    }

    public function testGraphBuilderAddVertex(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $gb = new GraphBuilder($graph);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $gb->addVertex($v1);
        $this->assertCount(1, $graph->vertexSet());
        $gb->removeVertex($v1);
        $this->assertCount(0, $graph->vertexSet());
        $gb->addVertex($v1);
        $gb->addVertex($v2);
        $vs = new VertexSet();
        $vs[] = $v1;
        $vs[] = $v2;
        $gb->removeVertices($vs);
        $this->assertCount(0, $graph->vertexSet());
    }

    public function testGraphBuilderAddVertices(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $gb = new GraphBuilder($graph);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $vs = new VertexSet();
        $vs[] = $v1;
        $vs[] = $v2;
        $gb->addVertices($vs);
        $this->assertCount(2, $graph->vertexSet());
    }

    public function testGraphBuilderAddEdge(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $gb = new GraphBuilder($graph);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $gb->addEdge($v1, $v2);
        $this->assertCount(1, $graph->edgeSet());

        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $gb = new GraphBuilder($graph);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $edge = new DefaultEdge();
        $gb->addEdge($v1, $v2, $edge);
        $this->assertCount(1, $graph->edgeSet());
        $gb->removeEdge($v1, $v2);
        $this->assertCount(0, $graph->edgeSet());
        $gb->addEdge($v1, $v2, $edge);
        $gb->removeEdge($v1, $v2, $edge);
        $this->assertCount(0, $graph->edgeSet());
    }

    public function testGraphBuilderAddEdgeChain(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $gb = new GraphBuilder($graph);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        $v4 = new Vertex(3);
        $gb->addEdgeChain($v1, $v2, $v3, $v4);
        $this->assertCount(3, $graph->edgeSet());
    }

    public function testGraphBuilderAddGraph(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $graph->addVertex($v1);
        $graph->addVertex($v2);
        $graph->addEdge($v1, $v2);

        $graph2 = new DefaultDirectedGraph(DefaultEdge::class);
        $v12 = new Vertex(3);
        $v22 = new Vertex(4);
        $graph2->addVertex($v12);
        $graph2->addVertex($v22);
        $graph2->addEdge($v12, $v22);

        $gb = new GraphBuilder($graph);
        $gb->addGraph($graph2);
        $this->assertCount(2, $graph->edgeSet());
    }
}

<?php

namespace tests\edge;

use PHPUnit\Framework\TestCase;
use graphp\graph\DefaultGraphType;
use graphp\GraphUtils;
use graphp\graph\specifics\DirectedSpecifics;
use graphp\graph\specifics\UndirectedSpecifics;
use graphp\graph\types\DefaultDirectedGraph;
use graphp\edge\DefaultEdge;
use graphp\edge\DefaultWeightedEdge;
use graphp\vertex\Vertex;
use graphp\util\Supplier;

class GraphUtilsTest extends TestCase
{
    public function testAddEdge(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $edge = GraphUtils::addEdgeWithVertices($graph, $v1, $v2);
        $this->assertNotNull($edge);

        $edge = GraphUtils::addEdge($graph, $v1, $v2);
        $this->assertNull($edge);
    }

    public function testAddGraph(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $edge = GraphUtils::addEdgeWithVertices($graph, $v1, $v2);
        
        $graph2 = new DefaultDirectedGraph(DefaultEdge::class);
        $v3 = new Vertex(3);
        $v4 = new Vertex(4);
        $edge = GraphUtils::addEdgeWithVertices($graph2, $v3, $v4);

        $this->assertTrue(GraphUtils::addGraph($graph, $graph2));

        $this->assertCount(4, $graph->vertexSet());
        $this->assertCount(2, $graph->edgeSet());
    }

    public function testAddGraphReversed(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $edge = GraphUtils::addEdgeWithVertices($graph, $v1, $v2);
        
        $graph2 = new DefaultDirectedGraph(DefaultEdge::class);
        $v3 = new Vertex(3);
        $v4 = new Vertex(4);
        $edge = GraphUtils::addEdgeWithVertices($graph2, $v3, $v4);

        GraphUtils::addGraphReversed($graph, $graph2);

        $this->assertCount(4, $graph->vertexSet());
        $this->assertCount(2, $graph->edgeSet());
        $edge = $graph->edgeSet()[1];
        $this->assertTrue($v4->equals($edge->getSource()));
        $this->assertTrue($v3->equals($edge->getTarget()));
    }

    public function testSiblingsOf(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);

        $graph->addVertex($v1);
        $graph->addVertex($v2);
        $graph->addVertex($v3);

        $graph->addEdge($v1, $v2);
        $graph->addEdge($v2, $v3);

        $neighbors = GraphUtils::neighborsOf($graph, $v2);
        $this->assertCount(2, $neighbors);
        $neighbors = GraphUtils::neighborsOf($graph, $v1);
        $this->assertCount(1, $neighbors);
        $neighbors = GraphUtils::neighborsOf($graph, $v3);
        $this->assertCount(1, $neighbors);
        $this->assertTrue(GraphUtils::vertexHasSuccessors($graph, $v1));
        $this->assertTrue(GraphUtils::vertexHasSuccessors($graph, $v2));
        $this->assertFalse(GraphUtils::vertexHasSuccessors($graph, $v3));

        $predecessors = GraphUtils::predecessorsOf($graph, $v2);
        $this->assertCount(1, $predecessors);
        $predecessors = GraphUtils::predecessorsOf($graph, $v1);
        $this->assertCount(0, $predecessors);
        $predecessors = GraphUtils::predecessorsOf($graph, $v3);
        $this->assertCount(1, $predecessors);
        $this->assertFalse(GraphUtils::vertexHasPredecessors($graph, $v1));
        $this->assertTrue(GraphUtils::vertexHasPredecessors($graph, $v2));
        $this->assertTrue(GraphUtils::vertexHasPredecessors($graph, $v3));
    }

    public function testIncidence(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);

        $graph->addVertex($v1);
        $graph->addVertex($v2);
        $graph->addVertex($v3);

        $edge1 = $graph->addEdge($v1, $v2);
        $edge2 = $graph->addEdge($v2, $v3);

        $this->assertTrue(GraphUtils::testIncidence($graph, $edge1, $v1));
        $this->assertTrue(GraphUtils::testIncidence($graph, $edge1, $v2));
        $this->assertFalse(GraphUtils::testIncidence($graph, $edge1, $v3));
        $this->assertFalse(GraphUtils::testIncidence($graph, $edge2, $v1));
        $this->assertTrue(GraphUtils::testIncidence($graph, $edge2, $v2));
        $this->assertTrue(GraphUtils::testIncidence($graph, $edge2, $v3));
    }

    public function testGetOpposite(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);

        $graph->addVertex($v1);
        $graph->addVertex($v2);
        $graph->addVertex($v3);

        $edge1 = $graph->addEdge($v1, $v2);
        $edge2 = $graph->addEdge($v2, $v3);
        $this->assertTrue($v2->equals(GraphUtils::getOppositeVertex($graph, $edge1, $v1)));
        $this->assertTrue($v1->equals(GraphUtils::getOppositeVertex($graph, $edge1, $v2)));
        $this->assertTrue($v2->equals(GraphUtils::getOppositeVertex($graph, $edge2, $v3)));
        $this->assertTrue($v3->equals(GraphUtils::getOppositeVertex($graph, $edge2, $v2)));
    }

    public function testRemoveVertexAndPreserveConnectivity(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        $v4 = new Vertex(4);

        $graph->addVertex($v1);
        $graph->addVertex($v2);
        $graph->addVertex($v3);
        $graph->addVertex($v4);

        $edge1 = $graph->addEdge($v1, $v2);
        $edge2 = $graph->addEdge($v2, $v3);
        $edge3 = $graph->addEdge($v3, $v4);

        GraphUtils::removeVertexAndPreserveConnectivity($graph, $v2, $v3);
        $edges = $graph->edgeSet();
        $this->assertCount(1, $edges);
        $edge = $edges[array_keys($edges->getArrayCopy())[0]];
        $this->assertTrue($v1->equals($edge->getSource()));
        $this->assertTrue($v4->equals($edge->getTarget()));
    }
      
    public function testAddOutgoingEdges(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        $v4 = new Vertex(4);

        $graph->addVertex($v1);
        $graph->addVertex($v2);
        $graph->addEdge($v1, $v2);
        GraphUtils::addOutgoingEdges($graph, $v2, $v3, $v4);
        $this->assertCount(3, $graph->edgeSet());
        $this->assertCount(3, GraphUtils::neighborsOf($graph, $v2));
    }

    public function testAddIncomingEdges(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        $v4 = new Vertex(4);
        $v5 = new Vertex(5);

        $graph->addVertex($v1);
        $graph->addVertex($v2);
        $graph->addEdge($v1, $v2);
        GraphUtils::addIncomingEdges($graph, $v2, $v3, $v4, $v5);
        $this->assertCount(4, $graph->edgeSet());
        $this->assertCount(4, GraphUtils::neighborsOf($graph, $v2));
    }
}

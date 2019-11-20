<?php

namespace tests\path;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use graphp\GraphUtils;
use graphp\graph\types\{
    Pseudograph,
    SimpleGraph,
    SimpleWeightedGraph,
    SimpleDirectedGraph,
    SimpleDirectedWeightedGraph
};
use graphp\edge\DefaultEdge;
use graphp\edge\DefaultWeightedEdge;
use graphp\vertex\Vertex;
use graphp\vertex\VertexSet;
use graphp\path\GraphWalk;

class GraphWalkTest extends TestCase
{
    public function testInvalidPath1(): void
    {
        $graph = new Pseudograph(DefaultEdge::class);
        $vertex = new Vertex(0);
        $graph->addVertex($vertex);
        $graph->addEdge($vertex, $vertex);
        // Invalid: the path's vertexList and edgeList cannot both be empty
        $this->expectException(InvalidArgumentException::class);
        new GraphWalk($graph, $vertex, $vertex, null, null, 0);
    }

    public function testInvalidPath2(): void
    {
        $graph = new SimpleGraph(DefaultEdge::class);
        $vertex = new Vertex(0);
        $graph->addVertex($vertex);
        // Invalid: The graph does not contain a self loop from 0 to 0.
        $gw = new GraphWalk($graph, $vertex, $vertex, [$vertex, $vertex], null, 0);
        $this->expectException(InvalidArgumentException::class);
        $gw->verify();
    }

    public function testInvalidPath3(): void
    {
        $graph = new SimpleGraph(DefaultEdge::class);
        $v0 = new Vertex(0);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        GraphUtils::addAllVertices($graph, new VertexSet([$v0, $v1, $v2, $v3]));
        $graph->addEdge($v0, $v1);
        $graph->addEdge($v1, $v2);
        $graph->addEdge($v2, $v3);
        // Invalid: The vertexList does not constitute to a feasible path. Edge (1,3) does not exist in the graph.
        $gw = new GraphWalk($graph, $v0, $v2, [$v0, $v1, $v3, $v2], null, 0);
        $this->expectException(InvalidArgumentException::class);
        $gw->verify();
    }

    public function testInvalidPath4(): void
    {
        $graph = new SimpleGraph(DefaultEdge::class);
        $v0 = new Vertex(0);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        GraphUtils::addAllVertices($graph, new VertexSet([$v0, $v1, $v2, $v3]));
        $e1 = $graph->addEdge($v0, $v1);
        $graph->addEdge($v1, $v2);
        $e3 = $graph->addEdge($v2, $v3);
        // Invalid: The edgeList does not constitute to a feasible path. Conflicting edge: (2 : 3)
        $gw = new GraphWalk($graph, $v0, $v2, null, [$e1, $e3], 0);
        $this->expectException(InvalidArgumentException::class);
        $gw->verify();
    }

    public function testValidPaths(): void
    {
        $graph = new SimpleGraph(DefaultEdge::class);
        $v0 = new Vertex(0);
        $graph->addVertex($v0);

        // empty path
        $gw1 = new GraphWalk($graph, null, null, [], [], 0);
        $this->assertTrue($gw1->verify());
        
        $gw2 = new GraphWalk($graph, null, null, null, [], 0);
        $this->assertTrue($gw2->verify());

        $gw3 = new GraphWalk($graph, null, null, [], null, 0);
        $this->assertTrue($gw3->verify());

        // singleton path
        $gw4 = new GraphWalk($graph, $v0, $v0, [$v0], [], 0);
        $this->assertTrue($gw4->verify());
    }

    public function testEmptyPath(): void
    {
        $graph = new SimpleGraph(DefaultEdge::class);
        $path = new GraphWalk($graph, null, null, [], [], 0);
        $this->assertEquals(0, $path->getLength());
        $this->assertEquals([], $path->getVertexList());
        $this->assertEquals([], $path->getEdgeList());
        $this->assertTrue($path->isEmpty());
        $this->assertEquals(GraphWalk::emptyWalk($graph), $path);
    }

    public function testReversePathUndirected(): void
    {
        $graph = new SimpleWeightedGraph(DefaultWeightedEdge::class);
        $v0 = new Vertex(0);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        GraphUtils::addAllVertices($graph, new VertexSet([$v0, $v1, $v2, $v3]));
        $e1 = GraphUtils::addEdge($graph, $v0, $v1, 2);
        $e2 = GraphUtils::addEdge($graph, $v1, $v2, 3);
        $e3 = GraphUtils::addEdge($graph, $v2, $v3, 4);

        $gw1 = new GraphWalk($graph, $v0, $v3, [$v0, $v1, $v2, $v3], null, 9);
        $gw2 = new GraphWalk($graph, $v0, $v3, null, [$e1, $e2, $e3], 9);

        $rev1 = $gw1->reverse();
        $this->assertTrue($rev1->verify());
        $rev2 = $gw2->reverse();
        $this->assertTrue($rev2->verify());

        $revPath = new GraphWalk($graph, $v3, $v0, null, [$e3, $e2, $e1], 9);
        $this->assertTrue($revPath->equals($rev1));
        $this->assertTrue($revPath->equals($rev2));

        $rev1 = $gw1->reverse();
        $this->assertEquals(9.0, $gw1->getWeight());
        $rev2 = $gw2->reverse();
        $this->assertEquals(9.0, $gw2->getWeight());
    }

    public function testReverseInvalidPathDirected(): void
    {
        $graph = new SimpleDirectedGraph(DefaultEdge::class);
        $v0 = new Vertex(0);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        GraphUtils::addAllVertices($graph, new VertexSet([$v0, $v1, $v2, $v3]));
        $graph->addEdge($v0, $v1);
        $graph->addEdge($v1, $v2);
        $graph->addEdge($v2, $v3);

        $gw1 = new GraphWalk($graph, $v0, $v3, [$v0, $v1, $v2, $v3], null, 0);
        // Invalid: this walk cannot be reversed. The graph does not contain a reverse arc for arc (2 : 3)
        $this->expectException(InvalidArgumentException::class);
        $gw1->reverse();
    }

    public function testReversePathDirected(): void
    {
        $graph = new SimpleDirectedWeightedGraph(DefaultWeightedEdge::class);
        $v0 = new Vertex(0);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        GraphUtils::addAllVertices($graph, new VertexSet([$v0, $v1, $v2, $v3]));
        GraphUtils::addEdge($graph, $v0, $v1, 1);
        GraphUtils::addEdge($graph, $v1, $v2, 2);
        GraphUtils::addEdge($graph, $v2, $v3, 3);

        $e1 = GraphUtils::addEdge($graph, $v3, $v2, 4);
        $e2 = GraphUtils::addEdge($graph, $v2, $v1, 5);
        $e3 = GraphUtils::addEdge($graph, $v1, $v0, 6);

        $gw1 = new GraphWalk($graph, $v0, $v3, [$v0, $v1, $v2, $v3], null, 0);
        $rev1 = $gw1->reverse();
        $this->assertTrue($rev1->verify());
        
        $revPath = new GraphWalk($graph, $v3, $v0, null, [$e1, $e2, $e3], 15);

        $this->assertTrue($revPath->equals($rev1));
        $this->assertEquals(15, $rev1->getWeight());

        $rev2 = $gw1->reverse();
        $this->assertEquals(15, $rev2->getWeight());
    }

    public function testIllegalConcatPath1(): void
    {
        $graph = new SimpleDirectedWeightedGraph(DefaultEdge::class);
        $v0 = new Vertex(0);
        $graph->addVertex($v0);
        $gw1 = GraphWalk::emptyWalk($graph);
        $gw2 = GraphWalk::singletonWalk($graph, $v0, 10);
        // Invalid: An empty path cannot be extended
        $this->expectException(InvalidArgumentException::class);
        $gw1->concat($gw2);
    }

    public function testIllegalConcatPath2(): void
    {
        $graph = new SimpleDirectedWeightedGraph(DefaultEdge::class);
        $v0 = new Vertex(0);
        $v1 = new Vertex(1);
        $graph->addVertex($v0);
        $graph->addVertex($v1);
        $gw1 = GraphWalk::singletonWalk($graph, $v0, 10);
        $gw2 = GraphWalk::singletonWalk($graph, $v1, 12);
        // Invalid: Cannot concat two paths which do not end/start at the same vertex
        $this->expectException(InvalidArgumentException::class);
        $gw1->concat($gw2);
    }

    public function testConcatPath1(): void
    {
        $graph = new SimpleDirectedWeightedGraph(DefaultEdge::class);
        $v0 = new Vertex(0);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        GraphUtils::addAllVertices($graph, new VertexSet([$v0, $v1, $v2, $v3]));
        $graph->addEdge($v0, $v1);
        $graph->addEdge($v1, $v2);
        $e3 = $graph->addEdge($v2, $v3);
        $e4 = $graph->addEdge($v3, $v1);
        $gw1 = new GraphWalk($graph, $v0, $v2, [$v0, $v1, $v2], null, 5);
        $gw2 = new GraphWalk($graph, $v2, $v1, null, [$e3, $e4], 7);
        $gw3 = $gw1->concat($gw2);
        $this->assertTrue($gw3->verify());

        $expected = new GraphWalk($graph, $v0, $v1, [$v0, $v1, $v2, $v3, $v1], null, 12);
        $this->assertTrue($expected->equals($gw3));
        $this->assertEquals(12.0, $gw3->getWeight());
    }

    public function testConcatPathWithSingleton(): void
    {
        $graph = new SimpleDirectedWeightedGraph(DefaultEdge::class);
        $v0 = new Vertex(0);
        $v1 = new Vertex(1);
        GraphUtils::addAllVertices($graph, new VertexSet([$v0, $v1]));
        $graph->addEdge($v0, $v1);
        $gw1 = new GraphWalk($graph, $v0, $v1, [$v0, $v1], null, 5);
        $gw2 = GraphWalk::singletonWalk($graph, $v1, 10);
        $gw3 = $gw1->concat($gw2);
        $this->assertTrue($gw3->verify());
        // Concatenation with singleton shouldn't result in a different path.
        $this->assertTrue($gw1->equals($gw3));
    }
}

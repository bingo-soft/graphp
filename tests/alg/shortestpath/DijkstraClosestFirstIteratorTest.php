<?php

namespace tests\alg\shortestpath;

use PHPUnit\Framework\TestCase;
use Graphp\GraphUtils;
use Graphp\Graph\Types\WeightedPseudograph;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Vertex\Vertex;
use Graphp\Vertex\VertexSet;
use Graphp\Alg\Shortestpath\DijkstraClosestFirstIterator;

class DijkstraClosestFirstIteratorTest extends TestCase
{
    public function test(): void
    {
        $graph = new WeightedPseudograph(DefaultWeightedEdge::class);
        $v1 = new Vertex("1");
        $v2 = new Vertex("2");
        $v3 = new Vertex("3");
        $v4 = new Vertex("4");
        $v5 = new Vertex("5");
        GraphUtils::addAllVertices($graph, new VertexSet([$v1, $v2, $v3, $v4, $v5]));
        $graph->setEdgeWeight($graph->addEdge($v1, $v2), 2.0);
        $graph->setEdgeWeight($graph->addEdge($v1, $v3), 3.0);
        $graph->setEdgeWeight($graph->addEdge($v1, $v5), 100.0);
        $graph->setEdgeWeight($graph->addEdge($v2, $v4), 5.0);
        $graph->setEdgeWeight($graph->addEdge($v3, $v4), 20.0);
        $graph->setEdgeWeight($graph->addEdge($v4, $v5), 5.0);

        $it = new DijkstraClosestFirstIterator($graph, $v3);
    }
}

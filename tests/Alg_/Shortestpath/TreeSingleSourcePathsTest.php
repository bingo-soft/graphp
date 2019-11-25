<?php

namespace tests\Alg\Shortestpath;

use PHPUnit\Framework\TestCase;
use Graphp\GraphUtils;
use Graphp\Graph\Types\DirectedWeightedPseudograph;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Vertex\Vertex;
use Graphp\Vertex\VertexSet;
use Graphp\Alg\Shortestpath\TreeSingleSourcePaths;

class TreeSingleSourcePathsTest extends TestCase
{
    public function test(): void
    {
        $graph = new DirectedWeightedPseudograph(DefaultWeightedEdge::class);
        $v1 = new Vertex(1);
        $v2 = new Vertex(2);
        $v3 = new Vertex(3);
        $v4 = new Vertex(4);
        GraphUtils::addAllVertices($graph, new VertexSet([$v1, $v2, $v3, $v4]));
        $e12_1 = $graph->addEdge($v1, $v2);
        $graph->setEdgeWeight($e12_1, -5.0);
        $e12_2 = $graph->addEdge($v1, $v2);
        $graph->setEdgeWeight($e12_2, -2.0);
        $e12_3 = $graph->addEdge($v1, $v2);
        $graph->setEdgeWeight($e12_3, 1.0);
        $e23_1 = $graph->addEdge($v2, $v3);
        $graph->setEdgeWeight($e23_1, 0.0);
        $e23_2 = $graph->addEdge($v2, $v3);
        $graph->setEdgeWeight($e23_2, -2.0);
        $e23_3 = $graph->addEdge($v2, $v3);
        $graph->setEdgeWeight($e23_3, -5.0);
        $e34_1 = $graph->addEdge($v3, $v4);
        $graph->setEdgeWeight($e34_1, -100.0);
        $e34_2 = $graph->addEdge($v3, $v4);
        $graph->setEdgeWeight($e34_2, 100.0);
        $e34_3 = $graph->addEdge($v3, $v4);
        $graph->setEdgeWeight($e34_3, 1.0);

        $map = [
            $v2->getHash() => [-5.0, $e12_1],
            $v3->getHash() => [-10.0, $e23_3],
            $v4->getHash() => [-110.0, $e34_1]
        ];
        
        $t1 = new TreeSingleSourcePaths($graph, $v1, $map);

        $this->assertEquals(1, $t1->getSourceVertex()->getValue());
        $this->assertEquals(0.0, $t1->getWeight($v1));
        $this->assertTrue(empty($t1->getPath($v1)->getEdgeList()));
        $this->assertEquals([$graph->getEdgeSource($e12_1)], $t1->getPath($v1)->getVertexList());
        $this->assertEquals(-5.0, $t1->getWeight($v2));
        $this->assertEquals([$e12_1], $t1->getPath($v2)->getEdgeList());
        $this->assertEquals(-10.0, $t1->getWeight($v3));
        $this->assertEquals([$e12_1, $e23_3], $t1->getPath($v3)->getEdgeList());
        $this->assertEquals(-110.0, $t1->getWeight($v4));
        $this->assertEquals([$e12_1, $e23_3, $e34_1], $t1->getPath($v4)->getEdgeList());
    }
}

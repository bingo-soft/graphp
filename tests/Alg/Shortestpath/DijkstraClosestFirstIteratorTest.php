<?php

namespace tests\Alg\Shortestpath;

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
        $this->assertEquals($v3, $it->next());
        $this->assertEquals($v1, $it->next());
        $this->assertEquals($v2, $it->next());
        $this->assertEquals($v4, $it->next());
        $this->assertEquals($v5, $it->next());
        $this->assertFalse($it->hasNext());

        $it1 = new DijkstraClosestFirstIterator($graph, $v1);
        $this->assertEquals($v1, $it1->next());
        $this->assertEquals($v2, $it1->next());
        $this->assertEquals($v3, $it1->next());
        $this->assertEquals($v4, $it1->next());
        $this->assertEquals($v5, $it1->next());
        $this->assertFalse($it1->hasNext());

        $it2 = new DijkstraClosestFirstIterator($graph, $v1, 11.0);
        $this->assertEquals("1", $it2->next());
        $this->assertEquals("2", $it2->next());
        $this->assertEquals("3", $it2->next());
        $this->assertEquals("4", $it2->next());
        $this->assertFalse($it2->hasNext());

        $it3 = new DijkstraClosestFirstIterator($graph, $v3, 12.0);
        $this->assertEquals($v3, $it3->next());
        $this->assertEquals($v1, $it3->next());
        $this->assertEquals($v2, $it3->next());
        $this->assertEquals($v4, $it3->next());
        $this->assertFalse($it3->hasNext());

        $paths3 = $it3->getPaths();
        $this->assertEquals(10.0, $paths3->getPath($v4)->getWeight());
        $this->assertEquals(5.0, $paths3->getPath($v2)->getWeight());
        $this->assertEquals(3.0, $paths3->getPath($v1)->getWeight());
    }
}

<?php

namespace tests\alg\shortestpath;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use Graphp\GraphInterface;
use Graphp\GraphUtils;
use Graphp\Graph\Types\DirectedWeightedPseudograph;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Vertex\VertexInterface;
use Graphp\Vertex\Vertex;
use Graphp\Vertex\VertexSet;
use Graphp\Alg\Shortestpath\DijkstraShortestPath;

class DijkstraShortestPathTest extends ShortestPathTest
{
    public function testConstructor(): void
    {
        $g = $this->create();

        $path = (new DijkstraShortestPath($g, INF))->getPath(self::$V3, self::$V4);
        $this->assertEquals([$this->e13, $this->e12, $this->e24], $path->getEdgeList());
        $this->assertEquals(10.0, $path->getWeight());

        $path = (new DijkstraShortestPath($g, 7.0))->getPath(self::$V3, self::$V4);
        $this->assertNull($path);
    }

    public function findPathBetween(GraphInterface $g, VertexInterface $src, VertexInterface $dest): array
    {
        return (new DijkstraShortestPath($g))->getPath($src, $dest)->getEdgeList();
    }

    public function testShortestPathTree(): void
    {
        $g = new DirectedWeightedPseudograph(DefaultWeightedEdge::class);
        GraphUtils::addAllVertices($g, new VertexSet([self::$V1, self::$V2, self::$V3, self::$V4, self::$V5]));

        $we12 = $g->addEdge(self::$V1, self::$V2);
        $we24 = $g->addEdge(self::$V2, self::$V4);
        $we13 = $g->addEdge(self::$V1, self::$V3);
        $we32 = $g->addEdge(self::$V3, self::$V2);
        $we34 = $g->addEdge(self::$V3, self::$V4);

        $g->setEdgeWeight($we12, 3.0);
        $g->setEdgeWeight($we24, 1.0);
        $g->setEdgeWeight($we13, 1.0);
        $g->setEdgeWeight($we32, 1.0);
        $g->setEdgeWeight($we34, 3.0);

        $pathsTree = (new DijkstraShortestPath($g))->getPaths(self::$V1);
        $this->assertEquals($g, $pathsTree->getGraph());
        $this->assertEquals(self::$V1, $pathsTree->getSourceVertex());
        $this->assertEquals(0.0, $pathsTree->getWeight(self::$V1));
        $this->assertEquals(2.0, $pathsTree->getWeight(self::$V2));
        $this->assertEquals(1.0, $pathsTree->getWeight(self::$V3));
        $this->assertEquals(3.0, $pathsTree->getWeight(self::$V4));
        $this->assertEquals(INF, $pathsTree->getWeight(self::$V5));

        $p11 = $pathsTree->getPath(self::$V1);
        $this->assertEquals(self::$V1, $p11->getStartVertex());
        $this->assertEquals(self::$V1, $p11->getEndVertex());
        $this->assertEquals(0.0, $p11->getWeight());
        $this->assertTrue(count($p11->getEdgeList()) == 0);

        $p12 = $pathsTree->getPath(self::$V2);
        $this->assertEquals(self::$V1, $p12->getStartVertex());
        $this->assertEquals(self::$V2, $p12->getEndVertex());
        $this->assertEquals(2.0, $p12->getWeight());
        $this->assertEquals([$we13, $we32], $p12->getEdgeList());

        $p13 = $pathsTree->getPath(self::$V3);
        $this->assertEquals(self::$V1, $p13->getStartVertex());
        $this->assertEquals(self::$V3, $p13->getEndVertex());
        $this->assertEquals(1.0, $p13->getWeight());
        $this->assertEquals([$we13], $p13->getEdgeList());

        $p14 = $pathsTree->getPath(self::$V4);
        $this->assertEquals(self::$V1, $p14->getStartVertex());
        $this->assertEquals(self::$V4, $p14->getEndVertex());
        $this->assertEquals(3.0, $p14->getWeight());
        $this->assertEquals([$we13, $we32, $we24], $p14->getEdgeList());

        $p15 = $pathsTree->getPath(self::$V5);
        $this->assertNull($p15);
    }

    public function testGetPathWeight(): void
    {
        $g = new DirectedWeightedPseudograph(DefaultWeightedEdge::class);
        GraphUtils::addAllVertices($g, new VertexSet([self::$V1, self::$V2, self::$V3, self::$V4, self::$V5]));

        $we12 = $g->addEdge(self::$V1, self::$V2);
        $we24 = $g->addEdge(self::$V2, self::$V4);
        $we13 = $g->addEdge(self::$V1, self::$V3);
        $we32 = $g->addEdge(self::$V3, self::$V2);
        $we34 = $g->addEdge(self::$V3, self::$V4);

        $g->setEdgeWeight($we12, 3.0);
        $g->setEdgeWeight($we24, 1.0);
        $g->setEdgeWeight($we13, 1.0);
        $g->setEdgeWeight($we32, 1.0);
        $g->setEdgeWeight($we34, 3.0);

        $this->assertEquals(0.0, (new DijkstraShortestPath($g))->getPathWeight(self::$V1, self::$V1));
        $this->assertEquals(2.0, (new DijkstraShortestPath($g))->getPathWeight(self::$V1, self::$V2));
        $this->assertEquals(1.0, (new DijkstraShortestPath($g))->getPathWeight(self::$V1, self::$V3));
        $this->assertEquals(3.0, (new DijkstraShortestPath($g))->getPathWeight(self::$V1, self::$V4));
        $this->assertEquals(INF, (new DijkstraShortestPath($g))->getPathWeight(self::$V1, self::$V5));
    }

    public function testNonNegativeWeights(): void
    {
        $g = new DirectedWeightedPseudograph(DefaultWeightedEdge::class);
        GraphUtils::addAllVertices($g, new VertexSet([self::$V1, self::$V2]));

        $we12 = $g->addEdge(self::$V1, self::$V2);
        $g->setEdgeWeight($we12, -100.0);

        // Invalid: Negative edge weight not allowed
        $this->expectException(InvalidArgumentException::class);
        (new DijkstraShortestPath($g))->getPath(self::$V1, self::$V2);
    }
}

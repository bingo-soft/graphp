<?php

namespace tests\Alg\Shortestpath;

use PHPUnit\Framework\TestCase;
use Graphp\GraphInterface;
use Graphp\GraphUtils;
use Graphp\Graph\Types\SimpleWeightedGraph;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Vertex\Vertex;
use Graphp\Vertex\VertexSet;
use Graphp\Alg\Shortestpath\DijkstraClosestFirstIterator;

abstract class ShortestPathTest extends TestCase
{
    public static $V1;
    public static $V2;
    public static $V3;
    public static $V4;
    public static $V5;

    public $e12;
    public $e13;
    public $e15;
    public $e24;
    public $e34;
    public $e45;

    public static function setUpBeforeClass(): void
    {
        self::$V1 = new Vertex("v1");
        self::$V2 = new Vertex("v2");
        self::$V3 = new Vertex("v3");
        self::$V4 = new Vertex("v4");
        self::$V5 = new Vertex("v5");
    }

    public function testPathBetween(): void
    {
        $g = $this->create();

        $path = $this->findPathBetween($g, self::$V1, self::$V2);
        $this->assertEquals([$this->e12], $path);

        $path = $this->findPathBetween($g, self::$V1, self::$V4);
        $this->assertEquals([$this->e12, $this->e24], $path);

        $path = $this->findPathBetween($g, self::$V1, self::$V5);
        $this->assertEquals([$this->e12, $this->e24, $this->e45], $path);

        $path = $this->findPathBetween($g, self::$V3, self::$V4);
        $this->assertEquals([$this->e13, $this->e12, $this->e24], $path);
    }

    protected function create(): GraphInterface
    {
        $g = new SimpleWeightedGraph(DefaultWeightedEdge::class);

        $g->addVertex(self::$V1);
        $g->addVertex(self::$V2);
        $g->addVertex(self::$V3);
        $g->addVertex(self::$V4);
        $g->addVertex(self::$V5);

        $this->e12 = GraphUtils::addEdge($g, self::$V1, self::$V2, 2);

        $this->e13 = GraphUtils::addEdge($g, self::$V1, self::$V3, 3);

        $this->e24 = GraphUtils::addEdge($g, self::$V2, self::$V4, 5);

        $this->e34 = GraphUtils::addEdge($g, self::$V3, self::$V4, 20);

        $this->e45 = GraphUtils::addEdge($g, self::$V4, self::$V5, 5);

        $this->e15 = GraphUtils::addEdge($g, self::$V1, self::$V5, 100);

        return $g;
    }
}

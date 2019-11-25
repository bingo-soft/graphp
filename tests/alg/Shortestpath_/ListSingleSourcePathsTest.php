<?php

namespace tests\alg\shortestpath;

use PHPUnit\Framework\TestCase;
use Graphp\GraphUtils;
use Graphp\Graph\Types\DirectedPseudograph;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Vertex\Vertex;
use Graphp\Vertex\VertexSet;
use Graphp\Alg\Shortestpath\ListSingleSourcePaths;

class ListSingleSourcePathsTest extends TestCase
{
    public function test(): void
    {
        $graph = new DirectedPseudograph(DefaultWeightedEdge::class);
        $this->assertTrue(true);
    }
}

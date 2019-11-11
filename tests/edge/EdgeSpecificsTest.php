<?php

namespace tests\edge;

use PHPUnit\Framework\TestCase;
use graphp\graph\specifics\DirectedSpecifics;
use graphp\graph\specifics\UndirectedSpecifics;
use graphp\graph\graphs\DefaultDirectedGraph;
use graphp\edge\DefaultEdge;
use graphp\edge\DefaultWeightedEdge;
use graphp\vertex\Vertex;

class EdgeSpecificsTest extends TestCase
{
    public function testDirectedSpecificsCreation(): void
    {
        $graph = new DefaultDirectedGraph(DefaultEdge::class);
        $this->assertTrue(true);
    }
}

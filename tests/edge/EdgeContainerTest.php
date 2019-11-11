<?php

namespace tests\edge;

use PHPUnit\Framework\TestCase;
use graphp\graph\specifics\DirectedEdgeContainer;
use graphp\graph\specifics\UndirectedEdgeContainer;
use graphp\edge\EdgeArraySetFactory;
use graphp\edge\DefaultEdge;
use graphp\vertex\Vertex;

class EdgeContainerTest extends TestCase
{
    public function testDirectedEdgeContainerOutgoing(): void
    {
        $vertex = new Vertex(123);
        $ec = new DirectedEdgeContainer(new EdgeArraySetFactory(), $vertex);
        $edge = new DefaultEdge();
        $edge2 = new DefaultEdge();
        $ec->addOutgoingEdge($edge);
        $this->assertCount(1, $ec->getOutgoing());

        $ec->removeOutgoingEdge($edge2);
        $ec->removeOutgoingEdge($edge);
        $this->assertCount(0, $ec->getOutgoing());
    }

    public function testDirectedEdgeContainerIncoming(): void
    {
        $vertex = new Vertex(123);
        $ec = new DirectedEdgeContainer(new EdgeArraySetFactory(), $vertex);
        $edge = new DefaultEdge();
        $edge2 = new DefaultEdge();
        $ec->addIncomingEdge($edge);
        $this->assertCount(1, $ec->getIncoming());

        $ec->removeIncomingEdge($edge2);
        $ec->removeIncomingEdge($edge);
        $this->assertCount(0, $ec->getIncoming());
    }

    public function testUndirectedEdgeMethods(): void
    {
        $vertex = new Vertex(123);
        $ec = new UndirectedEdgeContainer(new EdgeArraySetFactory(), $vertex);
        $edge = new DefaultEdge();
        $edge2 = new DefaultEdge();

        $ec->addEdge($edge);
        $this->assertCount(1, $ec->getEdges());

        $ec->removeEdge($edge2);
        $this->assertCount(1, $ec->getEdges());

        $ec->removeEdge($edge);
        $this->assertCount(0, $ec->getEdges());
    }
}

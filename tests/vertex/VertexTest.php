<?php

namespace tests\vertex;

use PHPUnit\Framework\TestCase;
use graphp\graph\specifics\DirectedEdgeContainer;
use graphp\graph\specifics\UndirectedEdgeContainer;
use graphp\edge\EdgeArraySetFactory;
use graphp\vertex\Vertex;
use graphp\vertex\VertexMap;
use graphp\vertex\VertexSet;

class VertexTest extends TestCase
{
    public function testVertexCreation(): void
    {
        $vertex1 = new Vertex(1);
        $this->assertNotNull($vertex1->getHash());
        $this->assertEquals(1, $vertex1->getValue());
        $this->assertEquals(1, (string) $vertex1);
    }

    public function testVertexMapMethods(): void
    {
        $vm = new VertexMap();
        $vertex = new Vertex(123);
        $vertex2 = new Vertex(345);
        $ec = new DirectedEdgeContainer(new EdgeArraySetFactory(), $vertex);

        $vm->put($vertex, $ec);
        $this->assertNotNull($vm->get($vertex));
        $this->assertNull($vm->get($vertex2));

        $vertices = $vm->keySet();
        $this->assertCount(1, $vertices);
        $this->assertInstanceOf(Vertex::class, $vertices[0]);

        $vm->remove($vertex);
        $this->assertNull($vm->get($vertex));

        $vm->put($vertex, $ec);
        $this->assertNotNull($vm->get($vertex));
        $id = $vertex->getHash();
        unset($vm[$id]);
        $this->assertNull($vm->get($vertex));
    }

    public function testVertexSetCreation(): void
    {
        $vs = new VertexSet();
        $vertex1 = new Vertex(1);
        $vertex2 = new Vertex(2);
        $vertex3 = new Vertex(3);
        
        $vs[] = $vertex1;
        $vs[] = $vertex2;
        
        $this->assertCount(2, $vs);
        $this->assertTrue($vs->contains($vertex1));
        $this->assertFalse($vs->contains($vertex3));

        $vs->remove($vertex3);
        $vs->remove($vertex2);
        $this->assertCount(1, $vs);

        $vs->remove($vertex1);
        $this->assertCount(0, $vs);

        $vs->remove($vertex1);
    }
}

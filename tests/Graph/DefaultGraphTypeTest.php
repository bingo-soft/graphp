<?php

namespace tests\Graph;

use PHPUnit\Framework\TestCase;
use Graphp\Graph\DefaultGraphType;
use Graphp\Graph\Builder\GraphTypeBuilder;

class DefaultGraphTypeTest extends TestCase
{
    public function testDefaultGraphType(): void
    {
        $type = new DefaultGraphType(true, false, false, true, true, false, true);
        $this->assertTrue($type->isDirected());
        $this->assertFalse($type->isUndirected());
        $this->assertFalse($type->isMixed());
        $this->assertTrue($type->isAllowingMultipleEdges());
        $this->assertFalse($type->isAllowingCycles());
        $this->assertFalse($type->isAllowingSelfLoops());
        $this->assertTrue($type->isWeighted());
        $this->assertFalse($type->isSimple());
        $this->assertTrue($type->isMultigraph());
        $this->assertTrue($type->isModifiable());

        $this->assertFalse($type->asUndirected()->isDirected());
        $this->assertFalse($type->asDirected()->isUndirected());
        $this->assertTrue($type->asMixed()->isMixed());
        $this->assertFalse($type->asUnweighted()->isWeighted());
        $this->assertTrue($type->asWeighted()->isWeighted());
        $this->assertFalse($type->asUnmodifiable()->isModifiable());
        $this->assertTrue($type->asModifiable()->isModifiable());

        $simple = $type->simple();
        $this->assertTrue($simple->isUndirected());
        $this->assertFalse($simple->isAllowingSelfLoops());
        $this->assertFalse($simple->isAllowingMultipleEdges());
        $this->assertFalse($simple->isWeighted());

        $multi = $type->multigraph();
        $this->assertTrue($multi->isUndirected());
        $this->assertFalse($multi->isAllowingSelfLoops());
        $this->assertTrue($multi->isAllowingMultipleEdges());
        $this->assertFalse($multi->isWeighted());

        $ps = $type->pseudograph();
        $this->assertTrue($ps->isUndirected());
        $this->assertTrue($ps->isAllowingSelfLoops());
        $this->assertTrue($ps->isAllowingMultipleEdges());
        $this->assertFalse($ps->isWeighted());

        $simple = $type->directedSimple();
        $this->assertFalse($simple->isUndirected());
        $this->assertFalse($simple->isAllowingSelfLoops());
        $this->assertFalse($simple->isAllowingMultipleEdges());
        $this->assertFalse($simple->isWeighted());

        $dm = $type->directedMultigraph();
        $this->assertFalse($dm->isUndirected());
        $this->assertFalse($dm->isAllowingSelfLoops());
        $this->assertTrue($dm->isAllowingMultipleEdges());
        $this->assertFalse($dm->isWeighted());

        $dm = $type->directedPseudograph();
        $this->assertFalse($dm->isUndirected());
        $this->assertTrue($dm->isAllowingSelfLoops());
        $this->assertTrue($dm->isAllowingMultipleEdges());
        $this->assertFalse($dm->isWeighted());

        $mixed = $type->mixed();
        $this->assertTrue($mixed->isMixed());
        $this->assertTrue($mixed->isAllowingSelfLoops());
        $this->assertTrue($mixed->isAllowingMultipleEdges());
        $this->assertFalse($mixed->isWeighted());

        $dag = $type->dag();
        $this->assertTrue($dag->isDirected());
        $this->assertFalse($dag->isAllowingSelfLoops());
        $this->assertTrue($dag->isAllowingMultipleEdges());
        $this->assertFalse($dag->isAllowingCycles());
        $this->assertFalse($dag->isWeighted());
        
        $this->assertEquals("DefaultGraphType [directed=1, undirected=, self-loops=, " .
                            "multiple-edges=1, weighted=, allows-cycles=, modifiable=1]", (string) $dag);

        $gtb = new GraphTypeBuilder(null, true, false);
        $type = $gtb->build();
        $this->assertTrue($type->isDirected());
        $this->assertFalse($type->isUndirected());
        $this->assertTrue($type->isAllowingSelfLoops());
        $this->assertTrue($type->isAllowingMultipleEdges());
        $this->assertFalse($type->isWeighted());
        $this->assertTrue($type->isAllowingCycles());
        $this->assertTrue($type->isModifiable());
    }
}

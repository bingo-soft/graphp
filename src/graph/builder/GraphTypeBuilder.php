<?php

namespace graphp\graph\builder;

use InvalidArgumentException;
use graphp\graph\DefaultGraphType;
use graphp\graph\GraphTypeInterface;

/**
 * class GraphTypeBuilder
 *
 * @package graphp\graph
 */
class GraphTypeBuilder
{
    private $directed;
    private $undirected;
    private $allowSelfLoops;
    private $allowMultipleEdges;
    private $weighted;
    private $allowCycles;
    private $modifiable;

    /**
     * Construct a new graph type
     *
     * @param GraphTypeInterface $type - the graph type
     * @param bool $directed - is graph directed
     * @param bool $undirected - is graph undirected
     */
    public function __construct(?GraphTypeInterface $type = null, ?bool $directed = null, ?bool $undirected = null)
    {
        if (!is_null($type)) {
            $this->directed = $type->isDirected() || $type->isMixed();
            $this->undirected = $type->isUndirected() || $type->isMixed();
            $this->allowSelfLoops = $type->isAllowingSelfLoops();
            $this->allowMultipleEdges = $type->isAllowingMultipleEdges();
            $this->weighted = $type->isWeighted();
            $this->allowCycles = $type->isAllowingCycles();
            $this->modifiable = $type->isModifiable();
        } elseif (!is_null($directed) && !is_null($undirected)) {
            if (!$directed && !$undirected) {
                throw new InvalidArgumentException("At least one of directed or undirected must be true");
            }
            $this->directed = $directed;
            $this->undirected = $undirected;
            $this->allowSelfLoops = true;
            $this->allowMultipleEdges = true;
            $this->weighted = false;
            $this->allowCycles = true;
            $this->modifiable = true;
        } else {
            $this->directed = false;
            $this->undirected = true;
            $this->allowSelfLoops = true;
            $this->allowMultipleEdges = true;
            $this->weighted = false;
            $this->allowCycles = true;
            $this->modifiable = true;
        }
    }

    /**
     * Set the type as directed.
     *
     * @return self
     */
    public function directed(): self
    {
        $this->directed = true;
        $this->undirected = false;
        return $this;
    }

    /**
     * Set the type as undirected.
     *
     * @return self
     */
    public function undirected(): self
    {
        $this->directed = false;
        $this->undirected = true;
        return $this;
    }

    /**
     * Set the type as mixed.
     *
     * @return self
     */
    public function mixed(): self
    {
        $this->directed = true;
        $this->undirected = true;
        return $this;
    }

    /**
     * Set whether to allow self-loops.
     *
     * @param bool $value - if to allow self loops
     *
     * @return self
     */
    public function allowSelfLoops(bool $value): self
    {
        $this->allowSelfLoops = $value;
        return $this;
    }

    /**
     * Set whether to allow multiple edges.
     *
     * @param bool $value - if to allow multiple edges
     *
     * @return self
     */
    public function allowMultipleEdges(bool $value): self
    {
        $this->allowMultipleEdges = $value;
        return $this;
    }

    /**
     * Set whether the graph is weighted.
     *
     * @param bool $value - if graph is weighted
     *
     * @return self
     */
    public function weighted(?bool $value = null): self
    {
        $this->weighted = $value ?? false;
        return $this;
    }

    /**
     * Set whether to allow cycles.
     *
     * @param bool $value - if to allow cycles
     *
     * @return self
     */
    public function allowCycles(bool $value): self
    {
        $this->allowCycles = $value;
        return $this;
    }

    /**
     * Set whether the graph is modifiable.
     *
     * @param bool $value - if graph is modifiable
     *
     * @return self
     */
    public function modifiable(bool $value): self
    {
        $this->modifiable = $value;
        return $this;
    }

    /**
     * Build the graph type
     *
     * @return GraphTypeInterface
     */
    public function build(): GraphTypeInterface
    {
        return new DefaultGraphType(
            $this->directed,
            $this->undirected,
            $this->allowSelfLoops,
            $this->allowMultipleEdges,
            $this->weighted,
            $this->allowCycles,
            $this->modifiable
        );
    }
}

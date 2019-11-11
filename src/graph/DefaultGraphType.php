<?php

namespace graphp\graph;

/**
 * class DefaultGraphType
 *
 * @package graphp\graph
 */
class DefaultGraphType implements GraphTypeInterface
{
    private $directed;
    private $undirected;
    private $selfLoops;
    private $multipleEdges;
    private $weighted;
    private $allowsCycles;
    private $modifiable;

    /**
     * Construct a default graph type
     */
    public function __construct(
        bool $directed,
        bool $undirected,
        bool $selfLoops,
        bool $multipleEdges,
        bool $weighted,
        bool $allowsCycles,
        bool $modifiable
    ) {
        $this->directed = $directed;
        $this->undirected = $undirected;
        $this->selfLoops = $selfLoops;
        $this->multipleEdges = $multipleEdges;
        $this->weighted = $weighted;
        $this->allowsCycles = $allowsCycles;
        $this->modifiable = $modifiable;
    }

    /**
     * Check if all edges of the graph are directed
     *
     * @return bool
     */
    public function isDirected(): bool
    {
        return $this->directed && !$this->undirected;
    }

    /**
     * Check if all edges of the graph are undirected
     *
     * @return bool
     */
    public function isUndirected(): bool
    {
        return $this->undirected && !$this->directed;
    }

    /**
     * Check if the graph contains both directed and undirected edges
     *
     * @return bool
     */
    public function isMixed(): bool
    {
        return $this->undirected && $this->directed;
    }

    /**
     * Check if multiple edges are allowed in the graph
     *
     * @return bool
     */
    public function isAllowingMultipleEdges(): bool
    {
        return $this->multipleEdges;
    }

    /**
     * Check if cycles are allowed in the graph
     *
     * @return bool
     */
    public function isAllowingCycles(): bool
    {
        return $this->allowsCycles;
    }

    /**
     * Check if self-loops are allowed in the graph
     *
     * @return bool
     */
    public function isAllowingSelfLoops(): bool
    {
        return $this->selfLoops;
    }

    /**
     * Check if weighted edges are allowed in the graph
     *
     * @return bool
     */
    public function isWeighted(): bool
    {
        return $this->weighted;
    }

    /**
     * Check if the graph is simple
     *
     * @return bool
     */
    public function isSimple(): bool
    {
        return !$this->isAllowingMultipleEdges() && !$this->isAllowingSelfLoops();
    }

    /**
     * Check if the graph is multigraph
     *
     * @return bool
     */
    public function isMultigraph(): bool
    {
        return $this->isAllowingMultipleEdges() && !$this->isAllowingSelfLoops();
    }

    /**
     * Check if the graph is modifiable
     *
     * @return bool
     */
    public function isModifiable(): bool
    {
        return $this->modifiable;
    }

    /**
     * Create a directed variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asDirected(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder($this);
        return $builder->directed()->build();
    }

    /**
     * Create an undirected variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asUndirected(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder($this);
        return $builder->undirected()->build();
    }

    /**
     * Create a mixed variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asMixed(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder($this);
        return $builder->mixed()->build();
    }

    /**
     * Create a weighted variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asWeighted(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder($this);
        return $builder->weighted(true)->build();
    }

    /**
     * Create an unweighted variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asUnweighted(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder($this);
        return $builder->weighted(false)->build();
    }

    /**
     * Create a modifiable variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asModifiable(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder($this);
        return $builder->modifiable(true)->build();
    }

    /**
     * Create an unmodifiable variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asUnmodifiable(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder($this);
        return $builder->modifiable(false)->build();
    }

    /**
     * Create a simple graph.
     *
     * @return GraphTypeInterface
     */
    public static function simple(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder();
        return $builder->undirected()
            ->allowSelfLoops(false)
            ->allowMultipleEdges(false)
            ->weighted(false)
            ->build();
    }

    /**
     * Create a multigraph.
     *
     * @return GraphTypeInterface
     */
    public static function multigraph(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder();
        return $builder->undirected()
            ->allowSelfLoops(false)
            ->allowMultipleEdges(true)
            ->weighted(false)
            ->build();
    }

    /**
     * Create a pseudograph.
     *
     * @return GraphTypeInterface
     */
    public static function pseudograph(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder();
        return $builder->undirected()
            ->allowSelfLoops(true)
            ->allowMultipleEdges(true)
            ->weighted(false)
            ->build();
    }

    /**
     * Create a directed simple graph.
     *
     * @return GraphTypeInterface
     */
    public static function directedSimple(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder();
        return $builder->directed()
            ->allowSelfLoops(false)
            ->allowMultipleEdges(true)
            ->weighted(false)
            ->build();
    }

    /**
     * Create a directed multigraph.
     *
     * @return GraphTypeInterface
     */
    public static function directedMultigraph(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder();
        return $builder->directed()
            ->allowSelfLoops(false)
            ->allowMultipleEdges(true)
            ->weighted(false)
            ->build();
    }

    /**
     * Create a directed pseudograph.
     *
     * @return GraphTypeInterface
     */
    public static function directedPseudograph(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder();
        return $builder->directed()
            ->allowSelfLoops(true)
            ->allowMultipleEdges(true)
            ->weighted(false)
            ->build();
    }

    /**
     * Create a mixed graph.
     *
     * @return GraphTypeInterface
     */
    public static function mixed(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder();
        return $builder->mixed()
            ->allowSelfLoops(true)
            ->allowMultipleEdges(true)
            ->weighted(false)
            ->build();
    }

    /**
     * Create a directed acyclic graph.
     *
     * @return GraphTypeInterface
     */
    public static function dag(): GraphTypeInterface
    {
        $builder = new GraphTypeBuilder();
        return $builder->directed()
            ->allowSelfLoops(false)
            ->allowMultipleEdges(true)
            ->allowCycles(false)
            ->weighted(false)
            ->build();
    }

    /**
     * Get the graph type string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        return "DefaultGraphType [directed=" . $this->directed . ", undirected=" . $this->undirected
        . ", self-loops=" . $this->selfLoops . ", multiple-edges=" . $this->multipleEdges . ", weighted="
        . $this->weighted . ", allows-cycles=" . $this->allowsCycles . ", modifiable=" . $this->modifiable . "]";
    }
}

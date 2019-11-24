<?php

namespace Graphp\Graph;

/**
 * Interface GraphTypeInterface
 *
 * @package Graphp\Graph
 */
interface GraphTypeInterface
{
    /**
     * Check if all edges of the graph are directed
     *
     * @return bool
     */
    public function isDirected(): bool;

    /**
     * Check if all edges of the graph are undirected
     *
     * @return bool
     */
    public function isUndirected(): bool;

    /**
     * Check if the graph contains both directed and undirected edges
     *
     * @return bool
     */
    public function isMixed(): bool;

    /**
     * Check if multiple edges are allowed in the graph
     *
     * @return bool
     */
    public function isAllowingMultipleEdges(): bool;

    /**
     * Check if cycles are allowed in the graph
     *
     * @return bool
     */
    public function isAllowingCycles(): bool;

    /**
     * Check if self-loops are allowed in the graph
     *
     * @return bool
     */
    public function isAllowingSelfLoops(): bool;

    /**
     * Check if weighted edges are allowed in the graph
     *
     * @return bool
     */
    public function isWeighted(): bool;

    /**
     * Check if the graph is simple
     *
     * @return bool
     */
    public function isSimple(): bool;

    /**
     * Check if the graph is multigraph
     *
     * @return bool
     */
    public function isMultigraph(): bool;

    /**
     * Check if the graph is modifiable
     *
     * @return bool
     */
    public function isModifiable(): bool;

    /**
     * Create a directed variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asDirected(): GraphTypeInterface;

    /**
     * Create an undirected variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asUndirected(): GraphTypeInterface;

    /**
     * Create a mixed variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asMixed(): GraphTypeInterface;

    /**
     * Create a weighted variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asWeighted(): GraphTypeInterface;

    /**
     * Create an unweighted variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asUnweighted(): GraphTypeInterface;

    /**
     * Create a modifiable variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asModifiable(): GraphTypeInterface;

    /**
     * Create an unmodifiable variant of the current graph type.
     *
     * @return GraphTypeInterface
     */
    public function asUnmodifiable(): GraphTypeInterface;
}

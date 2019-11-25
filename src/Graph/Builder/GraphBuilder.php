<?php

namespace Graphp\Graph\Builder;

use Graphp\GraphInterface;
use Graphp\GraphUtils;
use Graphp\Edge\EdgeInterface;
use Graphp\Vertex\VertexInterface;
use Graphp\Vertex\VertexSet;

/**
 * Class GraphBuilder
 *
 * @package Graphp\Graph\Builder
 */
class GraphBuilder
{
    /**
     * The graph
     *
     * @var GraphInterface
     */
    private $graph;
    
    /**
     * Construct the graph
     *
     * @param GraphInterface $graph - the graph
     */
    public function __construct(GraphInterface $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Add a vertex to the graph being built
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return self
     */
    public function addVertex(VertexInterface $vertex): self
    {
        $this->graph->addVertex($vertex);
        return $this;
    }

    /**
     * Add vertices to the graph being built
     *
     * @param VertexSet $vertices - the vertices
     *
     * @return self
     */
    public function addVertices(VertexSet $vertices): self
    {
        foreach ($vertices as $vertex) {
            $this->addVertex($vertex);
        }
        return $this;
    }

    /**
     * Add an edge to the graph being built
     *
     * @param VertexInterface $vertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     * @param EdgeInterface $edge - the edge to be added
     *
     * @return self
     */
    public function addEdge(
        VertexInterface $sourceVertex,
        VertexInterface $targetVertex,
        ?EdgeInterface $edge = null,
        ?float $weight = null
    ): self {
        if (is_null($edge)) {
            GraphUtils::addEdgeWithVertices($this->graph, $sourceVertex, $targetVertex, $weight);
        } else {
            $this->addVertex($sourceVertex);
            $this->addVertex($targetVertex);
            $this->graph->addEdge($sourceVertex, $targetVertex, $edge);
        }
        return $this;
    }

    /**
     * Add an chain of edges to the graph being built
     *
     * @param VertexInterface $first - the first vertex
     * @param VertexInterface $second - the second vertex
     * @param mixed $vertices - the remaining vertices
     *
     * @return self
     */
    public function addEdgeChain(VertexInterface $first, VertexInterface $second, ...$vertices): self
    {
        $this->addEdge($first, $second);
        $last = $second;
        foreach ($vertices as $vertex) {
            $this->addEdge($last, $vertex);
            $last = $vertex;
        }
        return $this;
    }

    /**
     * Add all the vertices and all the edges of the specified graph to the graph being built
     *
     * @param GraphInterface $sourceGraph - the graph
     *
     * @return self
     */
    public function addGraph(GraphInterface $sourceGraph): self
    {
        GraphUtils::addGraph($this->graph, $sourceGraph);
        return $this;
    }

    /**
     * Remove the specified vertex from the graph being built
     *
     * @param VertexInterface $vertex - the vertex to remove
     *
     * @return self
     */
    public function removeVertex(VertexInterface $vertex): self
    {
        $this->graph->removeVertex($vertex);
        return $this;
    }

    /**
     * Remove vertices from the graph being built
     *
     * @param VertexSet $vertices - the vertices to remove
     *
     * @return self
     */
    public function removeVertices(VertexSet $vertices): self
    {
        foreach ($vertices as $vertex) {
            $this->removeVertex($vertex);
        }
        return $this;
    }

    /**
     * Remove the edge from the graph being built
     *
     * @param VertexInterface $sourceVertex - the edge source vertex
     * @param VertexInterface $tagretVertex - the edge target vertex
     * @param EdgeInterface $edge - the edge
     *
     * @return self
     */
    public function removeEdge(
        ?VertexInterface $sourceVertex = null,
        ?VertexInterface $targetVertex = null,
        ?EdgeInterface $edge = null
    ): self {
        $this->graph->removeEdge($sourceVertex, $targetVertex, $edge);
        return $this;
    }

    /**
     * Get the graph being built
     *
     * @return GraphInterface
     */
    public function build(): GraphInterface
    {
        return $this->graph;
    }
}

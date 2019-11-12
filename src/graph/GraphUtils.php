<?php

namespace graphp\graph;

use InvalidArgumentException;
use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSet;
use graphp\vertex\VertexInterface;
use graphp\vertex\VertexSet;

/**
 * Class GraphUtils
 *
 * @package graphp\graph
 */
class GraphUtils
{
    /**
     * Create a new edge and add it to the specified graph
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     * @param float $weight - the weight of the edge being added
     *
     * @return EdgeInterface
     */
    public static function addEdge(
        GraphInterface $graph,
        VertexInterface $sourceVertex,
        VertexInterface $targetVertex,
        ?float $weight = null
    ): ?EdgeInterface {
        $edgeSupplier = $graph->getEdgeSupplier();
        if (is_null($edgeSupplier)) {
            throw new Exception("Graph contains no edge supplier");
        }
        $edge = $edgeSupplier->get();

        if ($graph->addEdge($sourceVertex, $targetVertex, $edge)) {
            if ($graph->getType()->isWeighted()) {
                $graph->setEdgeWeight($edge, $weight);
            }
            return $edge;
        }
        return null;
    }

    /**
     * Add the speficied source and target vertices to the graph
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     * @param float $weight - the weight of the edge being added
     *
     * @return EdgeInterface
     */
    public static function addEdgeWithVertices(
        GraphInterface $graph,
        VertexInterface $sourceVertex,
        VertexInterface $targetVertex,
        ?float $weight = null
    ): EdgeInterface {
        $graph->addVertex($sourceVertex);
        $graph->addVertex($targetVertex);
        return self::addEdge($graph, $sourceVertex, $targetVertex, $weight);
    }

    /**
     * Add all the vertices and all the edges of the source graph to the target graph.
     * Return true, if the target graph was changed
     *
     * @param GraphInterface $targetGraph - the target graph
     * @param GraphInterface $sourceGraph - the source graph
     *
     * @return bool
     */
    public static function addGraph(GraphInterface $targetGraph, GraphInterface $sourceGraph): bool
    {
        $modified = self::addAllVertices($targetGraph, $sourceGraph->vertexSet());
        return self::addAllEdges($targetGraph, $sourceGraph, $sourceGraph->edgeSet()) || $modified;
    }

    /**
     * Add all vertices to the specified graph.
     * Return true, if the graph was modified
     *
     * @param GraphInterface $graph - the target graph
     * @param VertexSet $vertices - the vertices
     *
     * @return bool
     */
    public static function addAllVertices(GraphInterface $graph, VertexSet $vertices): bool
    {
        $modified = false;
        foreach ($vertices as $vertex) {
            $modified = $graph->addVertex($vertex) || $modified;
        }
        return $modified;
    }

    /**
     * Add all edges of the source graph to the target graph.
     * Return true, if the graph was modified
     *
     * @param GraphInterface $targetGraph - the target graph
     * @param GraphInterface $sourceGraph - the source graph
     * @param EdgeSet $edges - the edges to add
     *
     * @return bool
     */
    public static function addAllEdges(
        GraphInterface $targetGraph,
        GraphInterface $sourceGraph,
        EdgeSet $edges
    ): bool {
        $modified = false;
        foreach ($edges as $edge) {
            $sourceVertex = $sourceGraph->getEdgeSource($edge);
            $targetVertex = $sourceGraph->getEdgeTarget($edge);
            $targetGraph->addVertex($sourceVertex);
            $targetGraph->addVertex($targetVertex);
            $modified = $targetGraph->addEdge($sourceVertex, $targetVertex, $edge) || $modified;
        }
        return $modified;
    }

    /**
     * Add all the vertices and all the edges of the source graph to the target graph in the reversed order
     *
     * @param GraphInterface $targetGraph - the target graph
     * @param GraphInterface $sourceGraph - the source graph
     */
    public static function addGraphReversed(GraphInterface $targetGraph, GraphInterface $sourceGraph): void
    {
        if (!$sourceGraph->getType()->isDirected() || !$targetGraph->getType()->isDirected()) {
            throw new InvalidArgumentException("graph must be directed");
        }

        self::addAllVertices($targetGraph, $sourceGraph->vertexSet());

        $edges = $sourceGraph->edgeSet();
        foreach ($edges as $edge) {
            $targetGraph->addEdge($sourceGraph->getEdgeTarget($edge), $sourceGraph->getEdgeSource($edge));
        }
    }

    /**
     * Get a list of vertices that are the neighbors of a specified vertex.
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the vertex
     *
     * @return array
     */
    public static function neighborsOf(GraphInterface $graph, VertexInterface $vertex): array
    {
        $neighbors = [];
        $edges = $graph->edgesOf($vertex);
        foreach ($edges as $edge) {
            $neighbors[] = self::getOppositeVertex($graph, $edge, $vertex);
        }
        return $neighbors;
    }

    /**
     * Get a list of vertices that are the direct predecessors of a specified vertex.
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the vertex
     *
     * @return array
     */
    public static function predecessorsOf(GraphInterface $graph, VertexInterface $vertex): array
    {
        $neighbors = [];
        $edges = $graph->incomingEdgesOf($vertex);
        foreach ($edges as $edge) {
            $neighbors[] = self::getOppositeVertex($graph, $edge, $vertex);
        }
        return $neighbors;
    }

    /**
     * Get a list of vertices that are the direct successors of a specified vertex.
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the vertex
     *
     * @return array
     */
    public static function successorsOf(GraphInterface $graph, VertexInterface $vertex): array
    {
        $neighbors = [];
        $edges = $graph->outgoingEdgesOf($vertex);
        foreach ($edges as $edge) {
            $neighbors[] = self::getOppositeVertex($graph, $edge, $vertex);
        }
        return $neighbors;
    }

    /**
     * Test whether an edge is incident to a vertex.
     *
     * @param GraphInterface $graph - the graph
     * @param EdgeInterface $edge - the edge
     * @param VertexInterface $vertex - the vertex
     *
     * @return bool
     */
    public static function testIncidence(GraphInterface $graph, EdgeInterface $edge, VertexInterface $vertex): bool
    {
        return $graph->getEdgeSource($edge)->equals($vertex)
               || $graph->getEdgeTarget($edge)->equals($vertex);
    }

    /**
     * Get the vertex opposite another vertex across an edge.
     *
     * @param g graph containing e and v
     * @param e edge in g
     * @param v vertex in g
     * @param <V> the graph vertex type
     * @param <E> the graph edge type
     *
     * @return vertex opposite to v across e
     *
     * @throws InvalidArgumentException
     */
    public static function getOppositeVertex(
        GraphInterface $graph,
        EdgeInterface $edge,
        VertexInterface $vertex
    ): VertexInterface {
        $source = $graph->getEdgeSource($edge);
        $target = $graph->getEdgeTarget($edge);
        if ($vertex->equals($source)) {
            return $target;
        } elseif ($vertex->equals($target)) {
            return $source;
        } else {
            throw new InvalidArgumentException("no such vertex: " + $vertex);
        }
    }

    /**
     * Remove the given vertices from the given graph. If the vertex to be removed has one or more
     * predecessors, the predecessors will be connected directly to the successors of the vertex to
     * be removed.
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the vertex to be removed
     * @param mixed $vertices - the remaining vertices
     *
     * @return bool
     */
    public static function removeVertexAndPreserveConnectivity(
        GraphInterface $graph,
        VertexInterface $vertex,
        ...$vertices
    ): bool {
        if (!$graph->containsVertex($vertex)) {
            return false;
        }

        $vertices[] = $vertex;
        foreach ($vertices as $vertex) {
            if (self::vertexHasPredecessors($graph, $vertex)) {
                $predecessors = self::predecessorsOf($graph, $vertex);
                $successors = self::successorsOf($graph, $vertex);

                foreach ($predecessors as $predecessor) {
                    self::addOutgoingEdges($graph, $predecessor, ...$successors);
                }
            }
            $graph->removeVertex($vertex);
        }

        return true;
    }

    /**
     * Add edges from one source vertex to multiple target vertices
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the source vertex
     * @param mixed $vertices - the target vertices
     */
    public static function addOutgoingEdges(GraphInterface $graph, VertexInterface $vertex, ...$vertices): void
    {
        if (!$graph->containsVertex($vertex)) {
            $graph->addVertex($vertex);
        }
        foreach ($vertices as $target) {
            if (!$graph->containsVertex($target)) {
                $graph->addVertex($target);
            }
            $graph->addEdge($vertex, $target);
        }
    }

    /**
     * Add edges from multiple source vertices to one target vertex
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the target vertex
     * @param mixed $vertices - the source vertices
     */
    public static function addIncomingEdges(GraphInterface $graph, VertexInterface $vertex, ...$vertices): void
    {
        if (!$graph->containsVertex($vertex)) {
            $graph->addVertex($vertex);
        }
        foreach ($vertices as $source) {
            if (!$graph->containsVertex($source)) {
                $graph->addVertex($source);
            }
            $graph->addEdge($source, $vertex);
        }
    }

    /**
     * Check if the vertex has direct successors
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the vertex
     *
     * @return bool
     */
    public static function vertexHasSuccessors(GraphInterface $graph, VertexInterface $vertex): bool
    {
        return count($graph->outgoingEdgesOf($vertex)) > 0;
    }

    /**
     * Check if the vertex has direct predecessors
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the vertex
     *
     * @return bool
     */
    public static function vertexHasPredecessors(GraphInterface $graph, VertexInterface $vertex): bool
    {
        return count($graph->incomingEdgesOf($vertex)) > 0;
    }
}

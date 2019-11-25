<?php

namespace Graphp\Path;

use ArrayObject;
use InvalidArgumentException;
use Graphp\GraphInterface;
use Graphp\GraphPathInterface;
use Graphp\GraphUtils;
use Graphp\Vertex\VertexInterface;

/**
 * Class GraphWalk
 *
 * @package Graphp\Path
 */
class GraphWalk extends AbstractGraphPath
{
    /**
     * The edge list making up the path
     *
     * @var array
     */
    protected $edgeList;

    /**
     * The vertex list making up the path
     *
     * @var array
     */
    protected $vertexList;

    /**
     * The walk unique hash
     *
     * @var string
     */
    private $hash;

    /**
     * Construct a new graph walk
     *
     * @param GraphPathInterface $other - the other walk
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        GraphInterface $graph,
        ?VertexInterface $startVertex = null,
        ?VertexInterface $endVertex = null,
        ?array $vertexList = null,
        ?array $edgeList = null,
        ?float $weight = 0.0
    ) {
        if (is_null($vertexList) && is_null($edgeList)) {
            throw new InvalidArgumentException("Vertex list and edge list cannot both be empty!");
        }

        if (
            !is_null($startVertex)
            && !is_null($vertexList)
            && !is_null($edgeList)
            && (count($edgeList) + 1 != count($vertexList))
        ) {
            throw new InvalidArgumentException(
                "VertexList and edgeList do not correspond to the same path (cardinality of vertexList +1 " .
                "must equal the cardinality of the edgeList)"
            );
        }

        $this->graph = $graph;
        $this->startVertex = $startVertex;
        $this->endVertex = $endVertex;
        $this->weight = $weight;
        $this->vertexList = $vertexList;
        $this->edgeList = $edgeList;
        $this->hash = $this->isEmpty() ? 1 : uniqid('', true);
    }

    /**
     * Get the edges making up the path
     *
     * @return array
     */
    public function getEdgeList(): array
    {
        return !is_null($this->edgeList) ? $this->edgeList : parent::getEdgeList();
    }

    /**
     * Get the vertices making up the path
     *
     * @return array
     */
    public function getVertexList(): array
    {
        return !is_null($this->vertexList) ? $this->vertexList : parent::getVertexList();
    }

    /**
     * Get the length of the path
     *
     * @return int
     */
    public function getLength(): int
    {
        if (!is_null($this->edgeList)) {
            return count($this->edgeList);
        }

        if (!is_null($this->vertexList)) {
            return count($this->vertexList) - 1;
        }

        return 0;
    }

    /**
     * Reverse the direction of the walk
     *
     * @return GraphPathInterface
     *
     * @throws InvalidArgumentException
     */
    public function reverse(): GraphPathInterface
    {
        $revVertexList = null;
        $revEdgeList = null;
        $revWeight = 0;

        if (!empty($this->vertexList)) {
            $revVertexList = array_reverse($this->vertexList);
            if ($this->graph->getType()->isUndirected()) {
                $revWeight = $this->weight;
            }

            // Check validity of the path.
            if (!$this->graph->getType()->isUndirected() && empty($this->edgeList)) {
                for ($i = 0; $i < count($revVertexList) - 1; $i += 1) {
                    $sourceVertex = $revVertexList[$i];
                    $targetVertex = $revVertexList[$i + 1];
                    $edge = $this->graph->getEdge($sourceVertex, $targetVertex);
                    if (is_null($edge)) {
                        throw new InvalidArgumentException(
                            "this walk cannot be reversed. The graph does not contain a reverse arc for arc "
                                . (string) $this->graph->getEdge($targetVertex, $sourceVertex)
                        );
                    } else {
                        $revWeight += $this->graph->getEdgeWeight($edge);
                    }
                }
            }
        }

        if (!empty($this->edgeList)) {
            if ($this->graph->getType()->isUndirected()) {
                $revEdgeList = array_reverse($this->edgeList);
                $revWeight = $this->weight;
            } else {
                $revEdgeList = [];
                $edgeIterator = (new ArrayObject(array_reverse($this->edgeList)))->getIterator();
                while ($edgeIterator->valid()) {
                    $edge = $edgeIterator->current();
                    $sourceVertex = $this->graph->getEdgeSource($edge);
                    $targetVertex = $this->graph->getEdgeTarget($edge);
                    $revEdge = $this->graph->getEdge($targetVertex, $sourceVertex);
                    if (is_null($revEdge)) {
                        throw new InvalidArgumentException(
                            "this walk cannot be reversed. The graph does not contain a reverse arc for arc " .
                            (string) $edge
                        );
                    }
                    $revEdgeList[] = $revEdge;
                    $revWeight += $this->graph->getEdgeWeight($revEdge);
                    $edgeIterator->next();
                }
            }
        }
        $gw = new GraphWalk(
            $this->graph,
            $this->endVertex,
            $this->startVertex,
            $revVertexList,
            $revEdgeList,
            $revWeight
        );
        return $gw;
    }

    /**
     * Concatenate the specified GraphWalk to the end of this GraphWalk
     *
     * @param GraphPathInterface $extension - the graph walk
     *
     * @return GraphPathInterface
     */
    public function concat(GraphPathInterface $extension): GraphPathInterface
    {
        if ($this->isEmpty()) {
            throw new InvalidArgumentException("An empty path cannot be extended");
        }
        if (!$this->endVertex->equals($extension->getStartVertex())) {
            throw new InvalidArgumentException(
                "This path can only be extended by another path if the end vertex of the " .
                "orginal path and the start vertex of the extension are equal."
            );
        }

        $concatVertexList = null;
        $concatEdgeList = null;

        if (!is_null($this->vertexList)) {
            $concatVertexList = array_merge($this->vertexList, array_slice($extension->getVertexList() ?? [], 1));
        }

        if (!is_null($this->edgeList)) {
            $concatEdgeList = array_merge($this->edgeList, array_slice($extension->getEdgeList() ?? [], 1));
        }

        $gw = new GraphWalk(
            $this->graph,
            $this->startVertex,
            $extension->getEndVertex(),
            $concatVertexList,
            $concatEdgeList,
            $this->weight + $extension->getWeight()
        );
        return $gw;
    }

    /**
     * Verify that the given path is feasible
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function verify(): bool
    {
        if ($this->isEmpty()) {
            return true;
        }

        if (!empty($this->vertexList)) {
            if (!$this->startVertex->equals($this->vertexList[0])) {
                throw new InvalidArgumentException(
                    "The start vertex must be the first vertex in the vertex list"
                );
            }
            if (!$this->endVertex->equals($this->vertexList[count($this->vertexList) - 1])) {
                throw new InvalidArgumentException(
                    "The end vertex must be the last vertex in the vertex list"
                );
            }
            // All vertices and edges in the path must be contained in the graph
            if (count(array_diff($this->vertexList, $this->graph->vertexSet()->getArrayCopy())) >= 1) {
                throw new InvalidArgumentException(
                    "Not all vertices in the path are contained in the graph"
                );
            }

            if (empty($this->edgeList)) {
                // Verify sequence
                $vertexIterator = (new ArrayObject($this->vertexList))->getIterator();
                $sourceVertex = $vertexIterator->current();
                $vertexIterator->next();
                while ($vertexIterator->valid()) {
                    $targetVertex = $vertexIterator->current();
                    if (is_null($this->graph->getEdge($sourceVertex, $targetVertex))) {
                        throw new InvalidArgumentException(
                            "The vertexList does not constitute to a feasible path. Edge (" .
                                (string) $sourceVertex . "," . (string) $targetVertex . ") does not exist in the graph."
                        );
                    }
                    $vertexIterator->next();
                    $sourceVertex = $targetVertex;
                }
            }
        }

        if (!empty($this->edgeList)) {
            if (!GraphUtils::testIncidence($this->graph, $this->edgeList[0], $this->startVertex)) {
                throw new InvalidArgumentException(
                    "The first edge in the edge list must leave the start vertex"
                );
            }
            if (count(array_diff($this->edgeList, $this->graph->edgeSet()->getArrayCopy())) >= 1) {
                throw new InvalidArgumentException(
                    "Not all edges in the path are contained in the graph"
                );
            }

            if (empty($this->vertexList)) {
                $vertex = $this->startVertex;
                foreach ($this->edgeList as $edge) {
                    if (!GraphUtils::testIncidence($this->graph, $edge, $vertex)) {
                        throw new InvalidArgumentException(
                            "The edgeList does not constitute to a feasible path. Conflicting edge: " .
                            (string) $edge
                        );
                    }
                    $vertex = GraphUtils::getOppositeVertex($this->graph, $edge, $vertex);
                }
                if (!$vertex->equals($this->endVertex)) {
                    throw new InvalidArgumentException(
                        "The path defined by the edgeList does not end in the endVertex."
                    );
                }
            }
        }

        if (!empty($this->vertexList) && !empty($this->edgeList)) {
            // Verify that the path is an actual path in the graph
            if (count($this->edgeList) + 1 != count($this->vertexList)) {
                throw new InvalidArgumentException(
                    "VertexList and edgeList do not correspond to the same path (cardinality of " .
                    "vertexList +1 must equal the cardinality of the edgeList)"
                );
            }

            $len = count($this->vertexList) - 1;
            $edges = $this->getEdgeList();
            for ($i = 0; $i < $len; $i += 1) {
                $startVertex = $this->vertexList[$i];
                $endVertex = $this->vertexList[$i + 1];
                $edge = $edges[$i];

                if ($this->graph->getType()->isDirected()) {
                    if (
                        !$this->graph->getEdgeSource($edge)->equals($startVertex)
                        || !$this->graph->getEdgeTarget($edge)->equals($endVertex)
                    ) {
                        throw new InvalidArgumentException(
                            "VertexList and edgeList do not form a feasible path"
                        );
                    }
                } else {
                    if (
                        !GraphUtils::testIncidence($this->graph, $edge, $startVertex)
                        || !GraphUtils::getOppositeVertex($this->graph, $edge, $startVertex)->equals($endVertex)
                    ) {
                        throw new InvalidArgumentException(
                            "VertexList and edgeList do not form a feasible path"
                        );
                    }
                }
            }
        }

        return true;
    }

    /**
     * Create an empty walk
     *
     * @param GraphInterface $graph - the graph
     *
     * @return GraphPathInterface
     */
    public static function emptyWalk(GraphInterface $graph): GraphPathInterface
    {
        return new GraphWalk($graph, null, null, [], [], 0.0);
    }

    /**
     * Create a walk consisting of one vertex
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $vertex - the vertex
     * @param float $weight - the weight
     *
     * @return GraphPathInterface
     */
    public static function singletonWalk(
        GraphInterface $graph,
        VertexInterface $vertex,
        ?float $weight = 0.0
    ): GraphPathInterface {
        return new GraphWalk(
            $graph,
            $vertex,
            $vertex,
            [$vertex],
            [],
            $weight
        );
    }

    /**
     * Get the path string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        if (!empty($this->vertexList)) {
            return implode('', array_map(function ($vertex) {
                return (string) $vertex;
            }, $this->vertexList));
        }

        if (!empty($this->edgeList)) {
            return implode('', array_map(function ($edge) {
                return (string) $edge;
            }, $this->edgeList));
        }

        return '';
    }

    /**
     * Check if two walks are equal
     *
     * @param GraphPathInterface $other - the other walk
     */
    public function equals(GraphPathInterface $other): bool
    {
        if ($this->hash == $other->getHash()) {
            return true;
        }

        if ($this->isEmpty() && $other->isEmpty()) {
            return true;
        }

        if ($this->isEmpty()) {
            return false;
        }

        if (
            !$this->startVertex->equals($other->getStartVertex())
            || !$this->endVertex->equals($other->getEndVertex())
        ) {
            return false;
        }

        if (empty($this->edgeList) && !$other->getGraph()->getType()->isAllowingMultipleEdges()) {
            return $this->vertexList == $other->getVertexList();
        }
        return $this->edgeList == $other->getEdgeList();
    }

    /**
     * Get the walk hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Check if the path is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return is_null($this->startVertex);
    }
}

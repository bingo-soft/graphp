[![Latest Stable Version](https://poser.pugx.org/bingo-soft/graphp/v/stable.png)](https://packagist.org/packages/bingo-soft/graphp)
[![Build Status](https://travis-ci.org/bingo-soft/graphp.png?branch=master)](https://travis-ci.org/bingo-soft/graphp)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bingo-soft/graphp/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bingo-soft/graphp/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/bingo-soft/graphp/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bingo-soft/graphp/?branch=master)

# Graphp

Graphp is a PHP library, which provides mathematical graph-theory objects and algorithms.

# Installation

Install Graphp, using Composer:

```
composer require bingo-soft/graphp
```

# Basic example

```php
use Graphp\GraphUtils;
use Graphp\Graph\Types\SimpleWeightedGraph;
use Graphp\Edge\DefaultWeightedEdge;
use Graphp\Vertex\Vertex;
use Graphp\Alg\Shortestpath\DijkstraShortestPath;

// Create vertices
$v1 = new Vertex("v1");
$v2 = new Vertex("v2");
$v3 = new Vertex("v3");
$v4 = new Vertex("v4");
$v5 = new Vertex("v5");

// Create a new graph and add vertices
$graph = new SimpleWeightedGraph(DefaultWeightedEdge::class);
$graph->addVertex($v1);
$graph->addVertex($v2);
$graph->addVertex($v3);
$graph->addVertex($v4);
$graph->addVertex($v5);

// Add weighted edges to the graph
$e12 = GraphUtils::addEdge($graph, $v1, $v2, 2.0);
$e13 = GraphUtils::addEdge($graph, $v1, $v3, 3.0);
$e24 = GraphUtils::addEdge($graph, $v2, $v4, 5.0);
$e34 = GraphUtils::addEdge($graph, $v3, $v4, 20.0);
$e45 = GraphUtils::addEdge($graph, $v4, $v5, 5.0);
$e15 = GraphUtils::addEdge($graph, $v1, $v5, 100.0);

// Find shortest path between v1 and v2 using Dijkstra shortest path algorithm. Returns [$e12]
$path = (new DijkstraShortestPath($graph))->getPath($v1, $v2)->getEdgeList();

// Returns [$e12, $e24]
$path = (new DijkstraShortestPath($graph))->getPath($v1, $v4)->getEdgeList();

// Returns [$e12, $e24, $e45]
$path = (new DijkstraShortestPath($graph))->getPath($v1, $v5)->getEdgeList();

// Returns [$e13, $e12, $e24]
$path = (new DijkstraShortestPath($graph))->getPath($v3, $v4)->getEdgeList();
```

## Features

* Graph types
    * Simple graph
    * Simple weighted graph
    * Simple directed graph
    * Simple directed weighted graph
    * ...

* Algorithms
    * Shortest path algorithms
        * Dijkstra shortest path

## Dependencies

Graphp depends on [Heap](https://github.com/bingo-soft/heap) library.

## Acknowledgements

Graphp draws inspiration from the [JGraphT](https://github.com/jgrapht/jgrapht) library.

## License

MIT
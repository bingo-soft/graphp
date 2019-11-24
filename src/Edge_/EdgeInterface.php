<?php

namespace Graphp\Edge;

use Graphp\Vertex\VertexInterface;

/**
 * Interface EdgeInterface
 *
 * @package Graphp\Edge
 */
interface EdgeInterface
{
    public function equals(EdgeInterface $other): bool;

    public function getHash(): string;

    public function getSource(): VertexInterface;

    public function getTarget(): VertexInterface;

    public function setSource(VertexInterface $vertex): void;

    public function setTarget(VertexInterface $vertex): void;
}

<?php

namespace graphp\edge;

use graphp\vertex\VertexInterface;

/**
 * Interface EdgeInterface
 *
 * @package graphp\edge
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

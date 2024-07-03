<?php

/*
 * This file is part of the NextPHP package.
 *
 * (c) Vedat Yıldırım <vedat@nextphp.io>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace NextPHP\Data\Persistence;

#[\Attribute]
/**
 * Class Entity
 *
 * A simple implementation of a PSR-7 HTTP message interface and PSR-12 extended coding style guide.
 * This class represents the Entity attribute.
 *
 * @package NextPHP\Data\Persistence
 */
class Entity
{
    public string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }
}
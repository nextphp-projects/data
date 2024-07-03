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
  * Class ManyToMany
  *
  * A simple implementation of a PSR-7 HTTP message interface and PSR-12 extended coding style guide.
  * This class represents the ManyToMany attribute.
  *
  * @package NextPHP\Data\Persistence
  */
class ManyToMany
{
    public string $targetEntity;
    public string $joinTable;
    public string $joinColumn;
    public string $inverseJoinColumn;
    public string $onDelete;

    public function __construct(
        string $targetEntity,
        string $joinTable,
        string $joinColumn,
        string $inverseJoinColumn,
        string $onDelete = 'CASCADE'
    )
    {
        $this->targetEntity = $targetEntity;
        $this->joinTable = $joinTable;
        $this->joinColumn = $joinColumn;
        $this->inverseJoinColumn = $inverseJoinColumn;
        $this->onDelete = $onDelete;
    }
}
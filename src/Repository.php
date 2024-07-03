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

namespace NextPHP\Data;

 #[\Attribute(\Attribute::TARGET_CLASS)]
 /**
  * Class Repository
  *
  * A simple implementation of a PSR-7 HTTP message interface and PSR-12 extended coding style guide.
  * This class represents the Repository attribute.
  *
  * @package NextPHP\Data\Persistence
  */
class Repository
{
    public function __construct(public string $entityClass) {}
}
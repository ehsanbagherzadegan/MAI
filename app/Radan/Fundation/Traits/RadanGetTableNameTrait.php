<?php

namespace App\Radan\Fundation\Traits;

/**
 * This file is part of Radan, 
 *
 * @license MIT
 * @package Radan/Traits
 */

trait RadanGetTableNameTrait
{
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}

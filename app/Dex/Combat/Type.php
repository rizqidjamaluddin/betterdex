<?php namespace BetterDex\Dex\Combat;

use Eloquent;

/**
 * @property int $id
 * @property string $identifier
 */
class Type extends Eloquent
{
    protected $table = 'types';
}
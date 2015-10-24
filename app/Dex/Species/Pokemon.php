<?php namespace BetterDex\Dex\Species;

use BetterDex\Dex\Combat\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $identifier
 * @property Collection|Type[] $types
 */
class Pokemon extends \Eloquent
{
    protected $table = 'pokemon';

    public function types()
    {
        return $this->belongsToMany(Type::class, 'pokemon_types', 'pokemon_id', 'type_id');
    }
}
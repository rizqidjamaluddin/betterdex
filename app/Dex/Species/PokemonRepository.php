<?php namespace BetterDex\Dex\Species;

use BetterDex\Dex\Support\Repository;

class PokemonRepository extends Repository
{
    protected $eagerLoads = ['types'];

    public function __construct(Pokemon $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $fragment
     * @return Pokemon
     */
    public function findByPartialName($fragment)
    {
        return $this->applyEagerLoads($this->model->newQuery()->where('identifier', 'like', strtolower($fragment) . '%'))
            ->first();
    }

}
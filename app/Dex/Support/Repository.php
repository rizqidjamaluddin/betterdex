<?php namespace BetterDex\Dex\Support;

use Illuminate\Database\Eloquent\Builder;

abstract class Repository
{

    protected $eagerLoads = [];

    /**
     * @var \Eloquent
     */
    protected $model;

    public function __construct(\Eloquent $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->applyEagerLoads($this->model->newQuery())->get();
    }

    public function getById($id)
    {
        return $this->applyEagerLoads($this->model->newQuery())->get($id);
    }

    /**
     * @param Builder $builder
     * @param Array   $added
     * @return Builder
     */
    protected function applyEagerLoads($builder, $added = [])
    {
        return $builder->with($this->eagerLoads + $added);
    }
}
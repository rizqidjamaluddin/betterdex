<?php namespace BetterDex\Dex\Combat;

use Illuminate\Support\Collection;
use stdClass;

class TypeEfficacyComparator
{
    protected $typeLookup = [];
    protected $singleElementDefensiveCache = [];
    protected $doubleElementDefensiveCache = [];
    protected $defendingTypeEfficacyCache = [];
    protected $defendingTypeEfficacyListCache = [];

    /**
     * @param Type[]|Collection $types
     * @return array
     */
    public function getDefences($types)
    {
        $this->warmUpTypeLookup();
        $result = [];
        if ($types->count() == 1) {
            $typeId = $types[0]->id;

            // check cache
            if (isset($this->singleElementDefensiveCache[$typeId])) {
                return $this->singleElementDefensiveCache[$typeId];
            }

            $firstElementEfficacy = $this->getDefendingTypeEfficacyData($typeId);
            /** @var stdClass $comparison */
            foreach ($firstElementEfficacy as $comparison) {
                $result[$this->typeLookup[$comparison->damage_type_id]] = $comparison->damage_factor;
            }

            // insert to cache
            $this->singleElementDefensiveCache[$typeId] = $result;

            return $result;
        } elseif ($types->count() == 2) {
            $firstTypeId = $types[0]->id;
            $secondTypeId = $types[1]->id;
            $cacheKey = $this->makeDualElementCacheKey($firstTypeId, $secondTypeId);

            if (isset($this->doubleElementDefensiveCache[$cacheKey])) {
                return $this->doubleElementDefensiveCache[$cacheKey];
            }

            $firstElementEfficacy = $this->getDefendingTypeEfficacyData($firstTypeId);
            $secondElementEfficacy = $this->getDefendingTypeEfficacyList($secondTypeId);
            /** @var stdClass $comparison */
            foreach ($firstElementEfficacy as $comparison) {
                $result[$this->typeLookup[$comparison->damage_type_id]] = $comparison->damage_factor *
                    $secondElementEfficacy[$comparison->damage_type_id] / 100;
            }

            // insert to cache
            $this->doubleElementDefensiveCache[$cacheKey] = $result;

            return $result;
        }
        return [];
    }

    protected function makeDualElementCacheKey($id1, $id2)
    {
        $ids = [$id1, $id2];
        sort($ids);
        return implode('+', $ids);

    }

    public function preloadAllData()
    {
        /** @var \stdClass[] $data */
        $data = \DB::table('type_efficacy')->get();


        // build defending type data
        $dataPerDefendingType = [];
        $listDataPerDefendingType = [];
        foreach ($data as $typeData) {
            if (!isset($dataPerDefendingType[$typeData->target_type_id])) {
                $dataPerDefendingType[$typeData->target_type_id] = [];
            }
            if (!isset($listDataPerDefendingType[$typeData->target_type_id])) {
                $listDataPerDefendingType[$typeData->target_type_id] = [];
            }

            $dataPerDefendingType[$typeData->target_type_id][] = $typeData;
            $listDataPerDefendingType[$typeData->target_type_id][$typeData->damage_type_id] = $typeData->damage_factor;
        }

        // insert defending type data into cache
        foreach ($dataPerDefendingType as $defendingTypeId => $defendingData) {
            $this->defendingTypeEfficacyCache[$defendingTypeId] = $defendingData;
        }
        foreach ($listDataPerDefendingType as $defendingTypeId => $defendingListData) {
            $this->defendingTypeEfficacyListCache[$defendingTypeId] = $defendingListData;
        }

    }

    protected function getDefendingTypeEfficacyData($defendingTypeId)
    {
        if (isset($this->defendingTypeEfficacyCache[$defendingTypeId])) {
            return $this->defendingTypeEfficacyCache[$defendingTypeId];
        }

        $data = \DB::table('type_efficacy')->where('target_type_id', $defendingTypeId)->get();
        $this->defendingTypeEfficacyCache[$defendingTypeId] = $data;
        return $data;
    }

    protected function getDefendingTypeEfficacyList($defendingTypeId)
    {
        if (isset($this->defendingTypeEfficacyListCache[$defendingTypeId])) {
            return $this->defendingTypeEfficacyListCache[$defendingTypeId];
        }

        $data = \DB::table('type_efficacy')
                   ->where('target_type_id', $defendingTypeId)
                   ->lists('damage_factor', 'damage_type_id');
        $this->defendingTypeEfficacyListCache[$defendingTypeId] = $data;
        return $data;
    }

    protected function warmUpTypeLookup()
    {
        if (!$this->typeLookup) {
            $this->typeLookup = \DB::table('types')->lists('identifier', 'id');
        }
    }

}
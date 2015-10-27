<?php namespace BetterDex\Dex\Species;

class PokemonStatRepository
{
    protected $map = [
      '1' => "hp",
      '2' => 'attack',
      '3' => 'defense',
      '4' => 'special-attack',
      '5' => 'special-defense',
      '6' => 'speed',
    ];

    protected $cache = [];

    public function preloadStats()
    {
        $fullData = \DB::table('pokemon_stats')->get();
        /** @var \stdClass $datum */
        foreach ($fullData as $datum) {
            if (!isset($this->cache[$datum->pokemon_id])) {
                $this->cache[$datum->pokemon_id] = [];
            }
            $this->cache[$datum->pokemon_id][$this->map[$datum->stat_id]] = $datum->base_stat;
        }

    }

    public function getStatsFor(Pokemon $pokemon)
    {
        if (isset($this->cache[$pokemon->id])) {
            return $this->cache[$pokemon->id];
        }

        $result = [];
        $stats = \DB::table('pokemon_stats')->where('pokemon_id', $pokemon->id)->lists('base_stat', 'stat_id');
        foreach ($stats as $id => $stat) {
            $result[$this->map[$id]] = $stat;
        }
        $this->cache[$pokemon->id] = $result;
        return $result;
    }
}
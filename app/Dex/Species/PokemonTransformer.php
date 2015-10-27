<?php namespace BetterDex\Dex\Species;

use BetterDex\Dex\Combat\Type;
use BetterDex\Dex\Combat\TypeEfficacyComparator;

class PokemonTransformer
{

    /**
     * @var TypeEfficacyComparator
     */
    private $efficacyComparator;
    /**
     * @var PokemonStatRepository
     */
    private $statRepository;

    public function __construct(TypeEfficacyComparator $efficacyComparator, PokemonStatRepository $statRepository)
    {
        $this->efficacyComparator = $efficacyComparator;
        $this->statRepository = $statRepository;
    }

    public function transform(Pokemon $pokemon)
    {
        $number = str_pad($pokemon->id, 3, '0', STR_PAD_LEFT);
        return [
            'number' => $number,
            'name' => $pokemon->identifier,
            'types' => $pokemon->types->map(function (Type $type) {
                return $type->identifier;
            }),
            'sprite' => $number,
            'type-defenses' => $this->efficacyComparator->getDefences($pokemon->types),
            'stats' => $this->statRepository->getStatsFor($pokemon),
        ];
    }
}
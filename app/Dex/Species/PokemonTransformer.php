<?php namespace BetterDex\Dex\Species;

use BetterDex\Dex\Combat\Type;
use BetterDex\Dex\Combat\TypeEfficacyComparator;

class PokemonTransformer
{

    /**
     * @var TypeEfficacyComparator
     */
    private $efficacyComparator;

    public function __construct(TypeEfficacyComparator $efficacyComparator)
    {
        $this->efficacyComparator = $efficacyComparator;
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
        ];
    }
}
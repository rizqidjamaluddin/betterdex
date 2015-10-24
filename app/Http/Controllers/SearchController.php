<?php namespace BetterDex\Http\Controllers;

use BetterDex\Dex\Combat\TypeEfficacyComparator;
use BetterDex\Dex\Species\Pokemon;
use BetterDex\Dex\Species\PokemonRepository;
use BetterDex\Dex\Species\PokemonTransformer;
use Input;

class SearchController extends Controller
{

    /**
     * @var PokemonRepository
     */
    private $pokemonRepository;
    /**
     * @var PokemonTransformer
     */
    private $pokemonTransformer;
    /**
     * @var TypeEfficacyComparator
     */
    private $typeEfficacyComparator;

    public function __construct(PokemonRepository $pokemonRepository, PokemonTransformer $pokemonTransformer,
                                TypeEfficacyComparator $typeEfficacyComparator)
    {
        $this->pokemonRepository = $pokemonRepository;
        $this->pokemonTransformer = $pokemonTransformer;
        $this->typeEfficacyComparator = $typeEfficacyComparator;
    }

    public function index()
    {
        return view('index');
    }

    public function find()
    {
        if (Input::exists('search')) {
            $search = Input::get('search');
            $result = $this->pokemonRepository->findByPartialName($search);

            if (!$result) {
                return $this->blankSuccess();
            }

            $result = $this->pokemonTransformer->transform($result);
            return $this->success($result);
        } else {
            // instruct calculators to eager load
            $this->typeEfficacyComparator->preloadAllData();


            $result = $this->pokemonRepository->getAll()->map(function (Pokemon $pokemon) {
               return  $this->pokemonTransformer->transform($pokemon);
            });
            return $this->success($result);
        }
    }

}
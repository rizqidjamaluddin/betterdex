<?php

namespace BetterDex\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($data)
    {
        if ($data instanceof Collection || is_array($data)) {
            return [
                'success' => true,
                'data' => $data,
            ];
        } else {
            return [
              'success' => true,
              'data' => [$data],
            ];
        }
    }

    protected function blankSuccess()
    {
        return [
            'success' => true,
            'data' => [],
        ];
    }
}

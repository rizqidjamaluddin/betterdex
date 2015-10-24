<?php

Route::get('/', \BetterDex\Http\Controllers\SearchController::class . '@index');

Route::get('/api/pokemon', \BetterDex\Http\Controllers\SearchController::class . '@find');
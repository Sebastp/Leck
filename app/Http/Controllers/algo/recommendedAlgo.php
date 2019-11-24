<?php

namespace leck\Http\Controllers\algo;

use Illuminate\Http\Request;
use leck\Http\Controllers\Controller;

use leck\Follow;


class recommendedAlgo extends Controller
{
    public static function getProfiles($limit = 10)
    {
      $popularProfIds = Follow::getMostPopular_ids($limit+10);
      shuffle($popularProfIds);
      return array_slice($popularProfIds, 0, $limit, true);
    }


    public static function recommWritings($limit = 8, $skip = null)
    {
      return hotCommAlgo::getAll(20, 10);

      // ->skip(10)->limit(10);

    }
}

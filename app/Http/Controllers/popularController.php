<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use leck\Http\Controllers\algo\popularAlgo;

class popularController extends Controller
{
  public static function showProfiles()
  {
    $data = array(
      'prof_popular' => popularAlgo::getAll_Profiles(30)
    );

    return view('layouts.popular.profiles')->with($data);
  }
}

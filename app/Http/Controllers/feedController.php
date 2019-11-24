<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use leck\Http\Controllers\algo\feedAlgo;
use leck\Http\Controllers\followings;
use leck\Follow;

class feedController extends Controller
{
  public static function showFeedPage()
  {
    $data = array(
    'feed_writings' => feedAlgo::getAll(),
    'usr_follows' => followings::getUsersFollows(Auth::id(), 7),
    );

    return view('layouts.feed')->with($data);
  }
}

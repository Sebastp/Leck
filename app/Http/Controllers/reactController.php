<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use leck\Like;


class reactController extends Controller
{
  public function saveLike(Request $request, $str_id, $writing_id)
  {
    $likes_nr = $request->likes;
    return  Like::cudLike($writing_id, $likes_nr);
  }

}

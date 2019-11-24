<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


use leck\Http\Controllers\profileController;
use leck\Http\Controllers\algo\feedAlgo;
use leck\Http\Controllers\algo\trendingAlgo;
use leck\Http\Controllers\algo\popularAlgo;
use leck\Http\Controllers\algo\categoriesAlgo;
use leck\Http\Controllers\algo\recommendedAlgo;

use leck\Writing;
use leck\User;



class main extends Controller
{
    public function viewRedirector($str_id, $_2_path = NULL){
      if (!empty($_2_path)) {
        return main::InProfileRedirector($str_id, $_2_path);
      }else {
        return profileController::show($str_id);
      }
    }


    public function InProfileRedirector($str_id, $_2_path){
      return profileController::ProfileRedirector($str_id, $_2_path);
    }


    public function authFunc(){
      if (Auth::check()) {
        return $this->userView();
      }else {
        return $this->index();
      }
    }

    public function index(){

      $data = $this->getBasicData();

      return view('layouts.home')->with($data);
    }


    public function userView(){
      $this->middleware('auth');

      $data = $this->getBasicData();

      $moreData = ['u_feed' => feedAlgo::getAll(6),
                   'usr_recent' => feedAlgo::getHistory(Auth::id(), 2, true)];

      $data = array_merge($data, $moreData);
      return view('layouts.home')->with($data);
    }


    public function getBasicData(){
      $data = array(
      'writings_tranding' => trendingAlgo::getAll(1, 5),
      'writings_bestIn' => categoriesAlgo::getBestIn(3),
      'recom_profiles' => popularAlgo::getAll_Profiles(4),
      'recom_writings' => recommendedAlgo::recommWritings(10)
      );

      return $data;
    }

    public static function scrollDownload(Request $request)
    {
      $visNr = $request->visibleNr;
      $rules = array(
        'title' => 'required|integer',
      );
      $vaildator = Validator::make([
        'title' => $visNr
      ], $rules);

      if ($vaildator->fails()) {
        return response()->json([
          'success' => false
        ]);
      }

      $wrData = recommendedAlgo::recommWritings($visNr, 10);
      $writingsArr = [];
      foreach ($wrData as $data) {
        $wrHtml = view('partials.elem.writings._writing-full-small', ['writing' => $data])->render();
        array_push($writingsArr, $wrHtml);
      }

      return response()->json([
        'success' => true,
        'appendData' => $writingsArr
      ]);
    }
}

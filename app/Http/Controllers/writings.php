<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use leck\Http\Controllers\main;
use leck\Http\Controllers\profileController;

use leck\User;
use leck\Writing;
use leck\Section;
use leck\Split;
use leck\Writing_privilege;
use leck\Inook_progress;
use leck\Writing_comment;



class writings extends Controller
{

  public function inookProgress(Request $request, $str_id, $writing_id){
    $usr_id = User::select('id')->where('str_id', '=', $str_id)->get()[0]->id;

    if (Writing::where([['id', '=', $writing_id], ['type', '=', 'inook']])->exists() && !empty($request->prog) &&
       Writing_privilege::where([
       ['user_id', '=', $usr_id], ['writing_id', '=', $writing_id],
       ['type', '=', 'author'], ['public', '=', 1]])->exists())
    {
      $split_id = $request->prog['split_id'];

      $inkModelResp = Inook_progress::newProg($writing_id, $split_id);
      $inkModelResp =  1;
      if ($inkModelResp) {
        $nextSectId = Split::where('id', $split_id)->select('next_id')->get()[0]->next_id;

        $data = Section::getFullSectionById($nextSectId, ['title', 'active_split']);
        return response()->json([
          'data' => view('layouts.writings._inook-section', ['sections' => $data])->render(),
          'success' => true
        ]);
      }else {
        return response()->json([
          'success' => false,
        ]);
      }
    }else {
      return response()->json([
        'success' => false,
        'msg' => "We couldn't authorize this action"
      ]);
    }
  }




  public function getWritingInfo(Request $request, $str_id, $writing_id){
    if ( User::where('str_id', '=', $str_id)->exists() ) {
      $usr_id = User::select('id')->where('str_id', '=', $str_id)->get()[0]->id;
      if (Writing::where('id', '=', $writing_id)->exists() && !empty($request->type) &&
         Writing_privilege::where([
         ['user_id', '=', $usr_id], ['writing_id', '=', $writing_id],
         ['type', '=', 'author'], ['public', '=', 1]])->exists())
      {
        switch ($request->type) {
          case 'loadNew':
            if (!empty($request->ltst_section)) {
              $prevSection = Inook_progress::getPrevSection($request->ltst_section, Auth::user()->id);
              if ($prevSection == NULL) {
                return response()->json([
                  'data' => NULL,
                  'success' => true
                ]);
              }elseif (!$prevSection) {
                $retFalse = true;
              }
              $data = Section::getFullSectionById($prevSection, ['title', 'active_split']);
              return response()->json([
                'success' => true,
                'data' => view('layouts.writings._inook-section', ['sections' => $data])->render()
              ]);
            }
            break;
          default:
            $retFalse = true;
            break;
        }
      }else {
        $retFalse = true;
      }
    }
    else {
      $retFalse = true;
    }

    if (!empty($retFalse) && !$retFalse) {
      return response()->json([
        'success' => false
      ]);
    }
  }


  public function commCreate(Request $request, $str_id, $writing_id){
    if (!empty($request->val && is_string($request->val)) ) {

      $comm = (object) [];

      $comm->value = $request->val;
      $comm->reply = $request->reply;

      $rules = array(
        'value' => 'string',
        'reply' => 'string|nullable'
      );

      $vaildator = Validator::make([
      'value' => $comm->value, 'reply' => $comm->reply
      ], $rules);
      if ($vaildator->fails()) {
        return response()->json([
          'success' => false
        ]);
      }

      $comm = Writing_comment::createNew($writing_id, $comm);

      $comm->author = (object) [];
      $comm->author->str_id = Auth::user()->str_id;
      $comm->author->avatar_path = User::getAvatarPath(Auth::user()->avatar);
      $comm->author->nickname = Auth::user()->nickname;

      $domRender = view('partials.elem.comments._comm-writing-0')->with(['comm' => $comm])->render();


      return response()->json([
        'success' => true,
        'dom' => $domRender
      ]);
    }
    else {
      return response()->json([
        'success' => false
      ]);
    }
  }

}

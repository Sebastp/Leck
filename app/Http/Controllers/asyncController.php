<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use leck\Page;
use leck\Page_rate;
use leck\Page_comment_rate;
use leck\Page_comment;
use leck\User_list;
use leck\List_item;
use leck\Book_edition;

use leck\Http\Controllers\pageController;



class asyncController extends Controller
{




  public static function ratePage_ths(Request $request, $str_id)
  {
    $page_id = $str_id;

    if ($request->val == 'th-up') {
      $rateVal = 5;
    }elseif ($request->val == 'th-down') {
      $rateVal = 1;
    }else {
      $rateVal = false;
    }



    $rules = array(
      'page_id' => 'required',
      'rate' => 'required|max:5|min:1|integer'
    );

    $vaildator = Validator::make(['page_id' => $page_id, 'rate' => $rateVal], $rules);

    if ($vaildator->fails()) {
      return response()->json([
        'err' => $vaildator->errors()->all()
      ]);
    }else {
      if (session('curr_incarnation')) {
        $lg_id = session('curr_incarnation')['id'];
      }else {
        $lg_id = NULL;
      }



      if (!Page_rate::where([['user_id', Auth::user()->id], ['rate', '=', $rateVal], ['page_id', '=', $page_id]])->exists()) {
        return Page_rate::ratePage(Auth::user()->id, $rateVal, $page_id);
      }else {
        Page_rate::rateDel(Auth::user()->id, $page_id);
      }

    }

  }




  protected static function commentPage(Request $request, $str_id)
  {
    if (!empty($request->nonPrivileged)) {
      return response()->json([
        'err' => $request->nonPrivileged
      ]);
    }

    if (!empty($request->rate)) {
      if ($request->reply_id != NULL) {
        return response()->json([
          'err' => 'Something went wrong'
        ]);
      }else {
        return asyncController::commentRatePage($request, $str_id);
      }
    }
    $commVal = $request->body;


    $rules = array(
      'page_id' => 'required',
      'comment' => 'required|min:2',
      'reply_id' => 'integer|exists:page_comments,id'
    );

    if ($request->reply_id != NULL) {
      $vaildator = Validator::make(['page_id' => $str_id, 'comment' => $commVal, 'reply_id' => $request->reply_id], $rules);
    }else {
      $vaildator = Validator::make(['page_id' => $str_id, 'comment' => $commVal], $rules);
    }


    if ($vaildator->fails()) {
      return response()->json([
        'err' => $vaildator->errors()->all()
      ]);
    }else {
      $resCommData = Page_comment::commentPage($commVal, Auth::user()->id, $str_id, true, $request->reply_id);


      $resCommData->author_strid = Auth::user()->str_id;
      $resCommData->author_avatar = Auth::user()->avatar;
      $resCommData->author_fname = Auth::user()->f_name;
      $resCommData->author_lname = Auth::user()->l_name;
      $resCommData->comment_body = $resCommData->comment;
      // $resCommData->usr_rate = 1;
      $resCommData->comment_id = $resCommData->id;
      $resCommData->usr_comm = 1;

      if (session('curr_incarnation') and !empty($resCommData->lage_id)) {
        $resCommData->lage_name = session('curr_incarnation')['name'];
        $resCommData->lage_strid = session('curr_incarnation')['str_id'];
        $resCommData->lage_avatar = session('curr_incarnation')['avatar'];
      }

      $data = array(
          'comm' => $resCommData,
      );
      return view('partials.elem._page-comm')->with($data);
    }

  }




  protected static function commentRatePage($request, $str_id)
  {
    $commVal = $request->body;
    $rateVal = $request->rate;

    $rules = array(
      'page_id' => 'required',
      'rate' => 'required|max:5|min:1|integer',
      'comment' => 'required|min:2'
    );

    $vaildator = Validator::make(['page_id' => $str_id, 'rate' => $rateVal, 'comment' => $commVal], $rules);


    if ($vaildator->fails()) {
      return response()->json([
        'err' => $vaildator->errors()->all()
      ]);
    }else {
      $query = Page_comment::commentPage($commVal, Auth::user()->id, $str_id, true);
      $page_id = $query->page_id;
      $comm_id = $query->id;
      Page_rate::ratePage(Auth::user()->id, $rateVal, $page_id, $comm_id);

    }


  }




  protected static function commentDel(Request $request, $str_id, $comm_id)
  {
    $bookObj = new Book_edition;

    $page_id = $str_id;


    $rules = array(
      'page_id' => 'required',
      'comment_id' => 'required|integer'
    );

    $vaildator = Validator::make(['page_id' => $page_id, 'comment_id' => $comm_id], $rules);


    if ($vaildator->fails()) {
      return response()->json([
        'err' => $vaildator->errors()->all()
      ]);
    }else {
      $bkEdsIds = $bookObj->getEditionsPageIds($page_id);
      Page_rate::rateDel(Auth::user()->id, $bkEdsIds);
      return Page_comment::commentDel(Auth::user()->id, $bkEdsIds, $comm_id);
    }

  }






  protected static function commentEdit(Request $request, $str_id, $comm_id)
  {

    $change = $request->body;

    $rules = array(
      'comment_id' => 'required|integer',
      'change' => 'required|min:1',
      'page_id' => 'required'
    );

    $vaildator = Validator::make(['comment_id' => $comm_id, 'change' => $change, 'page_id' => $str_id], $rules);


    if ($vaildator->fails()) {
      return response()->json([
        'err' => $vaildator->errors()->all()
      ]);
    }else {
      return Page_comment::commentEdit(Auth::user()->id, $str_id, $comm_id, $change);
    }

  }








  public static function rateComm(Request $request, $page_id, $commid)
  {

    $rateVal = $request->val;


    $refHdrs = explode('/', $request->header('referer'));
    // $valFromhdr = $refHdrs[array_search("page", $refHdrs)+1];
    $valFromhdr = $page_id;
    $valExValid = pageController::valExist($valFromhdr);


    if (!$valExValid[0]) {
      return response()->json([
        'err' => 'Something went wrong'
      ]);
    }

    if ($valExValid[1] != NULL) {
      $datainfo = $valExValid[1];
      $page_id = $datainfo->id;
    }else {
      $page_id = $valFromhdr;
    }

    if (!Page_comment::where([['page_id', '=', $page_id], ['id', '=', $commid]])->exists()) {
      return response()->json([
        'err' => 'Comment does not exist'
      ]);
    }


    $rules = array(
      'comment_id' => 'required|integer',
      'rate' => 'required|max:1|min:0|integer'
    );

    $vaildator = Validator::make(['comment_id' => $commid, 'rate' => $rateVal], $rules);

    if ($vaildator->fails()) {
      return response()->json([
        'err' => $vaildator->errors()->all()
      ]);
    }else {
      return Page_comment_rate::CommRateDel(Auth::user()->id, $commid, $rateVal);
    }
  }

}

<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Like extends Model
{
  protected $fillable = [
      'user_id', 'writing_id', 'likes', 'created_at'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;


  public static function cudLike($writing_id, $likes_nr)
  {
    $baseQury = self::where([['user_id', '=', Auth::id()], ['writing_id', '=', $writing_id]]);
    if ($baseQury->exists()) {
      if ($likes_nr != 0) {
        $prevLikeNr = $baseQury->select('likes')->get()[0]->likes;

        if ($prevLikeNr != $likes_nr) {
          $quryResp = $baseQury->update([
            'likes' => $likes_nr
          ]);
        }else {
          $quryResp = 1;
        }
      }else {
        $quryResp = $baseQury->delete();
      }
    }else {
      if ($likes_nr != 0) {
        $quryResp = self::create([
          'user_id' => Auth::id(),
          'writing_id' => $writing_id,
          'likes' => $likes_nr
        ]);
      }
    }

    if (!$quryResp) {
      return response()->json([
        'success' => false
      ]);
    }else {
      return response()->json([
        'success' => true
      ]);
    }
  }



  public static function getWritingLikes($writing_id)
  {
    $baseQury = self::where('writing_id', '=', $writing_id)->select('likes');
    if (!$baseQury->exists()) {
      return 0;
    }else {
      return $baseQury->sum('likes');
    }
  }

  public static function getUser_WrtLikes($writing_id, $usr_id)
  {
    $baseQury = self::where([['writing_id', '=', $writing_id], ['user_id', '=', $usr_id]])->select('likes');
    if (!$baseQury->exists()) {
      return 0;
    }else {
      return $baseQury->get()[0]->likes;
    }
  }
}

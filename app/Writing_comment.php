<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use leck\User;


class Writing_comment extends Model
{
  protected $fillable = [
      'id', 'user_id', 'value', 'reply', 'writing_id', 'created_at', 'updated_at'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;


  public static function createNew($writing_id, $comm)
  {
    $newId = Writing_comment::uid(9, 'id');


    $quryInfo = Writing_comment::create([
        'id' => $newId,
        'user_id' => Auth::user()->id,
        'value' => $comm->value,
        'reply' => $comm->reply,
        'writing_id' => $writing_id
    ]);

    $quryInfo->created_at = str_replace(',', '', Carbon::now()->toFormattedDateString());
    return $quryInfo;
  }


  public static function getByWritingId($writing_id, $maxLimit = null)
  {
    $mainQury =  self::where([['writing_id', $writing_id], ['reply', '=', null]]);
    if (!$mainQury->exists()) {
      return null;
    }

    $wrComms = $mainQury->join('users', 'users.id', '=', 'writing_comments.user_id')
                        ->select('writing_comments.id', 'writing_comments.user_id', 'writing_comments.value', 'writing_comments.writing_id', 'writing_comments.created_at', 'writing_comments.updated_at',
                                  'users.str_id as au_sid', 'users.nickname as au_ni', 'users.avatar as au_av')->limit($maxLimit)->get();

    foreach ($wrComms as &$singleComm) {
      if ($singleComm->created_at == $singleComm->updated_at) {
        $singleComm->updated_at == null;
      }
      $singleComm->created_at = str_replace(',', '', Carbon::parse($singleComm->created_at)->toFormattedDateString());

      $singleComm->author = (object) [];
      $singleComm->author->str_id = $singleComm->au_sid;
      $singleComm->author->avatar_path = User::getAvatarPath($singleComm->au_av);
      $singleComm->author->nickname = $singleComm->au_ni;

    }
    return $wrComms;
  }
}

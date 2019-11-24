<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;

class SysMsg_writing extends Model
{
  protected $fillable = [
      'id', 'user_id', 'message_type', 'writing_id', 'created_at'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;

  public static $message_typesArr = [
    'path_history' => 'Sorry, author of this story decided to delete path that you were currently on. You have been restored to your previous state'
  ];

  public static function w_pathHistory($writing_id, $user_ids){
    $new_id = Split::uid(9, 'id');
    $arrOfInstr = [];

    foreach ($user_ids as $u_id) {
      $qury = [
        'id' => $new_id,
        'user_id' => $u_id,
        'message_type' => 'path_history',
        'writing_id' => $writing_id
      ];
      array_push($arrOfInstr, $qury);
    }

    self::insert($arrOfInstr);
  }


  public static function getMsgs($writing_id, $user_id){
    $nQury = self::where([['writing_id', '=', $writing_id], ['user_id', '=', $user_id]]);
    if ($nQury->exists()) {
      $msgsObj = $nQury->select('message_type')->get();
      $arrOfText = [];
      foreach ($msgsObj as $msg) {
        array_push($arrOfText, self::$message_typesArr[$msg->message_type]);
      }
      $nQury->delete();
      return $arrOfText;
    }else {
      return null;
    }
  }

}

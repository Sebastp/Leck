<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Writing_traffic extends Model
{
  protected $fillable = [
      'writing_id', 'user_id', 'created_at'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;


  public static function grab($writing_id, $user_id)
  {
    if (!empty($user_id)) {
      $currTime = Carbon::now()->toDateTimeString();
      $ltstQury = self::where([['user_id', $user_id], ['created_at', '>', Carbon::now()->subHours(12)->toDateTimeString()]]);
      if ($ltstQury->exists()) {
        $ltst_date = $ltstQury->select('created_at')->get()[0]->created_at;
        self::where([['user_id', '=', $user_id], ['created_at', '=', $ltst_date]])->update(['created_at' => $currTime]);
      }else {
        self::create([
          'writing_id' => $writing_id,
          'user_id' => $user_id
        ]);
      }
    }else {
      Writing_traffic::create([
        'writing_id' => $writing_id,
        'user_id' => $user_id
      ]);
    }

  }

}

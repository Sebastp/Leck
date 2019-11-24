<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use leck\Http\Controllers\fileServe;


class Writing_file extends Model
{
  protected $fillable = [
      'file_id', 'position_after', 'atribute', 'paragraph_id', 'created_at'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;



  public static function CreateUpdate($file, $writing_id = null, $ifExCover = false)
  {
    $file_id = $file->file_id;
    $file_pos = $file->position_after;
    if ($file_pos == 'null' || !in_array($file_pos, ['top', 'mid', 'down'])) {
      $file_pos = Null;
    }

    $file_atr = $file->atribute;
    if ($file_atr == 'null') {
      $file_atr = Null;
    }


    $file_paragraph_id = $file->paragraph_id;

    if ($file_paragraph_id == null && !in_array($file_pos, ['top', 'mid', 'down'])) {
      $file_pos = 'mid';
    }

    $rules = array(
      'file_id' => 'required|string',
      'position_after' => 'nullable|string',
      'paragraph_id' => 'nullable|string',
      'atribute' => 'nullable|string',
          Rule::in([750, 1005, 1])
    );

    if(!empty($file_paragraph_id)) {
      $quryFrst = Writing_file::where([
        ['paragraph_id', '=', $file_paragraph_id],
        ['position_after', '=', NULL],
        ['file_id', '!=', $file_id]
      ]);
      if ($quryFrst->exists()) {
        self::removeByParId($quryFrst->select('paragraph_id')->get()[0]->paragraph_id);
      }
    }

    $qury = Writing_file::where([
      ['file_id', '=', $file_id],
      ['paragraph_id', '=', $file_paragraph_id]
    ]);


    /*if (!empty($file_paragraph_id)) {
      if (empty($prgrph_position)) {
        $quryFrst = Writing_file::where([
          ['paragraph_id', '=', $file_paragraph_id],
          ['position_after', '=', NULL]
        ]);
        if ($quryFrst->exists()) {
          if ($quryFrst->select('file_id')->get()[0]->file_id != $file_id) {
            $quryFrst->update(['position_after' => $file_id]);
          }
        }
      }else {
        $chckQury = Writing_file::where([
          ['paragraph_id', '=', $file_paragraph_id],
          ['position_after', '=', $file_pos]
        ]);
        if ($chckQury->exists()) {
          $chckQury->update(['position_after' => $file_id]);
        }
      }
    }*/



    $vaildator = Validator::make([
      'file_id' => $file_id, 'paragraph_id' => $file_paragraph_id,
      'atribute' => $file_atr, 'position_after' => $file_pos
    ], $rules);

    if ($vaildator->fails()) {
      return response()->json([
        'success' => false,
      ]);
    }


    if ($ifExCover) {
      $quryCov = Writing_file::where([
        ['file_id', '=', $file_id],
        ['paragraph_id', '=', NULL]
      ]);

      if (!$quryCov->exists()) {
        return response()->json([
          'success' => false,
        ]);
      }else {
        $quryCov->update([
          'position_after' => $file_pos,
          'atribute' => $file_atr,
          'paragraph_id' => $file_paragraph_id
        ]);
      }
    }else {
      if ($qury->exists()) {
        $qury->update([
          'atribute' => $file_atr,
          'position_after' => $file_pos
        ]);
      }else {
        Writing_file::create([
          'file_id' => $file_id,
          'atribute' => $file_atr,
          'paragraph_id' => $file_paragraph_id,
          'position_after' => $file_pos
        ]);
      }
    }


    return response()->json([
      'success' => true
    ]);
  }


  public static function getByParId($paragraph_id){
    if (Writing_file::where('paragraph_id', '=', $paragraph_id)->exists()) {
      return Writing_file::where('paragraph_id', '=', $paragraph_id)->select('file_id', 'position_after', 'atribute')->get();
    }else {
      return null;
    }
  }


  public static function removeByParId($paragraph_id, $file_ids = []){
    if (empty($file_ids)) {
      $toDelIds = Writing_file::where('paragraph_id', '=', $paragraph_id)->select('file_id')->get()->pluck('file_id');

      if (self::where('paragraph_id', '=', null)->whereIn('file_id', $toDelIds)->exists()) {
        $coverFileId = self::where('paragraph_id', '=', null)->whereIn('file_id', $toDelIds)->select('file_id')->get()[0]->file_id;
        $toDelIds = array_diff( $toDelIds, [$coverFileId] ); //to delete without cover file
      }

      self::where('paragraph_id', '=', $paragraph_id)->delete();
    }else {
      $toDelIds = $file_ids;
      self::where('paragraph_id', '=', $paragraph_id)->whereIn('file_id', $file_ids)->delete();
    }

    $toDelPaths = [];
    $storage_path = storage_path('app/content');
    foreach ($toDelIds as $imgId) {
      $sizeFilesArr = fileServe::searchByName($storage_path, $imgId);
      foreach ($sizeFilesArr as $sizeFile) {
        $imgPath = 'content/'.$sizeFile.'.jpg';
        array_push($toDelPaths, $imgPath);
      }
    }

    Storage::delete($toDelPaths);
  }



  public static function deleteCover($writing_id){
    $WRq = Writing::where('id', '=', $writing_id);
    $file_id = $WRq->select('cover')->get()[0]->cover;
    if (empty($file_id)) {
      return response()->json([
        'success' => true
      ]);
    }

    $WFqur = Writing_file::where([['file_id', '=', $file_id], ['paragraph_id', '=', null]]);
    if (Writing_file::where('file_id', '=', $file_id)->count() == 1) {
      $toDelPaths=[];
      $sizeFilesArr = fileServe::searchByName(storage_path('app/content'), $file_id);
      foreach ($sizeFilesArr as $sizeFile) {
        $imgPath = 'content/'.$sizeFile.'.jpg';
        array_push($toDelPaths, $imgPath);
      }
      Storage::delete($toDelPaths);
    }

    if ($WFqur->exists()) {
      $WFqur->delete();
    }
    $WRq->update([
      'cover' => null
    ]);
  }

}

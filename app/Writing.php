<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


use leck\Http\Controllers\fileServe;

use leck\Section;
use leck\Split;
use leck\Writing_section;
use leck\Tag;
use leck\Like;
use leck\Writing_privilege;
use leck\Writing_file;
use leck\Writing_comment;
use leck\Writing_traffic;
use leck\SysMsg_writing;

use leck\Events\writingShowEvent;
use Event;

class Writing extends Model
{
  protected $fillable = [
      'id', 'type', 'title', 'description', 'lang', 'cover', 'str_id', 'public', 'published_at', 'created_at'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;




  public static function getCoverPath($value)
  {
    $storage_path = storage_path('app/content');
    $filesArr = fileServe::searchByName($storage_path, $value);

    if (!empty($value) && !empty($filesArr)) {
      return Storage::url('content/'.$value.'.jpg');
    }else {
      return Storage::url('def/cover.png');
    }
  }





  public static function show($writing_id)
  {
    if (Auth::check()) {
      $sysMsgs = SysMsg_writing::getMsgs($writing_id, Auth::user()->id);
    }else {
      $sysMsgs = null;
    }

    $data = array(
        'writing' => Writing::getByIdFull($writing_id),
        'sections' => Writing_section::showWriting($writing_id),
        'authors' => Writing_privilege::getWritingAuthors($writing_id),
        'comments' => Writing_comment::getByWritingId($writing_id, 15),
        'sys_notif' => $sysMsgs
    );

    Event::fire(new writingShowEvent($writing_id));
    return view('layouts.writings.inook')->with($data);
  }




  public static function createNew($type, $title = NULL)
  {
    $newId = Writing::uid(9, 'id');
    Writing::create([
        'id' => $newId,
        'type' => $type,
        'title' => $title,
        'str_id' => $newId
    ]);
    Writing_privilege::addAuthor($newId);


    return $newId;
  }


  public static function updatedInfo($writing_id, $type, $data)
  {
    switch ($type) {
      case 'title':
        if (!Writing::where([['id', '=', $writing_id], ['title', '=', $data]])->exists()) {
          Writing::where('id', '=', $writing_id)->update(['title' => $data]);
        }
        break;
      case 'desc':
        if (!Writing::where([['id', '=', $writing_id], ['description', '=', $data]])->exists()) {
          Writing::where('id', '=', $writing_id)->update(['description' => $data]);
        }
        break;
      case 'cover':
        $currCover = Writing::where('id', '=', $writing_id)->select('cover')->get()[0]->cover;

        $file = (object)[];
        $file->file_id = $data['file_id'];
        $file->position_after = $data['pos'];
        $file->atribute = $data['attr'];
        $file->delf = filter_var($data['delf'], FILTER_VALIDATE_BOOLEAN);
        $file->paragraph_id = null;

        if ($file->delf && $currCover != $file->file_id) {
          Writing_file::deleteCover($writing_id);
        }

        if (!empty($file->file_id) || (empty($file->position_after) && empty($file->atribute) && empty($file->file_id))) {
          Writing::where('id', '=', $writing_id)->update(['cover' => $file->file_id]);
        }

        if ($file->delf) {
          Writing_file::CreateUpdate($file, $writing_id);
        }
        break;
      case 'url':
        $newUrl = Writing::generateStrId($data, 'str_id');//str, /col name, banned
        break;
    }

  }


  public static function publish($writing_id)
  {
    $quryBase = Writing::where('id', '=', $writing_id);

    // if cover not nullable
    /*if (empty($quryBase->select('cover')->get()[0]->cover)) {
      return response()->json([
        'success' => false,
      ]);
    }*/

    $writingAllSects = Writing_section::getWritingSectionIds($writing_id);
    $wrFirstSect = Section::whereIn('id', $writingAllSects)->where('first', 1)->select('id')->get()[0]->id;
    if (!Split::where([['section_id', '=', $wrFirstSect], ['next_id', '!=', null]])->exists()) {
      return response()->json([
        'success' => false,
        'error_type' => 'no_split',
      ]);
    }


    $quryBase->update(['public' => 1, 'published_at' => Carbon::now(config('app.timezone'))->toDateTimeString()]);
    return '/'.Auth::user()->str_id.'/'.$quryBase->select('str_id')->get()[0]->str_id;
  }


  public static function getByUser($user_id, $limit = NULL, $public = [1])
  {
    $UsrWrit_ids = Writing_privilege::getByAuthor($user_id);
    $wrObjArr = [];
    if ($UsrWrit_ids) {
      $writingsResp = Writing::whereIn('public', $public)->whereIn('id', $UsrWrit_ids)->where('published_at', '!=', Null)->select('id')->get()->pluck('id')->all();

      foreach ($writingsResp as $writing_id) {
        $writing = self::getWritingInfo($writing_id, ['type', 'title', 'cover', 'str_id', 'public', 'published_at'], ['authors', 'cover', 'published_at']);
        array_push($wrObjArr, $writing);
      }
      return $wrObjArr;
    }else {
      return NULL;
    }
  }

  public static function getUserDrafts($user_id, $limit = NULL)
  {
    $UsrWrit_ids = Writing_privilege::getByAuthor($user_id);
    if ($UsrWrit_ids) {
      $writingsResp = Writing::whereIn('id', $UsrWrit_ids)->where([['published_at', '=', Null], ['public', '=', 0]])->select('id')->get()->pluck('id')->all();
      $wrObjArr = [];
      foreach ($writingsResp as $writing_id) {
        $writing = self::getWritingInfo($writing_id, ['type', 'title', 'cover', 'str_id', 'created_at'], ['cover', 'sections_nr', 'splits_nr']);
        $writing->created_at = Carbon::parse($writing->created_at)->format('M j, Y \a\t h:i A');
        array_push($wrObjArr, $writing);
      }

      return array_reverse($wrObjArr);
    }else {
      return NULL;
    }
  }


  public static function getUserPrivate($user_id, $limit = NULL)
  {
    $UsrWrit_ids = Writing_privilege::getByAuthor($user_id);
    if ($UsrWrit_ids) {
      $writingsResp = Writing::whereIn('id', $UsrWrit_ids)->where([['published_at', '!=', Null], ['public', '=', 0]])->select('id')->get()->pluck('id')->all();

      $wrObjArr = [];
      foreach ($writingsResp as $writing_id) {
        $writing = self::getWritingInfo($writing_id, ['type', 'title', 'cover', 'str_id', 'created_at'], ['cover', 'likes', 'sections_nr', 'splits_nr']);
        $writing->created_at = Carbon::parse($writing->created_at)->format('M j, Y \a\t h:i A');
        array_push($wrObjArr, $writing);
      }
      return array_reverse($wrObjArr);
    }else {
      return NULL;
    }
  }


  public static function getByIdFull($writing_id)
  {
    $wrInfo = Writing::getWritingInfo($writing_id, ['title', 'description', 'lang', 'cover', 'str_id', 'public', 'published_at', 'created_at'], ['authors', 'cover', 'likes', 'usr_likes', 'published_at', 'tags']);

    if (count($wrInfo)) {
      return $wrInfo;
    }else {
      return 0;
    }
  }


  public static function getWritingInfo($writing_id, $info = ['title', 'lang', 'str_id'], $more_info = [])
  {
    $writingQury = self::where('id', '=', $writing_id)->select($info)->get()[0];
    $writingQury->id = $writing_id;

    foreach ($more_info as $info_param) {
      switch ($info_param) {
        case 'authors':
          $writingQury->authors = Writing_privilege::getWritingAuthors($writing_id);
          if (empty($writingQury->authors)) {
            return;
          }
        break;
        case 'cover':
        if (!empty($writingQury->cover)) {
          $tempCoverId = $writingQury->cover;
          $WFqury = Writing_file::where([['file_id', '=', $tempCoverId], ['paragraph_id', '=', null]]);
          if ($WFqury->exists()) {
            $WFresp = $WFqury->select('atribute', 'position_after')->get()[0];
            $writingQury->cover = (object) [];
            $writingQury->cover->id = $tempCoverId;
            $writingQury->cover->path = self::getCoverPath($tempCoverId);
            $writingQury->cover->attr = $WFresp->atribute;
            $writingQury->cover->position = $WFresp->position_after;
          }else {
            Writing_file::deleteCover($writing_id, $writingQury->cover);
            unset($writingQury->cover);
          }
        }
        break;
        case 'published_at':
          if (!empty($writingQury->published_at)) {
            // str_replace(',', '', Carbon::parse($writingQury->published_at)->toFormattedDateString());
            $writingQury->published_at_parsed = Carbon::parse($writingQury->published_at)->format('M j');
          }else {
            // $writingQury->published_at_parsed = str_replace(',', '', Carbon::now()->toFormattedDateString());
            $writingQury->published_at_parsed = Carbon::parse(Carbon::now())->format('M j');
          }
        break;
        case 'visited':
          if (Auth::check()) {
            $writingQury->visited = Writing_traffic::where([['user_id', '=', Auth::user()->id], ['writing_id', '=', $writing_id]])->exists();
          }else {
            $writingQury->visited = false;
          }
        break;

        case 'tags':
          $writingQury->tags = Tag::getWritingTags($writing_id);
        break;

        case 'label':
          $writingQury->label = Tag::getWritingLabel($writing_id);
        break;

        case 'likes':
          $writingQury->likes = Like::getWritingLikes($writing_id);
        break;
        case 'usr_likes':
          $writingQury->usr_likes = Like::getUser_WrtLikes($writing_id, Auth::id());
        break;
        case 'sections_nr':
          $writingQury->sections_nr = Writing_section::where('writing_id', '=', $writing_id)->count();
        break;
        case 'splits_nr':
          $wrSecion_ids = Writing_section::where('writing_id', '=', $writing_id)->select('section_id')->get()->pluck('section_id')->all();
          $writingQury->splits_nr = Split::whereIn('section_id', $wrSecion_ids)->count();
        break;
      }
    }


    return $writingQury;
  }
}

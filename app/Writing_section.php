<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use leck\Section;
use leck\Split;
use leck\Paragraph;



class Writing_section extends Model
{
  protected $fillable = [
      'writing_id', 'section_id'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;


  public static function writing2section($writing_id, $section_id)
  {
    Writing_section::create([
        'writing_id' => $writing_id,
        'section_id' => $section_id,
    ]);
  }


  // only for editor
  public static function getByWritingId($writing_id, $sectionTL_id = NULL, $present_id = NULL)
  {
    $secIdsResp = Writing_section::select('section_id')->where('writing_id', '=', $writing_id)->get();

    if (!count($secIdsResp)) {
      Section::createNew($writing_id);
    }

    $IdsArr = [];
    foreach ($secIdsResp as $indx => $secId) {
      if (Section::where('id', '=', $secId->section_id)->exists()) {
        $secId->title = Section::where('id', '=', $secId->section_id)->select('title')->get()[0]->title;
        $secId->splits = Split::where('section_id', '=', $secId->section_id)->select('section_id', 'next_id')->get();
        array_push($IdsArr, $secId->section_id);
      }else {
        Writing_section::where([['section_id', '=', $secId->section_id], ['writing_id', '=', $writing_id]])->delete();
        unset($secIdsResp[$indx]);
      }
    }


    $sectsToLoad = [];
    if ($sectionTL_id != NULL) {
      array_push($sectsToLoad, $sectionTL_id);
    }else {
      array_push($sectsToLoad, Writing_section::getLatestEdited($writing_id));
    }


    $allWritingSections = Section::whereIn('id', $IdsArr)->select('id', 'title', 'first')->orderBy('created_at', 'desc')->get(); // prevent loading over and over same data
    foreach ($sectsToLoad as &$singleSection){
      $singleSection = Section::getFullSectionById($singleSection, ['title', 'prev_paths', 'split_priv', 'editor_cont'])[0];
      $singleSection->posible_nexts = Writing_section::getNextPosibleSectionIds($writing_id, $singleSection->id, [$singleSection->id], $allWritingSections, $secIdsResp, $IdsArr);
    }

    return $sectsToLoad;
  }




  public static function showWriting($writing_id)
  {
    $secObj = (object) [];
    if (Auth::check()) {
      $lstSplitId = Inook_progress::getLastOfUser($writing_id, Auth::user()->id);
    }else {
      $lstSplitId = false;
    }

    if (!$lstSplitId) {
      $sectsIds = self::where('writing_id', '=', $writing_id)->select('section_id')->get()->pluck('section_id')->all();
      if (empty($sectsIds)) {
        return Null;
      }
      $secId = Section::whereIn('id', $sectsIds)->where('first', '=', 1)->select('id')->get()[0]->id;
    }else {
      $splitResp = Split::where('id', '=', $lstSplitId)->select('section_id', 'next_id')->get()[0];
      if ($splitResp->next_id == NULL) {
        $secId = $splitResp->section_id;
      }else {
        $secId = $splitResp->next_id;
      }
    }

    $secObj = Section::getFullSectionById($secId, ['title', 'active_split']);

    return $secObj;
  }


  public static function getWritingSectionIds($writing_id, $limit = null)
  {
    if (Writing_section::where('writing_id', '=', $writing_id)->exists()) {
      $wsecIds = Writing_section::where('writing_id', '=', $writing_id)->select('section_id')->limit($limit)->get();
      $arrayOfIds = [];
      foreach ($wsecIds as $sect) {
        array_push($arrayOfIds, $sect->section_id);
      }
      return $arrayOfIds;
    }else {
      return Null;
    }
  }



  public static function treeView($writing_id)
  {
    $wsecIds = Writing_section::getWritingSectionIds($writing_id);

    if ($wsecIds == NULL) {
      return response()->json([
        'success' => true,
        'data' => false
      ]);
    }


    $sectionsResp = Section::whereIn('id', $wsecIds)->select('id', 'title', 'first')->get();
    if (count($sectionsResp)) {
      foreach ($sectionsResp as $sRespSngl) {
        $Next_idsArr = [];

        if (Split::where('section_id', '=', $sRespSngl->id)->exists()) {
          $secNext_idArr = Split::where('section_id', '=', $sRespSngl->id)->select('next_id')->get();
          foreach ($secNext_idArr as $secNext_id) {
            if (!empty($secNext_id->next_id)) {
              array_push($Next_idsArr, $secNext_id->next_id);
            }
          }
        }

        $sRespSngl->isFirst = $sRespSngl->first;
        $sRespSngl->next_id = $Next_idsArr;

        $data = array(
            'id' => $sRespSngl->id,
            'title' => $sRespSngl->title,
            'next_ids' => $Next_idsArr,
            'isFirst' => $sRespSngl->isFirst
        );
        $sRespSngl->html = view('partials.elem._tree-section', $data)->render();
      }
    }else {
      return response()->json([
        'success' => true,
        'data' => false
      ]);
    }

    return response()->json([
      'success' => true,
      'data' => $sectionsResp
    ]);
  }




  public static function getSectionsInfo($writing_id, $limit = null, $info = ['id', 'title'])
  {
    $wsecIds = Writing_section::getWritingSectionIds($writing_id, $limit);

    if ($wsecIds == NULL) {
      return response()->json([
        'success' => true,
        'data' => false
      ]);
    }


    $sectionsResp = Section::whereIn('id', $wsecIds)->select($info)->get();

    return response()->json([
      'success' => true,
      'data' => $sectionsResp
    ]);
  }



  public static function getNextPosibleSectionIds($writing_id, $section_id, $unwanted = [], $allWritingSections = null, $secIdsResp = null, $IdsArr = null)
  {
    if (!count($unwanted)) {
      $unwanted = [$section_id];
    }

    if (empty($secIdsResp) || empty($IdsArr)) {
      $secIdsResp = Writing_section::select('section_id')->where('writing_id', '=', $writing_id)->get();
      $IdsArr = [];
      foreach ($secIdsResp as $indx => $secId) {
        if (Section::where('id', '=', $secId->section_id)->exists()) {
          $secId->title = Section::where('id', '=', $secId->section_id)->select('title')->get()[0]->title;
          $secId->splits = Split::where('section_id', '=', $secId->section_id)->select('section_id', 'next_id')->get();
          array_push($IdsArr, $secId->section_id);
        }else {
          Writing_section::where([['section_id', '=', $secId->section_id], ['writing_id', '=', $writing_id]])->delete();
          unset($secIdsResp[$indx]);
        }
      }
    }

    if (empty($allWritingSections)) {
      $allWritingSections = Section::whereIn('id', $IdsArr)->select('id', 'title', 'first')->orderBy('created_at', 'desc')->get();
    }

    foreach ($secIdsResp as $innersection) { // get posible nexts above in the tree
      foreach ($innersection->splits as $split) {
        if (in_array($split->next_id, $unwanted) && !in_array($split->section_id, $unwanted)) {
          array_push($unwanted, $split->section_id);
        }
      }
    }

    $possibilitiesArr = [];
    foreach (array_diff($IdsArr, $unwanted) as $posId) { //create array with ids and titles of posible sections
      foreach ($allWritingSections as $sectionObj) {
        if ($posId == $sectionObj->id && $sectionObj->first == 0) {
          array_push($possibilitiesArr, $sectionObj);
        }
      }
    }

    return $possibilitiesArr;
  }

  public static function getNextFromSplit($section_id, $ignore = [])
  {
    if (!is_array($ignore)) {
      $ignores = array($ignore);
    }else {
      $ignores = $ignore;
    }

    $secSplits = Split::getBySectionId($section_id);
    if (empty($secSplits)) {
      return NULL;
    }

    foreach ($secSplits as $split) {
      if (!empty($split->next_id) && !in_array($split->id, $ignores)) {
        return Section::where('id', '=', $split->next_id)->select('id')->get()[0]->id;
        break;
      }
    }
  }

  public static function getPrevFromSplit($section_id)
  {
    $qury = Split::where('next_id', '=', $section_id);
    if ($qury->exists()) {
      $prevSecsId = $qury->select('section_id')->orderBy('position')->get();
      $prevIdsArr = [];
      foreach ($prevSecsId as $section) {
        array_push($prevIdsArr, $section->section_id);
      }
      return Section::whereIn('id', $prevIdsArr)->select('id', 'title')->get();
    }else {
      return null;
    }
  }


  public static function getLatestEdited($writing_id)
  {
    $WRsects = Writing_section::getWritingSectionIds($writing_id);
    if (!Paragraph::whereIn('section_id', $WRsects)->exists()) {
      $ltstEditedId = Section::whereIn('id', $WRsects)->select('id')->latest()->limit(1)->get()[0]->id;
    }else {
      $ltstEditedId = Paragraph::whereIn('section_id', $WRsects)->select('section_id')->orderBy('updated_at', 'desc')->limit(1)->get()[0]->section_id;
    }
    return Section::where('id', $ltstEditedId)->select('id')->get()[0]->id;
  }


  public static function getWritingFiles($writing_id, $types = ['img'])
  {
    $parIdsArr = Writing_section::getWritingParagraphIds($writing_id, $parType = $types);
    if ($parIdsArr == Null || !Writing_file::whereIn('paragraph_id', $parIdsArr)->exists()) {
      return Null;
    }else {
      $filesIds = Writing_file::whereIn('paragraph_id', $parIdsArr)->select('file_id')->get();

      $fileUrls = [];
      foreach ($filesIds as $filesId) {
        $obj = (object) [];
        $obj->file_id = $filesId->file_id;
        $obj->path = Storage::url($filesId->file_id);
        array_push($fileUrls, $obj);
      }

      return $fileUrls;
    }
  }


  public static function getWritingParagraphIds($writing_id, $parType = [])
  {
    $writingSecIds = Writing_section::getWritingSectionIds($writing_id);
    if (!count($parType)) {
      $quryPar = Paragraph::whereIn('section_id', $writingSecIds);
    }else {
      $quryPar = Paragraph::whereIn('section_id', $writingSecIds)->whereIn('type', $parType);
    }

    if ($writingSecIds == Null || !$quryPar->exists()) {
      return Null;
    }

    $wparIds = $quryPar->select('id')->get();
    $arrayOfIds = [];
    foreach ($wparIds as $wparId) {
      array_push($arrayOfIds, $wparId->id);
    }
    return $arrayOfIds;
  }
}

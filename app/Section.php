<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use leck\Page;

use leck\Writing_section;
use leck\Writing_file;
use leck\Inook_progress;
use leck\Split;
use leck\Section;
use leck\Paragraph;


class Section extends Model
{
  protected $fillable = [
      'id', 'title', 'first', 'created_at'
  ];

  public $incrementing = false;
  protected $primaryKey = null;



  public static function createNew($writing_id, $title = NULL)
  {
    $newId = Section::uid(9, 'id');

    if ($title == NULL || $title == 'Section') {
      $numOfSections = Writing_section::where('writing_id', '=', $writing_id)->count()+1;
      $title = 'Section '. Section::toRoman($numOfSections);
    }

    $isf = 0;
    if(!Writing_section::where('writing_id', '=', $writing_id)->exists()){
      $isf = 1;
    }

    Section::create([
        'id' => $newId,
        'title' => $title,
        'first' => $isf
    ]);

    Writing_section::writing2section($writing_id, $newId);

    return $newId;
  }


  public static function deleteSect($writing_id, $section_id)
  {
    $wrSectIds = Writing_section::getWritingSectionIds($writing_id);
    $isFrst = Section::where([['id', '=', $writing_id], ['first', '=', 1]])->exists();
    if (!in_array($section_id, $wrSectIds) || $isFrst) {
      return response()->json([
        'success' => false
      ]);
    }

    $imgParIds = Paragraph::where([['section_id', '=', $section_id], ['type', '=', 'img']])->select('id')->get()->pluck('id');
    foreach ($imgParIds as $ParId) {
      Writing_file::removeByParId($ParId);
    }
    Paragraph::where('section_id', '=', $section_id)->delete();
    Writing_section::where('section_id', '=', $section_id)->delete();



    $splitsOnSectIds = Split::where('section_id', '=', $section_id)->select('id')->get()->pluck('id')->toArray();
    if (count($splitsOnSectIds)) {
      //delete splits on section
      Split::whereIn('section_id', $splitsOnSectIds)->delete();
    }else {
      $splitsOnSectIds = [];
    }

    //delete splits with path to section
    $splits2Sect = Split::where('next_id', '=', $section_id)->select('id')->get()->pluck('id')->toArray();
    if (count($splits2Sect)) {
      Split::where('next_id', '=', $section_id)->update(['next_id' => null]);

    }else {
      $splits2Sect = [];
    }


    $afectedSplits = array_merge($splitsOnSectIds, $splits2Sect);
    foreach ($afectedSplits as $spId) {
      Inook_progress::removePathHistry($writing_id, $spId);
    }

    Section::where('id', '=', $writing_id)->delete();

    return response()->json([
      'success' => true
    ]);
  }


  public static function updatedTitle($writing_id, $title, $section_id)
  {
    $rules = array(
      'section_id' => 'required',
      'title' => 'required|string|nullable|max:50'
    );

    $vaildator = Validator::make([
    'section_id' => $section_id, 'title' => $title
    ], $rules);

    if ($vaildator->fails()) {
      return response()->json([
        'success' => false
      ]);
    }

    $wrSectIds = Writing_section::getWritingSectionIds($writing_id);
    if (!in_array($section_id, $wrSectIds)) {
      return response()->json([
        'success' => false
      ]);
    }

    if (in_array($title, ['', ' ', null])) {
      $title = 'Section nr '.str($section_id);
    }

    $restOfIds = array_diff( $wrSectIds, [$section_id] ); // sect ids without one to change
    if (Section::where('title', '=', $title)->whereIn('id', $restOfIds)->exists()) {
      return response()->json([
        'success' => false,
        'msg' => 'One of your sections allready has this name'
      ]);
    }

    Section::where('id', '=', $section_id)->update(['title' => $title]);

    return response()->json([
      'success' => 1
    ]);
  }


  public static function getById($ids, $editor_cont = false)
  {
    if (!is_array($ids)) {
      $sec_ids = array($ids);
    }else {
      $sec_ids = $ids;
    }

    $SecContArr = [];
    foreach ($sec_ids as $section_id) {
      $sectionObj = (object) [];
      $sectionObj->id = $section_id;
      $paragraphElmtsArr = Paragraph::where('section_id', '=', $section_id)->select('id', 'content', 'atribute', 'type', 'position_after')->get();
      $DOMarray = [];

      $ElmtsArrSorted = [];
      $wanted = 'first0';
      $ifEmpty = true;

      for ($p=0; $p < count($paragraphElmtsArr);) {
        $currVal = $paragraphElmtsArr[$p];

        if ($currVal->position_after == $wanted) {
          // unset($paragraphElmtsArr[$p]);
          array_push($ElmtsArrSorted, $currVal);

          $wanted = $currVal->id;
          $p = 0;
        }else {
          $p++;
        }
      }


      if (!count($ElmtsArrSorted)) {
        $ifEmpty = true;
        $sectionObj->innerHtml = NULL;
      }else {
        foreach ($ElmtsArrSorted as $elementObj) {
          if ($elementObj->type == 'p') {
            if (trim($elementObj->content) != '' && $elementObj->content != '<br>') {
              $ifEmpty = false;
            }
          }elseif ($elementObj->type == 'img') {
            $elementObj->files = Writing_file::getByParId($elementObj->id)[0];
          }else {
            $ifEmpty = false;
          }

          $DOMelement = Paragraph::CreateDOM($elementObj, $editor_cont);
          array_push($DOMarray, $DOMelement);
        }

        $sectionObj->innerHtml = $DOMarray;
      }

      $sectionObj->contentEmpty = $ifEmpty;
      array_push($SecContArr, $sectionObj);
    }
    return $SecContArr;
  }




  public static function getFullSectionById($ids, $moreInfo = [])
  {
    if (!is_array($ids)) {
      $sec_ids = array($ids);
    }else {
      $sec_ids = $ids;
    }

    foreach ($sec_ids as &$sect) {
      $tempVarId = $sect;
      $sect = (object) [];
      $sect->id = $tempVarId;
      $sect->isFirst = Section::where('id', '=', $tempVarId)->select('first')->get()[0]->first;
      if (in_array('editor_cont', $moreInfo)) {
        $editor_cont = true;
      }else {
        $editor_cont = false;
      }

      $sect->content = Section::getById($tempVarId, $editor_cont)[0];
      if (in_array('split_priv', $moreInfo)) {
        $sect->splits = Split::getBySectionId($tempVarId);
      }else {
        $sect->splits = Split::getBySectionId($tempVarId, true);
      }

      if (count($moreInfo)) {
        foreach ($moreInfo as $type) {
          switch ($type) {
            case 'title':
              $sect->title = Section::where('id', '=', $tempVarId)->select('title')->get()[0]->title;
            break;

            case 'prev_paths':
              $sect->prev_paths = Writing_section::getPrevFromSplit($tempVarId);
            break;

            case 'active_split':
              if ($sect->splits != NULL && Auth::check()) {
                $inpQury = Inook_progress::where('user_id', '=', Auth::user()->id)->whereIn('split_id', $sect->splits);
                if (!$inpQury->exists()) {
                  $sect->active_split = NULL;
                }else {
                  $sect->active_split = $inpQury->select('split_id')->get()[0]->split_id;
                }
              }else {
                $sect->active_split = NULL;
              }
            break;
          }
        }
      }

    }
    return $sec_ids;
  }

}

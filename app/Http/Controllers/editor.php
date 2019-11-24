<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use leck\Jobs\ProcessParagraph;

use leck\Http\Controllers\fileUpload;
use Carbon\Carbon;

use leck\Writing;
use leck\Section;
use leck\Split;
use leck\Paragraph;
use leck\Writing_section;
use leck\Writing_tag;
use leck\Writing_privilege;
use leck\Writing_file;
use leck\Inook_progress;

class editor extends Controller
{

    public static function New_inook()
    {
      $createdId = Writing::createNew('inook');
      Section::createNew($createdId);
      return redirect('editor/'.$createdId);
    }




    public static function showEditor($writing_id)
    {
      if (!Writing::where('id', '=', $writing_id)->exists()) {
        abort(404);
      }else if(!Writing_privilege::isQualified(Auth::user()->id, $writing_id)){
        abort(403);
      }



      $sections = Writing_section::getByWritingId($writing_id);

      $data = array(
          'writing' => Writing::getByIdFull($writing_id),
          'sections' => $sections,
      );

      return view('system.editor.editor_big')->with($data);
    }







    public static function saveChanges(Request $request, $writing_id)
    {
      $request->writing_id = $writing_id;
      $Psuccess = true;
      $Pmsg = [];
      if (isset($request->title)) {
        if (is_string($request->title) || $request->title == "") {
          if (!empty($request->publish)) {
            $rules = array(
              'title' => 'string|max:120',
            );
          }else {
            $rules = array(
              'title' => 'nullable|string|max:120',
            );
          }

          $messages = [
            'max' => 'Title is too long',
          ];

          $tstring = trim(preg_replace('/\s+/', ' ', $request->title));

          $vaildator = Validator::make([
            'title' => $tstring
          ], $rules, $messages);



          if ($vaildator->fails()) {
            if (!empty($request->publish)) {
              $Psuccess = false;
              array_push($Pmsg, $vaildator->errors()->all());
            }else {
              return response()->json([
                'success' => false,
                'msg' => $vaildator->errors()->all()
              ]);
            }
          }else {
            Writing::updatedInfo($writing_id, 'title', $tstring);
          }
        }
      }



      if (isset($request->desc) && (is_string($request->desc) || $request->desc == "")) {
        if (!empty($request->publish)) {
          $rules = array(
            'desc' => 'string|max:300',
          );
        }else {
          $rules = array(
            'desc' => 'nullable|string|max:300',
          );
        }

        $messages = [
          'max' => 'Description is too long',
        ];

        $destring = trim(preg_replace('/\s+/', ' ', $request->desc));

        $vaildator = Validator::make([
          'desc' => $destring
        ], $rules, $messages);



        if ($vaildator->fails()) {
          if (!empty($request->publish)) {
            $Psuccess = false;
            array_push($Pmsg, $vaildator->errors()->all());
          }else {
            return response()->json([
              'success' => false,
              'msg' => $vaildator->errors()->all()
            ]);
          }
        }else {
          Writing::updatedInfo($writing_id, 'desc', $destring);
        }
      }


      if (is_array($request->tags)) {
        $reqFromTagMdl = Writing_tag::asignAndRemove($writing_id, $request->tags);

        if (!$reqFromTagMdl) {
          $Psuccess = false;
        }
      }


      if ($Psuccess) {
        if (!empty($request->publish)) {
          $respFrmModelWrp = Writing::publish($writing_id);
          if (!empty($respFrmModelWrp->original) && !$respFrmModelWrp->original['success']) {
            return $respFrmModelWrp;
          }else {
            return response()->json([
              'success' => true,
              'url' => $respFrmModelWrp
            ]);
          }
        }
      }else {
        return response()->json([
          'success' => false,
          'msg' => $Pmsg
        ]);
      }


      if (!empty($request->writing_cover)) {
        Writing::updatedInfo($writing_id, 'cover', $request->writing_cover);
      }


      if (!empty($request->new_section)) {
        $nscetitle = $request->new_section;
        if (is_string($nscetitle)) {
          $nwsecID = Section::CreateNew($writing_id, $nscetitle);
          $respFrmModel = Writing_section::getByWritingId($writing_id, $nwsecID);

          return response()->json([
            'success' => true,
            'section_id' => $nwsecID,
            'sectionTemplate' => view('layouts.writings._inook-editor-section', ['sections' => $respFrmModel])->render()
          ]);
        }else {
          return response()->json([
            'success' => false
          ]);
        }
      }



      if (!empty($request->section_title)) {
        $respFrmModelSect = Section::updatedTitle($writing_id, $request->section_title, $request->section_id);
        if (!$respFrmModelSect->original['success']) {
          return $respFrmModelSect;
        }
      }


      if (!empty($request->delete_sect)) {
        $respFrmModelSect = Section::deleteSect($writing_id, $request->delete_sect);
        if (!$respFrmModelSect->original['success']) {
          return $respFrmModelSect;
        }
      }



      /*if (!empty($request->editor_removed)) {

        if (!is_array($request->editor_removed)) {
          return response()->json([
            'success' => false
          ]);
        }
        $respFrmModel = ProcessParagraph::dispatch($request->editor_removed, $writing_id, 'remove')
            ->onQueue('procParagraphs');


      }*/



      if ((is_array($request->editor_content) && count($request->editor_content)) ||
          (is_array($request->editor_removed) && count($request->editor_removed))) {

        $respFrmModel = ProcessParagraph::dispatch($request->editor_content, $request->editor_removed, $writing_id)
                        ->onQueue('procParagraphs');



        if (!empty($respFrmModel->original) && !$respFrmModel->original['success']) {
          return $respFrmModel;
        }
      }


      if (!empty($request->remove_split) && is_string($request->remove_split)) {
        $respFrmModelremSplit = Split::removeSplit($writing_id, $request->remove_split);
        if (!$respFrmModelremSplit->original['success']) {
          return $respFrmModelremSplit;
        }
      }



      if (!empty($request->newsplit_item) && is_array($request->newsplit_item)) {
        $split = (object) [];
        $split->position = $request->newsplit_item['position'];
        $split->section_id = $request->newsplit_item['section_id'];


        $respFrmModelnewSplit = Split::CreateNew($split);

        if (!$respFrmModelnewSplit->original['success']) {
          return $respFrmModelnewSplit;
        }else {
          return response()->json([
            'success' => true,
            'new_id' => $respFrmModelnewSplit->original['new_id']
          ]);
        }
      }

      if (!empty($request->split_item) && is_array($request->split_item)) {
        foreach ($request->split_item as $split_item) {
          $split = (object) [];

          $split->id = $split_item['id'];
          $split->title = $split_item['title'];
          if (strlen($split->title) > 120) {
            $split->title = substr($split->title, 0, 119);
          }

          $split->position = $split_item['position'];
          $split->next_id = $split_item['next_id'];




          $respFrmModelSplit = Split::updateInfo($writing_id, $split);

          if (!$respFrmModelSplit->original['success']) {
            return $respFrmModelSplit;
          }
        }
      }


      return response()->json([
        'success' => true
      ]);
    }




    public static function saveUploads(Request $request, $writing_id)
    {
      if ($request->hasFile('inimage')) {
        return fileUpload::writingContentImage($request);
      }
    }




    public static function getInfo(Request $request, $writing_id)
    {
      if (!empty($request->tree_view) && is_string($request->tree_view)) {
        $respFrmModel = Writing_section::treeView($writing_id);
        return $respFrmModel;
      }

      if (!empty($request->sect_min) && is_string($request->sect_min)) {
        return Writing_section::getSectionsInfo($writing_id, $request->sect_min);
      }

      if (!empty($request->check_split) && is_string($request->check_split)) {
        return response()->json([
          'data' => Inook_progress::countUsersOnPath($writing_id, $request->check_split)
        ]);
      }


      if (isset($request->mmore_cover)) {
        $weCoverId = Writing::where('id', '=', $writing_id)->select('cover')->get()[0]->cover;
        if (!empty($weCoverId)) {
          $respFrmModel = Writing::getCoverPath($weCoverId);
        }else {
          $respFrmModel = Null;
        }

        if ($respFrmModel == Null) {
          return response()->json([
            'success' => true,
            'data' => Null
          ]);
        }else {
          return response()->json([
            'success' => true,
            'data' => $respFrmModel
          ]);
        }
      }



      if (!empty($request->get_section) && !empty($request->present)) {
        if (is_array($request->present) && is_string($request->get_section)) {
          $respFrmModel = Writing_section::getByWritingId($writing_id, $request->get_section, $request->present);

          return response()->json([
            'success' => true,
            'data' => view('layouts.writings._inook-editor-section', ['sections' => $respFrmModel])->render()
          ]);
        }else {
          return response()->json([
            'success' => false
          ]);
        }
      }


      if (!empty($request->get_drpdwn) && is_string($request->get_drpdwn)) {
        $respFrmModel = Writing_section::getByWritingId($writing_id, $request->get_section, $request->present);
        $nxtPosData = Writing_section::getNextPosibleSectionIds($writing_id, $request->get_drpdwn, $unwanted = []);

        $htmlArr = [];
        foreach ($nxtPosData as $next) {
          if ($nxtPosData != null) {
            array_push($htmlArr, view('partials.elem._w-split-drpdwn-item', ['next' => $next])->render());
          }else {
            $htmlArr = null;
          }
        }

        return response()->json([
          'success' => true,
          'data' => $htmlArr
        ]);
      }



      return response()->json([
        'success' => false
      ]);
    }


}

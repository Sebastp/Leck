<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use leck\Http\Controllers\fileServe;

use leck\Writing_section;
use leck\Writing_file;

use Illuminate\Support\Facades\File;
use Image;


class Paragraph extends Model
{
  protected $fillable = [
      'id', 'content', 'atribute', 'type', 'section_id', 'position_after', 'created_at', 'updated_at'
  ];

  public $incrementing = false;
  protected $primaryKey = null;





  public static function CreateUpdate($writing_id, $section_id, $paragraph)
  {
    $paragraph = Paragraph::sanitizeElement($paragraph);

    $prgrph_id = $paragraph->id;
    $prgrph_type = $paragraph->type;
    $prgrph_content = $paragraph->content;
    $prgrph_atr = $paragraph->atribute;
    $prgrph_position = $paragraph->position;
    $prgrph_excover = $paragraph->excover;



    $rules = array(
      'id' => 'required|string',
      'content' => 'string',
      'atribute' => 'nullable|string',
      'type' => [
        'required',
        Rule::in(['p', 'blockquote', 'h2', 'img']),
      ],
      'position_after' => 'nullable|string'
    );

    if ($prgrph_type == 'img') {
      $rules['content'] = 'nullable|string';
    }

    $qury = Paragraph::where([
      ['id', '=', $prgrph_id],
      ['section_id', '=', $section_id]
    ]);


    if (empty($prgrph_position)) {
      $quryFrst = Paragraph::where([
        ['section_id', '=', $section_id],
        ['position_after', '=', 'first0']
      ]);
      if ($quryFrst->exists()) {
        if ($quryFrst->select('id')->get()[0]->id != $prgrph_id) {
          $quryFrst->update(['position_after' => $prgrph_id]);
        }
        $prgrph_position = 'first0'; //first
      }else {
        $prgrph_position = 'first0'; //first
      }
    }else {
      $chckQury = Paragraph::where([
        ['id', '!=', $prgrph_id],
        ['section_id', '=', $section_id],
        ['position_after', '=', $prgrph_position]
      ]);
      if ($chckQury->exists()) {
        $chckQury->update(['position_after' => $prgrph_id]);
      }
    }


  if ($qury->exists()) {
    if (is_array($prgrph_content) && $prgrph_type == 'img') {
      foreach ($prgrph_content as $file) {
        $file = json_decode(json_encode($file)); //change to object

        $file->paragraph_id = $prgrph_id;
        Writing_file::CreateUpdate($file);
      }
    }else {
      $vaildator = Validator::make([
        'id' => $prgrph_id, 'section_id' => $section_id,
        'content' => $prgrph_content, 'atribute' => $prgrph_atr, 'type' => $prgrph_type,
        'position_after' => $prgrph_position
      ], $rules);

      if ($vaildator->fails()) {
        return response()->json([
          'success' => false
        ]);
      }

      $qury->update([
        'content' => $prgrph_content, 'atribute' => $prgrph_atr,
        'type' => $prgrph_type, 'position_after' => $prgrph_position
      ]);
    }
  }else {
    if (is_array($prgrph_content) && $prgrph_type == 'img') {
      foreach ($prgrph_content as $file) {
        $file = json_decode(json_encode($file)); //change to object

        $file->paragraph_id = $prgrph_id;
        Writing_file::CreateUpdate($file, null, $prgrph_excover);
      }
    }

    if (!is_array($prgrph_content) || $prgrph_excover) {
      if ($prgrph_excover && is_array($prgrph_content)) {
        $prgrph_content = '';
      }

      $vaildator = Validator::make([
      'id' => $prgrph_id, 'section_id' => $section_id,
      'content' => $prgrph_content, 'atribute' => $prgrph_atr, 'type' => $prgrph_type,
      'position_after' => $prgrph_position
      ], $rules);

      if ($vaildator->fails()) {
        return response()->json([
          'success' => false
        ]);
      }

      Paragraph::create([
        'id' => $prgrph_id,
        'content' => $prgrph_content,
        'atribute' => $prgrph_atr,
        'type' => $prgrph_type,
        'section_id' => $section_id,
        'position_after' => $prgrph_position
      ]);
    }
  }


  return response()->json([
    'success' => true
  ]);
}





  public static function remove($writing_id, $paragraph_id)
  {
    $rules = array(
      'id' => 'required|string'
    );

    $vaildator = Validator::make([
      'id' => $paragraph_id
    ], $rules);


    if ($vaildator->fails()) {
      return response()->json([
        'success' => false
      ]);
    }

    $section_ids = Writing_section::getWritingSectionIds($writing_id);

    $delQury = Paragraph::where('id', '=', $paragraph_id)->whereIn('section_id', $section_ids);


    if ($delQury->exists()) {
      $elemInfo = $delQury->select('position_after', 'type')->get()[0];
      Paragraph::where('position_after', '=', $paragraph_id)->update(['position_after' => $elemInfo->position_after]);
      if ($elemInfo->type == 'img') {
        Writing_file::removeByParId($paragraph_id);
      }
      $delQury->delete();
    }

    return response()->json([
      'success' => true
    ]);

  }






  public static function sanitizeElement($paragraph)
  {
    if (in_array($paragraph->type, ['p', 'h2', 'blockquote'])) {
      if (empty($paragraph->content)) {
        $paragraph->content = '<br>';
      }else {
        // $paragraph->content = strip_tags($paragraph->content, '<br><span>');
        $paragraph->content = strip_tags($paragraph->content, '<i><b><br><a>');
      }
    }


    switch ($paragraph->type) {
      case 'p':
        $paragraph->type = 'p';
      break;

      case 'h2':
        $paragraph->type = 'h2';
        $paragraph->content = strip_tags($paragraph->content, '<a>');
      break;

      case 'blockquote':
        $paragraph->type = 'blockquote';
      break;

      case 'img':
        $paragraph->type = 'img';
      break;

      default:
        $paragraph->type = 'p';
      break;
    }

    // $paragraph->content = preg_replace('/<!--(.*)-->/Uis', '', $paragraph->content); //reg exp remove html comments

    if ($paragraph->type != 'img') {
      $paragraph->content = strip_tags($paragraph->content, '<i><b><br><a>');
    }

    /*if (!empty($paragraph->content)) {
      $domElement = new \DOMDocument(); //parent node
      $domElement->loadHTML($paragraph->content);
      // return $domElement->saveHTML();
    }*/

    return $paragraph;
  }




  public static function CreateDOM($elementObj, $editor_style = false)
  {
    $elmnt_id = $elementObj->id;
    $elmnt_type = $elementObj->type;
    $elmnt_content = $elementObj->content;
    $elmnt_atr = $elementObj->atribute;

    $domElement = new \DOMDocument(); //parent node
    if ($elmnt_type == 'img') {
      $elmnt_f = $elementObj->files;

      if (!empty($elmnt_f)) {
        if ($editor_style) {
          $imgSizeByAtr = 1;
        }else {
          $imgSizeByAtr = $elmnt_f->atribute;
        }
        $fileSizeName = fileServe::getByContentAtr($elmnt_f->file_id, $imgSizeByAtr);
        $prepFileUrl = 'content/'.$fileSizeName.'.jpg';
      }else {
        $prepFileUrl = 'content/.jpg';
      }


      if (empty($elmnt_f) || !Storage::exists($prepFileUrl)) { // image not found
        $domElemSTR =
        '<figure contenteditable="false" id="'.$elmnt_id.'">'.
          "<p class='w-innerf f3_sub_msg brdr' style='padding: 25px 10px;'>Sorry, we couldn't load this image</p>".
        '</figure>'
        ;

        if (!empty($elmnt_f) && !Storage::exists($prepFileUrl)) {
          Writing_file::removeByParId($elmnt_id);
        }
      }else {
        $elempath = Storage::url($prepFileUrl);

        $file = File::get(storage_path('app/'.$prepFileUrl));
        $imageRI = Image::make($file);
        $aRatio = $imageRI->height()/$imageRI->width()*100;

        /*if ($imageRI->width() < 750) {
          $progContStyle = 'style="width: '.$imageRI->width().'px;"';
        }else {
          $progContStyle = '';
        }*/
        $progContStyle = '';

        //loading
        $elemLoadPath = Storage::url('content/'.$elmnt_f->file_id.'_20.jpg');

        $domElemSTR =
        '<figure contenteditable="false" id="'.$elmnt_id.'">'.
          '<div class="w-innerf-cont" '.$progContStyle.' contenteditable="false" data-ratio="'.$aRatio.'" data-size="'.$elmnt_f->atribute.'">'.
            '<div class="prog-load" '.$progContStyle.'><'.$elmnt_type.' src="'.$elemLoadPath.'" class="prog-load-elem" contenteditable="false"></div>'.
            '<'.$elmnt_type.' data-src="'.$elempath.'" style="padding-bottom: '.$aRatio.'%;" data-width="'.$imageRI->width().'" data-height="'.$imageRI->height().'" class="w-innerf" data-file-id="'.$elmnt_f->file_id.'" contenteditable="false">'.
          '</div>'.
        '</figure>'
        ;
      }

    }else {
      $domElemSTR =
        '<'.$elmnt_type.' id="'.$elmnt_id.'">'
        .$elmnt_content.
        '</'.$elmnt_type.'>'
      ;
    }

    libxml_use_internal_errors(true); // doesn't suport html5 tags
    $domElement->loadHTML(mb_convert_encoding($domElemSTR, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();
     //br, a, b etc.

    // $node = $domElement->createElement($elmnt_type);
    // $node->setAttribute("id", $elmnt_id);

    // $domElement2 = new \DOMNode(); //child nodes
    // $domElement2->loadHTML($elmnt_content);

    // $childNode = $domElement->createDocumentFragment();
    // $childNode->appendXML($elmnt_content);
    // return var_dump($domElement2->childNodes());
    // $node->appendChild($domElement2->saveHTML());


    return $domElement->saveHTML();
  }


}

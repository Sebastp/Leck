<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



use ImageOptimizer;
use Image;
use leck\User;
use leck\Writing_file;

class fileUpload extends Controller
{
  public static function writingContentImage(Request $request)
  {
    $rules = array(
      'file' => 'required|image|mimes:jpeg,png,jpg,jpgs|max:11000'
    );

    $vaildator = Validator::make(['file' => $request->file('inimage')], $rules);


    if ($request->file('inimage')->isValid() && !$vaildator->fails()) {
      $imageR = $request->file('inimage');
      $file_id = Writing_file::uStringId(15, 'file_id');


      $file = (object) [];
      $file->file_id = $file_id;
      $file->position_after = $request->position;
      $file->atribute = $request->attr;
      $file->paragraph_id = $request->paragraph_id;

      $imageRI = Image::make($imageR);
      if ($file->atribute == 750 && $imageRI->width() < 750) {
        $file->atribute = null;
      }

      if ($file->paragraph_id == 'cover') {
        $valid = true;
      }else {
        $respFrmModelFile = Writing_file::CreateUpdate($file);
        $valid = $respFrmModelFile->original['success'];
      }

      if (!$valid) {
        return $respFrmModelFile;
      }else {
        $saveSizes = [20];
        $posibleSizes = [1005, 750];
        $savedByAttr = false;

        if ($file->paragraph_id == 'cover') {
          array_push($posibleSizes, 200, 450, 550);
        }


        /*if (in_array($file->atribute, ['750', '1005'])) {
          $imageRI->widen((int)$file->atribute);
        }else if($file->atribute == '1'){
          if ($imageRI->width() > 3000) {
            $imageRI->widen(3000);
          }
        }else if($imageRI->width() >= 750){
          $imageRI->widen(750);
        }*/

        foreach ($posibleSizes as $pSize) {
          if ($imageRI->width() >= $pSize) {
            array_push($saveSizes, $pSize);
            $savedByAttr = true;
          }
        }

        if ($imageRI->width() > 1020 || !$savedByAttr) {
          if ($imageRI->width() >= 3000) {
            array_push($saveSizes, 3000);
          }else {
            array_push($saveSizes, $imageRI->width());
          }
        }


        $bigestSize = 0;
        arsort($saveSizes);
        foreach ($saveSizes as $size) {
          $imageRI->widen($size);
          Storage::put('content/'.$file_id.'_'.$size.'.jpg',  $imageRI->encode('jpg')->stream());
          if ($size > $bigestSize) {
            $bigestSize = $size;
          }
        }


        $bigestUrl = Storage::url('content/'.$file_id.'_'.$bigestSize.'.jpg');
        return response()->json([
          'success' => true,
          'new_img_id' => $file_id,
          'new_path' => $bigestUrl
        ]);
      }
    }else {
      return response()->json([
        'success' => false
      ]);
    }
  }





  public static function profileAvatar(Request $request)
  {
    $rules = array(
      'file' => 'required|image|mimes:jpeg,png,jpg,jpgs|max:11000'
    );
    $vaildator = Validator::make(['file' => $request->file('new_avatar')], $rules);


    if ($request->file('new_avatar')->isValid() && !$vaildator->fails()) {
      $imageR = $request->file('new_avatar');
      $file_id = User::uStringId(15, 'avatar');

      $imageRI = Image::make($imageR);




      $saveSizes = [150, 50, 35];
      foreach ($saveSizes as $size) {
        $imageRI->widen($size);
        if ($imageRI->height() < $size) {
          $imageRI->heighten($size);
          $imageRI->crop($size, $size);
        }

        Storage::put('users/'.$file_id.'_'.$size.'.jpg',  $imageRI->encode('jpg')->stream());
      }
      $latestUrl = Storage::url('users/'.$file_id.'_150.jpg');

      User::avatarUpdate($file_id);

      return response()->json([
        'success' => true,
        'new_path' => $latestUrl
      ]);
    }else {
      return response()->json([
        'success' => false,
      ]);
    }
  }

}

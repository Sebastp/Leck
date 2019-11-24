<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class fileServe extends Controller
{
  public static function serve(Request $request, $folder, $filename)
  {
    $storage_path = storage_path('app/'.$folder);
    $path = $storage_path.'/'.$filename;
    $file_parts = explode('.', $filename);
    $file_id = explode('.', $filename)[0];
    $sizeWanted = $request->input('s');

    $filesArr = fileServe::searchByName($storage_path, $file_id);
    if (!count($filesArr) || count($file_parts)<2) {
        abort(404);
    }
    $file_extension = explode('.', $filename)[1];


    if (empty($sizeWanted) && count(explode($file_id, '_')) > 1) {
      $sizeWanted = explode('_', $file_id)[1];
    }

    $rules = array(
      'size' => 'required|integer'
    );
    $vaildator = Validator::make(['size' => $sizeWanted], $rules);
    if (!$vaildator->fails()) {
      $fileSizeName = self::getSizeFileId($file_id, $storage_path, $sizeWanted);
    }else {
      if ($folder != 'def') {
        $fileSizeName = self::getSizeFileId($file_id, $storage_path, 10000000);
      }else {
        $fileSizeName = $file_id;
      }
    }

    $path = $storage_path.'/'.$fileSizeName.'.'.$file_extension;


    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
  }

  // $file_id without ext
  public static function searchByName($path, $file_id)
  {
    $dir_list = File::glob($path.'/'.$file_id.'*');
    $fileSizes = [];

    foreach ($dir_list as $fPath) {
      $pathParts = explode('/', $fPath);
      $fileName = $pathParts[count($pathParts)-1];
      $fileId = explode('.', $fileName)[0]; // no exp
      array_push($fileSizes, $fileId);
    }
    return $fileSizes;
  }


  public static function getSizeFileId($file_id, $storage_path, $size)
  {
    $dir_list = self::searchByName($storage_path, $file_id);
    $wantedName = $file_id;
    $closest = null;

    foreach ($dir_list as $fName) {
      if (count(explode('_', $fName))>1) {
        $fileSize = explode('_', $fName)[1];
        if ($closest === null || abs($size - $closest) > abs($fileSize - $size)) {
          $closest = $fileSize;
          $wantedName = $fName;
        }
      }
    }

    return $wantedName;
  }


  public static function getByContentAtr($file_id, $attr)
  {
      $storage_path = storage_path('app/content');
      if (empty($attr) || $attr == 1) {
        $attr = 100000;
      }
      return self::getSizeFileId($file_id, $storage_path, (int)$attr);
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;

class FilesController extends Controller
{
    public function download(File $file)
    {
        if(!$file) abort(404);

        return response()->download(storage_path('app/' . $file->url));
    }

    public static function upload($files, $storeFolder = '')
    {
        $fileIds = [];
        foreach ($files as $file) {
            $storedFileId = self::store($file, $storeFolder);

            array_push($fileIds, $storedFileId);
        }

        return $fileIds;
    }

    private static function store($file, $storeFolder)
    {
        $fileOriginalName = $file->getClientOriginalName();

        $newFile = new File();
        $newFile->name = $fileOriginalName;
        $newFile->url = $file->storeAs($storeFolder, $fileOriginalName, 'local');

        $newFile->save();

        return $newFile->id;
    }
}

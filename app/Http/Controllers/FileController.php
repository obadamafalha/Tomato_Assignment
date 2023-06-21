<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    public function groupFiles()
    {
        $folderPath = storage_path('app/files'); //Please put your files here

        // Get all text files in the folder
        $fileList = File::files($folderPath);

        // Create sub-folders based on language names
        $languages = collect($fileList)->map(function ($file) {
            return explode('-', $file->getFilename())[0];
        })->unique();

        $languages->each(function ($language) use ($folderPath) {
            $languageFolder = $folderPath . '/' . $language;
            if (!File::exists($languageFolder)) {
                File::makeDirectory($languageFolder);
            }
        });

        // Move files to respective language folders
        foreach ($fileList as $file) {
            $language = explode('-', $file->getFilename())[0];
            $destinationFolder = $folderPath . '/' . $language;
            $newFilePath = $destinationFolder . '/' . $file->getFilename();
            rename($file->getPathname(), $newFilePath);
        }

        return 'Files grouped into sub-folders successfully.';
    }
}

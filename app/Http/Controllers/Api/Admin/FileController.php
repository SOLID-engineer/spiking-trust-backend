<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store (Request $request) {
        $files = $request->file('files');
        $mime_type = $request->get('mime_type');
        $max = $request->get('max');
        $user = $request->user();
        $path = $request->get('path');
        if (is_array($files)) {
            $rules = [
                'files.*' => 'required|file'
            ];
            $validate = \Validator::make($request->all(), $rules);
            if ($validate->fails()) return response()->json([], 400);
            $result = [];

            foreach ($files as $key => $fileItem) {
                $source = Storage::disk('public')->put($path, $fileItem);
                $file = new File();
                $file->real_name = $fileItem->getClientOriginalName();
                $file->extension = $fileItem->getClientOriginalExtension();
                $file->size = $fileItem->getSize();
                $file->mime_type = $fileItem->getMimeType();
                $file->source = url($path.$source);
                $file->name =  basename($source);
                $file->created_by = $user->id;
                $file->save();
                $result[] = $file;
            }

            return response()->json($result, 200);
        }
        $rules = [
            'files' => 'required|file'
        ];
        $validate = \Validator::make($request->all(), $rules);
        if ($validate->fails()) return response()->json([], 400);

        $file = new File();
        $source = Storage::disk('public')->put($path, $files);
        $file->real_name = $files->getClientOriginalName();
        $file->extension = $files->getClientOriginalExtension();
        $file->size = $files->getSize();
        $file->mime_type = $files->getMimeType();
        $file->source = url($path.$source);
        $file->name =  basename($source);
        $file->created_by = $user->id;
        $file->save();
        return response()->json($file, 200);
    }
}

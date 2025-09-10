<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function listFiles(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $files = UploadedFile::latest()->paginate($perPage);

        $filesData = $files->getCollection()->map(function ($file) {
            return [
                'id' => $file->id,
                'original_name' => $file->original_name,
                'file_size' => $file->file_size_human,
                'mime_type' => $file->mime_type,
                'url' => $file->url,
                'is_image' => $file->isImage(),
                'uploaded_at' => $file->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $filesData,
            'pagination' => [
                'current_page' => $files->currentPage(),
                'total_pages' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total_items' => $files->total()
            ]
        ]);
    }

    public function uploadSingle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Generate unique filename
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Store file
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Save file record
            $uploadedFile = UploadedFile::create([
                'original_name' => $originalName,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'disk' => 'public',
                'metadata' => [
                    'uploaded_at' => now(),
                    'ip_address' => $request->ip()
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'id' => $uploadedFile->id,
                    'original_name' => $uploadedFile->original_name,
                    'file_size' => $uploadedFile->file_size_human,
                    'mime_type' => $uploadedFile->mime_type,
                    'url' => $uploadedFile->url,
                    'uploaded_at' => $uploadedFile->created_at
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFile($id)
    {
        $file = UploadedFile::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        try {

            $file->delete(); // Will also delete from storage due to boot method

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadFile($id)
    {
        $file = UploadedFile::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        if (!Storage::disk($file->disk)->exists($file->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on disk'
            ], 404);
        }

        return Storage::disk($file->disk)->download($file->file_path, $file->original_name);
    }
}

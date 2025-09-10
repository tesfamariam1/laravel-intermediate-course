<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UploadedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'disk',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    // protected appends = ['url'];

    public function getUrlAttribute()
    {
        if ($this->disk === 'public') {
            return Storage::disk('public')->url($this->file_path);
        }

        return route('files.download', $this->id);
    }

    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($file) {
            Storage::disk($file->disk)->delete($file->file_path);
        });
    }
}

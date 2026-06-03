<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorage
{
    protected static function modelPath($userId, $file)
    {
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();

        $safeName = Str::slug($original);
        $name = $safeName . '_' . time() . '_' . Str::random(4) . '.' . $ext;

        return "assets/user-$userId/$name";
    }

    protected static function thumbPath($userId, $file)
    {
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();

        $safeName = Str::slug($original);
        $name = 'thumb_' . $safeName . '_' . time() . '_' . Str::random(4) . '.' . $ext;

        return "thumbnails/user-$userId/$name";
    }

    protected static function profilePath($userId, $file)
    {
        $ext = $file->getClientOriginalExtension();
        $name = 'profile_' . $userId . '.' . $ext;

        return "thumbnails/user-$userId/profile/$name";
    }

    private static function supabaseUpload($path, $file)
    {
        $bucket     = env('AWS_BUCKET');
        $url        = env('SUPABASE_URL');
        $serviceKey = env('SUPABASE_SERVICE_ROLE_KEY');

        if (!$bucket || !$url || !$serviceKey) {
            throw new \Exception('Supabase configuration missing');
        }

        $fileContent = file_get_contents($file->getRealPath());
        if ($fileContent === false) {
            throw new \Exception('Failed to read file');
        }

        $mimeType = strtolower($file->getClientOriginalExtension()) === 'glb'
            ? 'model/gltf-binary'
            : ($file->getClientMimeType() ?: 'application/octet-stream');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $serviceKey,
            'Content-Type'  => $mimeType,
            'x-upsert'      => 'true',
        ])->withBody(
            $fileContent,
            $mimeType
        )->timeout(120)->post("{$url}/storage/v1/object/{$bucket}/{$path}");

        if (!$response->successful()) {
            throw new \Exception('Supabase upload failed: ' . $response->status() . ' - ' . $response->body());
        }

        return $path;
    }

    private static function supabaseDelete($path)
    {
        if (!$path) return;

        $bucket     = env('AWS_BUCKET');
        $url        = env('SUPABASE_URL');
        $serviceKey = env('SUPABASE_SERVICE_ROLE_KEY');

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $serviceKey,
        ])->delete("{$url}/storage/v1/object/{$bucket}/{$path}");
    }

    private static function publicUrl($path)
    {
        $url    = env('SUPABASE_URL');
        $bucket = env('AWS_BUCKET');

        return "{$url}/storage/v1/object/public/{$bucket}/{$path}";
    }

    public static function uploadModel($userId, $file)
    {
        $path = self::modelPath($userId, $file);
        return self::supabaseUpload($path, $file);
    }

    public static function uploadThumbnail($userId, $file)
    {
        $path = self::thumbPath($userId, $file);
        return self::supabaseUpload($path, $file);
    }

    public static function uploadProfile($userId, $file)
    {
        foreach (['jpg', 'jpeg', 'png', 'webp', 'gif'] as $ext) {
            self::supabaseDelete("thumbnails/user-$userId/profile/profile_$userId.$ext");
        }

        $path = self::profilePath($userId, $file);
        return self::supabaseUpload($path, $file);
    }

    public static function updateThumbnail($model, $file)
    {
        $userId = $model->user_id;
    
        // 1. Hapus file lama jika ada di database
        if ($model->thumbnail_path) {
            // Hapus path yang tersimpan di kolom thumbnail_path
            self::supabaseDelete($model->thumbnail_path);
        }
    
        // 2. Generate path baru yang unik
        $path = self::thumbPath($userId, $file);
    
        // 3. Upload file baru
        $uploadStatus = self::supabaseUpload($path, $file);
    
        if ($uploadStatus) {
            // 4. Update path baru ke database agar sinkron
            $model->update(['thumbnail_path' => $path]);
        }
    
        return $uploadStatus;
    }

    public static function getModelUrl($path)
    {
        return self::publicUrl($path);
    }

    public static function getThumbnailUrl($path)
    {
        return self::publicUrl($path);
    }

    public static function getProfileUrl($path)
    {
        return self::publicUrl($path);
    }

    public static function deleteModel($path)
    {
        self::supabaseDelete($path);
    }

    public static function deleteThumbnail($path)
    {
        self::supabaseDelete($path);
    }

    public static function deleteProfile($path)
    {
        self::supabaseDelete($path);
    }

    public static function deleteAll($model)
    {
        self::deleteModel($model->model_path);
        self::deleteThumbnail($model->thumbnail_path);
    }
}

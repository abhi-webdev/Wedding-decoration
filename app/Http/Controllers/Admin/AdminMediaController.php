<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminMediaController extends Controller
{
    /**
     * Display a simple, elegant media library of all uploaded files.
     */
    public function index(Request $request)
    {
        $baseDir = public_path('uploads');
        if (!File::isDirectory($baseDir)) {
            File::makeDirectory($baseDir, 0755, true, true);
        }

        $folders = [
            'all' => 'All Media',
            'decorations' => 'Decorations',
            'decoration-gallery' => 'Decoration Gallery',
            'categories' => 'Categories',
            'packages' => 'Packages',
            'offers' => 'Offers & Coupons',
            'gallery' => 'Gallery Showcase',
            'videos' => 'Videos & Reels',
            'video-thumbnails' => 'Video Thumbnails',
            'site' => 'Site & Brand',
        ];

        $selectedFolder = $request->input('folder', 'all');
        $search = strtolower(trim($request->input('search', '')));

        $allFiles = [];
        $scanDirs = ($selectedFolder !== 'all' && isset($folders[$selectedFolder])) 
            ? [$baseDir . DIRECTORY_SEPARATOR . $selectedFolder]
            : File::directories($baseDir);

        // Include root files if all is selected
        if ($selectedFolder === 'all') {
            $scanDirs[] = $baseDir;
        }

        foreach ($scanDirs as $dir) {
            if (!File::isDirectory($dir)) continue;
            
            $folderName = basename($dir);
            if ($dir === $baseDir) {
                $folderName = 'root';
            }

            $files = File::files($dir);
            foreach ($files as $file) {
                try {
                    $fileName = $file->getFilename();
                    if ($search && !str_contains(strtolower($fileName), $search)) {
                        continue;
                    }

                    $relPath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $relPath = str_replace('\\', '/', $relPath);
                    $ext = strtolower($file->getExtension());
                    $isVideo = in_array($ext, ['mp4', 'webm', 'mov', 'ogg']);
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif', 'ico']);
                    $size = $file->getSize();
                    $mtime = $file->getMTime();

                    $allFiles[] = [
                        'name' => $fileName,
                        'path' => $relPath,
                        'url' => asset($relPath),
                        'folder' => $folderName,
                        'extension' => $ext,
                        'size' => $size,
                        'formatted_size' => $this->formatBytes($size),
                        'modified_at' => $mtime,
                        'is_video' => $isVideo,
                        'is_image' => $isImage,
                    ];
                } catch (\Throwable $e) {
                    continue;
                }
            }
        }

        // Sort by newest modified first
        usort($allFiles, function ($a, $b) {
            return $b['modified_at'] <=> $a['modified_at'];
        });

        $totalCount = count($allFiles);
        $totalSize = array_sum(array_column($allFiles, 'size'));
        $formattedTotalSize = $this->formatBytes($totalSize);

        return view('admin.media.index', compact('allFiles', 'folders', 'selectedFolder', 'search', 'totalCount', 'formattedTotalSize'));
    }

    /**
     * Safely delete an uploaded file.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $relPath = $request->input('path');
        
        // Security check: ensure path is strictly inside uploads/
        if (!str_starts_with($relPath, 'uploads/') || str_contains($relPath, '..')) {
            return back()->with('error', 'Invalid media file path.');
        }

        $fullPath = public_path($relPath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);

            AdminActivityLog::log(
                auth()->id(),
                'delete',
                'media',
                null,
                "Deleted media file: {$relPath}"
            );

            return back()->with('success', "Media file '{$relPath}' deleted successfully.");
        }

        return back()->with('error', 'File not found on server.');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

<?php

namespace App\Imports;

use App\Models\Size;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SizesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['name'])) {
            return null;
        }

        $name = trim($row['name']);
        $type = trim($row['type'] ?? '');
        $unit = trim($row['unit'] ?? 'L');
        $value = trim($row['value'] ?? '0');
        $imageUrl = trim($row['image'] ?? '');

        $imagePath = null;

        // Download and store image if URL is provided
        if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            try {
                $imageContents = file_get_contents($imageUrl);
                if ($imageContents !== false) {
                    // Generate unique filename
                    $extension = $this->getImageExtension($imageUrl);
                    $filename = Str::slug($name) . '_' . time() . '.' . $extension;

                    // Store in public disk under sizes folder
                    Storage::disk('public')->put('sizes/' . $filename, $imageContents);
                    $imagePath = 'sizes/' . $filename;
                }
            } catch (\Exception $e) {
                // Log error but continue with import
                Log::warning('Failed to download image for size: ' . $name . ' - ' . $e->getMessage());
            }
        }

        return new Size([
            'name' => $name,
            'type' => $type,
            'unit' => $unit,
            'value' => $value,
            'image' => $imagePath,
        ]);
    }

    /**
     * Get image extension from URL
     */
    private function getImageExtension($url)
    {
        $extension = 'jpg'; // default

        // Try to get extension from URL
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
        if (isset($pathInfo['extension'])) {
            $ext = strtolower($pathInfo['extension']);
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $extension = $ext;
            }
        }

        // If no extension found, try to detect from content
        try {
            $headers = get_headers($url, 1);
            if (isset($headers['Content-Type'])) {
                $contentType = is_array($headers['Content-Type']) ? $headers['Content-Type'][0] : $headers['Content-Type'];
                switch ($contentType) {
                    case 'image/jpeg':
                        $extension = 'jpg';
                        break;
                    case 'image/png':
                        $extension = 'png';
                        break;
                    case 'image/gif':
                        $extension = 'gif';
                        break;
                    case 'image/webp':
                        $extension = 'webp';
                        break;
                }
            }
        } catch (\Exception $e) {
            // Keep default extension
        }

        return $extension;
    }
}

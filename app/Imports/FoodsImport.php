<?php

namespace App\Imports;

use App\Models\Food;
use App\Models\Size;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FoodsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['name']) || empty($row['sizes']) || empty($row['prices'])) {
            return null;
        }

        $name = trim($row['name']);
        $imageUrl = trim($row['image'] ?? '');
        $imagePath = null;
        $description = trim($row['description'] ?? '');
        $sizesString = trim($row['sizes']);
        $pricesString = trim($row['prices']);

        // Download and store image if URL is provided
        if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            try {
                $imageContents = file_get_contents($imageUrl);
                if ($imageContents !== false) {
                    // Generate unique filename
                    $extension = $this->getImageExtension($imageUrl);
                    $filename = Str::slug($name) . '_' . time() . '.' . $extension;

                    // Store in public disk under sizes folder
                    Storage::disk('public')->put('foods/' . $filename, $imageContents);
                    $imagePath = 'foods/' . $filename;
                }
            } catch (\Exception $e) {
                // Log error but continue with import
                Log::warning('Failed to download image for food: ' . $name . ' - ' . $e->getMessage());
            }
        }

        // Explode comma-separated values
        $sizeIds = array_map('trim', explode(',', $sizesString));
        $prices = array_map('trim', explode(',', $pricesString));

        // Validate that sizes and prices arrays have the same length
        if (count($sizeIds) !== count($prices)) {
            Log::warning('Sizes and prices count mismatch for food import', [
                'food_name' => $name,
                'sizes_count' => count($sizeIds),
                'prices_count' => count($prices)
            ]);
            return null;
        }

        // Validate all sizes exist
        $sizeData = [];
        foreach ($sizeIds as $index => $sizeId) {
            $size = Size::find($sizeId);
            if (!$size) {
                Log::warning('Size not found for food import', [
                    'food_name' => $name,
                    'size_id' => $sizeId,
                    'index' => $index
                ]);
                return null;
            }

            $price = floatval($prices[$index]);
            if ($price <= 0) {
                Log::warning('Invalid price for food import', [
                    'food_name' => $name,
                    'size_id' => $sizeId,
                    'price' => $price
                ]);
                return null;
            }

            $sizeData[$sizeId] = ['price' => $price];
        }

        // Check if food already exists
        $food = Food::where('name', $name)->first();

        if (!$food) {
            // Create new food
            $food = Food::create([
                'name' => $name,
                'description' => $description,
                'image' => $imagePath,
            ]);
        }
        
        // Attach sizes with prices (this will update if already exists)
        $food->sizes()->syncWithoutDetaching($sizeData);

        // Return null since we're not creating a new model for each row
        return null;
    }

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

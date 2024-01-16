<?php

class ImageProcessor {
    public static function processImages($imageFiles, $destinationFolder = '../uploads/artworks/', $maxSize = 800, $jpegQuality = 85) {
        $savedImageNames = [];

        if (count($imageFiles['name']) > 0) {
            foreach ($imageFiles['name'] as $key => $value) {
                $uploadedFile = [
                    'name' => $imageFiles['name'][$key],
                    'type' => $imageFiles['type'][$key],
                    'tmp_name' => $imageFiles['tmp_name'][$key],
                    'error' => $imageFiles['error'][$key],
                    'size' => $imageFiles['size'][$key],
                ];

                $result = self::processImage($uploadedFile, $destinationFolder, $maxSize, $jpegQuality);

                if ($result !== false) {
                    $savedImageNames[] = $result;
                }
            }
        }

        return $savedImageNames;
    }

    public static function processImage($uploadedFile, $destinationFolder = '../uploads/artworks/', $maxSize = 800, $jpegQuality = 85) {
        // Check if there are no errors during the file upload
        print_r($uploadedFile);
        if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
            // Load the image based on the file type
            $image = self::loadImage($uploadedFile);

            // Calculate new width and height to maintain aspect ratio
            list($newWidth, $newHeight) = self::calculateResizedDimensions($image, $maxSize);

            // Resize the image
            if ($newWidth != imagesx($image) || $newHeight != imagesy($image)) {
                $resizedImage = imagescale($image, $newWidth, $newHeight);
            } else {
                $resizedImage = $image;
            }

            // Generate a unique filename
            $uniqueFilename = self::generateUniqueFilename();
            $outputFile = $destinationFolder . $uniqueFilename;

            // Create a JPEG version of the resized image
            if (imagejpeg($resizedImage, $outputFile, $jpegQuality)) {
                // Free up memory
                imagedestroy($image);
                imagedestroy($resizedImage);

                // echo 'Conversion and resizing completed successfully for ' . $uploadedFile['name'] . '. Saved as ' . $uniqueFilename . '<br>';
                
                return $outputFile;
            }
            // else {
            //     echo 'Error during image processing for ' . $uploadedFile['name'] . '. Please try again.<br>';
            // }
        }
        // else {
        //     echo 'Error during file upload for ' . $uploadedFile['name'] . '. Please try again.<br>';
        // }

        return false;
    }

    private static function loadImage($uploadedFile) {
        $extension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($uploadedFile['tmp_name']);
            case 'png':
                return imagecreatefrompng($uploadedFile['tmp_name']);
            default:
                die('Unsupported file format. Please upload JPEG or PNG files.');
        }
    }

    private static function calculateResizedDimensions($image, $maxSize) {
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        if ($originalWidth > $maxSize || $originalHeight > $maxSize) {
            if ($originalWidth > $originalHeight) {
                $newWidth = $maxSize;
                $newHeight = intval($originalHeight * ($maxSize / $originalWidth));
            } else {
                $newHeight = $maxSize;
                $newWidth = intval($originalWidth * ($maxSize / $originalHeight));
            }
            return [$newWidth, $newHeight];
        }
        return [$originalWidth, $originalHeight];
    }

    private static function generateUniqueFilename() {
        return uniqid() . '.jpg';
    }

    public static function deleteImage($path) {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}

?>

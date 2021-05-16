<?php

namespace App\Http\Helpers;

use SplFileInfo;
use imageLib;

// Event for Image upload,resize and watermark

/**
 * Upload image trait
 */
trait UploadImageTrait
{

    /**
     * upload image
     * @param type $imageData
     * @return string|boolean
     */
    public function uploadImage($imageData)
    {
        /*
         * prepare image config params
         */
        $default_path = config('image_upload.default_path');
        $is_resize = (isset($imageData['is_resize']) && $imageData['is_resize']) ? $imageData['is_resize'] : config('image_upload.is_resize');
        $is_watermark = (isset($imageData['is_watermark']) && $imageData['is_watermark']) ? $imageData['is_watermark'] : config('image_upload.is_watermark');
        $is_watermark_resize = (isset($imageData['is_watermark_resize']) && $imageData['is_watermark_resize']) ? $imageData['is_watermark_resize'] : config('image_upload.is_watermark_resize');
        $dimestions = (isset($imageData['dimensions']) && $imageData['dimensions']) ? $imageData['dimensions'] : config('image_upload.dimensions');
        $watermark_img = (isset($imageData['watermark_image']) && $imageData['watermark_image']) ? $imageData['watermark_image'] : config('image_upload.watermark_image');
        $image = $imageData['image'] ? $imageData['image'] : null;
        $folder_name = (isset($imageData['folder_name']) && $imageData['folder_name']) ? $imageData['folder_name'] : '';
        $id = $imageData['id'];

        if ($image) {
            $fileName = $image->getClientOriginalName();
            $fileNameArr = explode('.', $fileName);
            $fileExt = end($fileNameArr);

            // $newFileName = time() . $id . '.' . $fileExt;
            $newFileName = $id . '.' . $fileExt;
            $desc_path = $default_path . '/' . $folder_name . '/' . $id . '/';

            /* ------------   UPLOAD IMAGE   ------------- */
            $image->move($desc_path, $newFileName);

            /* ------------  RESIZE IMAGE  ----------- */
            if ($is_resize) {
                $this->resizeImageDimenstions($folder_name, $dimestions, $is_watermark, $is_watermark_resize, $watermark_img, $default_path, $desc_path, $id, $newFileName);
            }

            if (!$is_resize && $is_watermark) {
                $descImg = $desc_path . "/watermark/" . $newFileName;
                $sourceImg = $desc_path . "/" . $newFileName;
                $this->applyWaterMarkOnImg($folder_name, $is_watermark_resize, $default_path, $sourceImg, $watermark_img, $descImg, $id);
            }
            return $newFileName;
        }
        return false;
    }

    /**
     * image dimenstions for resize
     * @param type $dimestions
     * @param type $is_watermark
     * @param type $is_watermark_resize
     * @param type $watermark_img
     * @param type $default_path
     * @param type $desc_path
     * @param type $id
     * @param type $newFileName
     * @return boolean
     */
    public function resizeImageDimenstions($folder_name, $dimestions, $is_watermark, $is_watermark_resize, $watermark_img, $default_path, $desc_path, $id, $newFileName)
    {
        if (empty($dimestions)) {
            return false;
        }

        foreach ($dimestions as $key => $dimension) {
            $imgSrc = $desc_path . $newFileName;
            $destW = $dimension[0];
            $destH = $dimension[1];
            $this->resizeImage($folder_name, $is_watermark, $is_watermark_resize, $default_path, $watermark_img, $desc_path, $id, $imgSrc, $destW, $destH, $newFileName);
        }
    }

    /**
     * create resize image
     * @param type $is_watermark
     * @param type $is_watermark_resize
     * @param type $default_path
     * @param type $watermark_img
     * @param type $basicPath
     * @param type $folderId
     * @param type $imgSrc
     * @param type $destW
     * @param type $destH
     * @param type $imageName
     * @param type $subfolderImagepath
     * @return boolean
     */
    public function resizeImage($folder_name, $is_watermark, $is_watermark_resize, $default_path, $watermark_img, $basicPath, $folderId, $imgSrc, $destW, $destH, $imageName, $subfolderImagepath = '') {
        $imagePath = $basicPath;

        $thumbFolderexists = is_dir($basicPath . '/thumbnails');
        $subFolderExists = is_dir($subfolderImagepath . '/thumbnails');

        $isMineTypeMatched = true;
        if (!$thumbFolderexists) {
            @mkdir($basicPath . '/thumbnails');
            @chmod($basicPath . '/thumbnails', 0777);
        }

        $subfolderImagepath = ($subfolderImagepath) ? $subfolderImagepath : $basicPath;

        $sizeSubfolder = $subfolderImagepath . "/thumbnails/" . $destW . "x" . $destH;
        $sizeSubImageExists = is_dir($sizeSubfolder);

        if (!$sizeSubImageExists) {
            @mkdir("$sizeSubfolder");
            @chmod("$sizeSubfolder", 0777);
        }

        @chmod("$imgSrc", 0777);

        $info = new SplFileInfo($imageName);
        $imgType = $info->getExtension();

        $sizeArr = @getimagesize($basicPath . '/' . $imageName);

        $mineTypeImg = isset($sizeArr['mime']) ? $sizeArr['mime'] : '';

        $isMineTypeMatched = $this->checkIsImgFile($imgType, $mineTypeImg);

        $width = $sizeArr[0];
        $height = $sizeArr[1];

        if (($width > $destW || $height > $destH) && $isMineTypeMatched == true) {
            require_once(realpath(dirname(__DIR__)) . '/Resize/php_image_magician.php');

            $magicianObj = new imageLib($basicPath . '/' . $imageName);

            if (count($magicianObj->getErrors()) == 0) {
                $magicianObj->resizeImage($destW, $destH, '0', true);
                $magicianObj->saveImage($sizeSubfolder . '/' . $imageName, 100);
            }
        } else {
            @chmod("$basicPath. '/' . $imageName", 0777);
            @chmod("$sizeSubfolder", 0777);
            copy($basicPath . '/' . $imageName, $sizeSubfolder . '/' . $imageName);
        }

        if ($is_watermark) {
            if (/* $sizeImageExists && */$basicPath == $imagePath) {
                $descImg = $basicPath . "/watermark/" . $imageName;
                $sourceImg = $basicPath . "/" . $imageName;
                $thumbSourceImg = $thumbDescImg = '';
                if ($is_watermark_resize) {
                    $thumbSourceImg = $basicPath . "/thumbnails/" . $destW . "x" . $destH . '/' . $imageName;
                    $thumbDescImg = $basicPath . "/watermark/thumbnails/" . $destW . "x" . $destH . '/' . $imageName;
                }
                $this->applyWaterMarkOnImg($folder_name, $is_watermark_resize, $default_path, $sourceImg, $watermark_img, $descImg, $folderId, $destW, $destH, $thumbSourceImg, $thumbDescImg);
            }
        }
        if ($basicPath == $imagePath && $destW == 350 && $destH == 350) {
            $sizeArrActual = @getimagesize($subfolder . '/' . $imageName);

            $actualImgW = $sizeArrActual[0];
            $actualImgH = $sizeArrActual[1];

            $actualImgDimention['width'] = $actualImgW;
            $actualImgDimention['height'] = $actualImgH < 350 ? $actualImgH : 350;
            $actualImgDimention['setDimension'] = true;

            return $actualImgDimention;
        }
    }

    /**
     * create watermark of image
     * @param type $is_watermark_resize
     * @param type $default_path
     * @param type $sourceImg
     * @param type $watermark_img
     * @param type $descImg
     * @param type $folderId
     * @param type $destW
     * @param type $destH
     * @param type $thumbSourceImg
     * @param type $thumbDescImg
     * @return string|boolean
     */
    public function applyWaterMarkOnImg($folder_name, $is_watermark_resize, $default_path, $sourceImg, $watermark_img, $descImg, $folderId, $destW = 0, $destH = 0, $thumbSourceImg = '', $thumbDescImg = '') {
        if (!file_exists($watermark_img)) {
            return 'watermark image not exist';
        }

        $stamp = imagecreatefrompng($watermark_img);
        $im = imagecreatefromjpeg($sourceImg);
        $desc_path = $default_path . '/' . $folder_name . '/' . $folderId . '/watermark/';
        $marge_right = 8;
        $marge_bottom = 5;

        // Set the margins for the stamp and get the height/width of the stamp image
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        if (!is_dir($desc_path)) {
            mkdir($desc_path, 0777, true);
        }
        copy($sourceImg, $descImg);
        // Copy the stamp image onto our photo using the margin offsets and the photo
        // width to calculate positioning of the stamp.
        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

        // Final processing Creating The Image
        imagejpeg($im, $descImg, 100);

        $targetArr = explode('.', $sourceImg);
        $imageType = end($targetArr);
        //echo $watermarkFile;exit;
        $watermark = imagecreatefrompng($watermark_img);
        //imagecreatefrompng($watermark);
        imagesavealpha($watermark, true);
        if ($imageType == "png") {
            $img = imagecreatefrompng($sourceImg);
        } else {
            $img = imagecreatefromjpeg($sourceImg);
        }

        if ($is_watermark_resize) {
            $thumb_desc_path = $default_path . '/' . $folder_name . '/' . $folderId . '/watermark/thumbnails/' . $destW . "x" . $destH . '/';
            $thumbim = imagecreatefromjpeg($thumbSourceImg);

            //    $water_thumb_path = $default_path . '/profile/' . $folderId . '/watermark/thumbnails/';
            // Set the margins for the stamp and get the height/width of the stamp image
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);
            if (!is_dir($thumb_desc_path)) {
                mkdir($thumb_desc_path, 0777, true);
            }
            copy($thumbSourceImg, $thumbDescImg);
            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
            imagecopy($thumbim, $stamp, imagesx($thumbim) - $sx - $marge_right, imagesy($thumbim) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

            // Final processing Creating The Image
            imagejpeg($thumbim, $thumbDescImg, 100);

            $targetArr = explode('.', $thumbSourceImg);
            $imageType = end($targetArr);
            //echo $watermarkFile;exit;
            $watermark = imagecreatefrompng($watermark_img);
            //imagecreatefrompng($watermark);
            imagesavealpha($watermark, true);
            if ($imageType == "png") {
                $img = imagecreatefrompng($thumbSourceImg);
            } else {
                $img = imagecreatefromjpeg($thumbSourceImg);
            }
        }
        return true;
    }

    /**
     * check valid image file or not
     * @param type $ext
     * @param type $mine
     * @return boolean
     */
    public function checkIsImgFile($ext, $mine)
    {
        $isValidImg = false;
        $ext = strtolower($ext);

        if ($ext == 'jpg') {
            $ext = "jpeg";
        }

        if (strpos($mine, $ext) !== false) {
            $isValidImg = true;
        }

        return $isValidImg;
    }
}

<?php

namespace App\Http\Helpers;

use App\Models\User;
use AWS;
//use App\Models\PropertyImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Upload image trait
 */
trait UploadImageOnBucket
{
    /**
     * Upload user profile image on bucket
     *
     * @param string $folderName
     * @param Object $file
     * @param string $fileName
     * @param int $ids [logged in user id]
     * @return boolean
     */
    public function imageUploadOnBucket($foldersName, $file, $fileName, $ids)
    {
        //Check user exist and propfile image is uploaded or not
        if (!empty($file)) {
            //Local image path
            $sourcePath = public_path() . '/uploads/' . $foldersName . '/original/' . $fileName;
            //Check Image is exist or not
            if ($sourcePath) {
                //Array for folder name as per size
                $folderName = ['original', '200x200', '50x50'];
                foreach ($folderName as $folder) {
                    if ($folder == 'original') {
                        $sourcePath = public_path() . '/uploads/' . $foldersName . '/' . $ids . '/' . $fileName;
                        $path = 'uploads/'. $foldersName .'/' . $ids . '/' . $fileName;
                    } else {
                        $arr = explode("x", $folder, 2);
                        //. '/THUMB_' . $arr[0] . '_'
                        $sourcePath = public_path() . '/uploads/' . $foldersName .
                                '/' . $ids . '/thumbnails/' . $folder . '/' . $fileName;
                        $path = 'uploads/'. $foldersName .'/' . $ids . '/thumbnails/'. $folder . '/' . $fileName;
                    }

                    //Create object of aws s3 bucket
                    $s3 = AWS::createClient('s3');
                    $result = '';
                    $result = $s3->putObject([
//                        'client_defaults' => ['verify' => false]
                        'Bucket' => config('aws.s3_bucket'),
                        'Body' => fopen($sourcePath, 'rb'),
                        'Key' => $path, //'uploads/'. $foldersName .'/' . $folder . '/' . $fileName,
                    ]);
                }
            }
            //If image uploaded on s3 bucket then delete image from local storage
            if ($result) {
                $oldProfile = '';
                $oldProfile = $fileName;
                $profilePath = $oldProfile;
                $defaultPath = public_path('uploads/' . $foldersName . '/' . $ids);
                $originalPath = $defaultPath . '/' . $profilePath;
                $twoHundred = $defaultPath . '/thumbnails/200x200/' . $profilePath;
                // $fourFiftyHundred = $defaultPath . '/thumbnails/450x450/' . $profilePath;
                // $fourHundred = $defaultPath . '/thumbnails/400x400/' . $profilePath;
                $fifty = $defaultPath . '/thumbnails/50x50/' . $profilePath;
                //Delete image from local storage
                if ($oldProfile != '' && file_exists($originalPath)) {
                    // echo $defaultPath;exit;
                    File::delete($originalPath);
                    File::delete($fifty);
                    File::delete($twoHundred);
                    // File::delete($fourFiftyHundred);
                    // File::delete($fourHundred);
                    File::deleteDirectory($defaultPath);
                }
            }
        }
        return true;
    }
    
    /**
     * Upload Document Original on the s3 Bucket
     *
     * @param string $folderName
     * @param Object $file
     * @param string $fileName
     * @param int $ids [logged in user id]
     * @return boolean
     */
    public function documentUploadOnBucket($foldersName, $file, $fileName, $ids)
    {
        //Check user exist and propfile image is uploaded or not
        if (!empty($file)) {
            //Local image path
            $sourcePath = public_path() . '/uploads/' . $foldersName . '/original/' . $fileName;
            //Check Image is exist or not
            if ($sourcePath) {
                //Array for folder name as per size
                $folderName = ['original'];
                foreach ($folderName as $folder) {
                    if ($folder == 'original') {
                        $sourcePath = public_path() . '/uploads/' . $foldersName . '/' . $ids . '/' . $fileName;
                        $path = 'uploads/'. $foldersName .'/' . $ids . '/' . $fileName;
                    }

                    //Create object of aws s3 bucket
                    $s3 = AWS::createClient('s3');
                    $result = '';
                    $result = $s3->putObject([
                        'Bucket' => config('aws.s3_bucket'),
                        'Body' => fopen($sourcePath, 'rb'),
                        'Key' => $path,
                    ]);
                }
            }
            //If image uploaded on s3 bucket then delete image from local storage
            if ($result) {
                $oldProfile = '';
                $oldProfile = $fileName;
                $profilePath = $oldProfile;
                $defaultPath = public_path() . '/uploads/' . $foldersName . '/' . $ids;
                $originalPath = $defaultPath . '/' . $profilePath;
                
                //Delete image from local storage
                if ($oldProfile != '' && file_exists($originalPath)) {
                    File::delete($originalPath);
                    File::deleteDirectory($defaultPath);
                }
            }
        }
        return true;
    }

    /**
     * Upload Property image on s3 bucket
     * @param type $propertyId
     * @return boolean
     */
    public function alarmImageUploadBucket($createdImage)
    {
        $folderName = ['original', '320x240', '80x80'];
        foreach ($folderName as $folder) {
            $sourcePath = public_path() . '/uploads/alarm_assets/' . $folder . '/' . $createdImage->filename;
            //Create object of aws s3 bucket
            $s3 = AWS::createClient('s3');
            $result = '';
            //Upload image on s3 bucket
            $result = $s3->putObject([
                'Bucket' => config('aws.s3_bucket'),
                'Body' => fopen($sourcePath, 'rb'),
                'Key' => 'uploads/alarm_assets/' . $folder . '/' . $createdImage->filename,
            ]);
        }

        //If image uploaded on s3 bucket then delete image from local storage
        if ($result) {
            $oldProfile = '';
            $oldProfile = $createdImage->filename;
            $profilePath = $oldProfile;
            $defaultPath = config('image_upload.default_path') . '/uploads/alarm_assets';
            $originalPath = $defaultPath . '/original/' . $profilePath;
            $mediumThumbnail = $defaultPath . '/320x240/' . $profilePath;
            $thumbnail = $defaultPath . '/80x80/' . $profilePath;
            //Delete image from local storage
            if ($oldProfile != '' && file_exists($originalPath)) {
                File::delete($originalPath);
                File::delete($mediumThumbnail);
                File::delete($thumbnail);
            }
        }
        return true;
    }

    /**
     * Get random string for file name
     *
     * @param type $length
     * @return string
     */
    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

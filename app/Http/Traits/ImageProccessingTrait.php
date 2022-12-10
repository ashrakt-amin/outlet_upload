<?php
namespace App\Http\Traits;

use App\Models\ItemImage;
use Intervention\Image\Facades\Image;
// use Image;
// use Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Trait ImageProccessingTrait {

    /**
     * img extenstions
     */
    public function getMime ($mime){
        if ($mime == 'image/jpeg')
            $extension = '.jpg';
        elseif ($mime == 'image/png')
            $extension = '.png';
        elseif ($mime == 'image/gif')
            $extension = '.gif';
        elseif ($mime  == 'image/svg+xml' )
            $extension = '.svg';
        elseif ($mime == 'image/tiff')
            $extension = '.tiff';
        elseif ($mime == 'image/webp')
            $extension = '.webp';

        return $extension;
    }

    /**
     * Set Image
     */
    public function imageName($image)
    {
        $img = Image::make($image);
        $extension = $this->getMime($img->mime());

        $strRandom = Str::random(8);

        return $strRandom;
    }

    /**
     * Set Image
     */
    public function setImage($image)
    {
        $img = Image::make($image);
        $extension = $this->getMime($img->mime());

        $strRandom = Str::random(8);
        $imgPath   = $strRandom.time().$extension;
        $img->save(storage_path('app/imagesFb').'/'.$imgPath);

        return $imgPath;
    }

    /**
     * update image width and height
     */
    public function aspectForResize($image, $width, $height)
    {
        // $this->imageName($image);
        $img = Image::make($image);
        $extension = $this->getMime($img->mime());

        $strRandom = Str::random(8);
        $img->resize($width, $height, function($constraint) {
            $constraint->aspectRatio();
        });
        $imgPath   = $strRandom.time().$extension;
        $img->save(storage_path('app/imagesFb').'/'.$imgPath);

        return $imgPath;
    }

    /**
     * crop image width and height
     */
    public function aspectForCrop($image, $width, $height)
    {
        // $this->imageName($image);
        $img = Image::make($image);
        $extension = $this->getMime($img->mime());

        $strRandom = Str::random(8);
        $img->crop($width, $height, 0, 0);
        $imgPath   = $strRandom.time().$extension;
        $img->save(storage_path('app/imagesFb').'/'.$imgPath);

        return $imgPath;
    }

    /**
     * Thumbnail image width and height
     */
    public function ImageThumbnail($image, $thumb = false)
    {
        $dataArray = array();
        $dataArray['image'] = $this->setImage($image);
        if ($thumb) {
            $dataArray['thumbnailSm'] = $this->aspectForResize($image, 200, 200);
            $dataArray['thumbnailMd'] = $this->aspectForResize($image, 400, 400);
            $dataArray['thumbnailLg'] = $this->aspectForResize($image, 600, 600);
        }
        return $dataArray;
    }

    /**
     * Delete image
     */
    public function deleteImage($imgPath)
    {
        // if (is_file(Storage::disk('imagesFb')->path($imgPath))) {
        //     if (file_exists(Storage::disk('imagesFb')->path($imgPath))) {
        //         unlink(Storage::disk('imagesFb')->path());
        //     }
        // }
    }
}

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
        if ($mime == 'image/jpg')
            $extension = '.jpg';
        elseif ($mime == 'image/jpeg')
            $extension = '.jpeg';
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
        $imgPath   = $strRandom.time().$extension;

        return $imgPath;
    }

    /**
     * Set Image
     */
    public function setImage($image, $ownerId, $path)
    {
        $img = Image::make($image);

        $name            = $image->getClientOriginalName();

        $extension = $this->getMime($img->mime());
        $strRandom = Str::random(8);
        // $imgPath   = $strRandom.time().$extension;
        $imgPath   = $ownerId.$name;
        $img->save(public_path('assets/images/uploads/'.$path).'/'.$imgPath);

        return $imgPath;
    }

    /**
     * update image width and height
     */
    public function aspectForResize($image, $ownerId, $width, $height, $path)
    {
        $img = Image::make($image);

        $name            = $image->getClientOriginalName();

        $extension = $this->getMime($img->mime());
        $strRandom = Str::random(8);
        $img->resize($width, $height, function($constraint) {
            $constraint->aspectRatio();
        });
        // $imgPath   = $strRandom.time().$extension;
        $imgPath   = $ownerId.$name;
        $img->save(public_path('assets/images/uploads/'.$path).'/'.$imgPath);

        return $imgPath;
    }

    /**
     * crop image width and height
     */
    public function aspectForCrop($image, $ownerId, $width, $height, $path)
    {
        // $this->imageName($image);
        $img = Image::make($image);

        $name            = $image->getClientOriginalName();

        $extension = $this->getMime($img->mime());
        $strRandom = Str::random(8);
        $img->crop($width, $height, 0, 0);
        // $imgPath   = $strRandom.time().$extension;
        $imgPath   = $ownerId.$name;
        $img->save(public_path('assets/images/uploads/'.$path).'/'.$imgPath);

        return $imgPath;
    }

    /**
     * Thumbnail image width and height
     */
    public function ImageThumbnail($image, $ownerId, $path, $thumb = false)
    {
        // $dataArray = array();
        // $dataArray['image'] = $this->setImage($image, $ownerId, $path);
        // if ($thumb) {
        //     $dataArray['thumbnailSm'] = $this->aspectForResize($image, $ownerId, $path, 200, 200);
        //     $dataArray['thumbnailMd'] = $this->aspectForResize($image, $ownerId, $path, 400, 400);
        //     $dataArray['thumbnailLg'] = $this->aspectForResize($image, $ownerId, $path, 600, 600);
        // }
        // return $dataArray;

        $dataArray = array();
        $img = Image::make($image);

        $name            = $image->getClientOriginalName();
        $imgPath   = $ownerId.$name;
        $dataArray['image'] = $imgPath ;
        if ($thumb) {
            // sm image
            $dataArray['thumbnailSm'] = $img->resize(200, 200, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('assets/images/uploads/'.$path.'/sm').'/'.$imgPath);
            // md image
            $dataArray['thumbnailSm'] = $img->resize(400, 400, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('assets/images/uploads/'.$path.'/md').'/'.$imgPath);
            // lg image
            $dataArray['thumbnailSm'] = $img->resize(600, 600, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('assets/images/uploads/'.$path.'/lg').'/'.$imgPath);
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

    /**
     * store multi images for all models
     */
    public function storeMultiModelImages($image, $modelName, $relationColumnName, $relationColumnValue, $path)
    {
        $img = Image::make($image);
        $name            = $image->getClientOriginalName();
        $ext             = $image->getClientOriginalExtension();
        $filename        = rand(10, 100000).time().'.'.$ext;
        $image->save(public_path('assets/images/uploads/'.$path).'/'.$name);


        $relationColumnNameImage = $modelName;
        $relationColumnNameImage->$relationColumnName = $relationColumnValue;
        $relationColumnNameImage->img                 = $name;
        $relationColumnNameImage->save();
        return $image;

        // $levelImage = new LevelImage();
        // $levelImage->item_id = $item;
        // $levelImage->img     = $this->aspectForResize($image, $item, 600, 450, 'items');
        // $levelImage->save();
    }

}

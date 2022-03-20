<?php
/*
 * File name: Upload.php
 * Last modified: 2022.02.13 at 23:27:53
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Collection;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Upload extends Model implements HasMedia
{
    use HasMediaTrait {
        getMedia as protected getMediaTrait;
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    public $fillable = [
        'uuid'
    ];

    private $performed = 'default';

    /**
     * @param Media|null $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    // TODO
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        if($url){
            $array = explode('.', $url);
            $extension = strtolower(end($array));
            if (in_array($extension, ['jpg', 'png', 'gif', 'bmp', 'jpeg'])) {
                return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
            } else {
                return asset('images/icons/' . $extension . '.png');
            }
        }else{
            return asset('images/image_default.png');
        }
    }

    /**
     * @return string
     */
    public function getPerformed(): string
    {
        return $this->performed;
    }

    /**
     * @param string $performed
     */
    public function setPerformed(string $performed): void
    {
        $this->performed = $performed;
    }


    /**
     * get media
     * @param string $collectionName
     * @param array $filters
     * @return Collection
     */
    public function getMedia(string $collectionName = 'default', $filters = [])
    {

        if (count($this->getMediaTrait($collectionName))) {
            return $this->getMediaTrait($collectionName, $filters);
        }
        return $this->getMediaTrait('default', $filters);
    }
}

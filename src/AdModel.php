<?php
namespace Yoan1005\Admigen;

use Illuminate\Database\Eloquent\Model;
use Yoan1005\Admigen\AdTexte as Texte;
use Yoan1005\Admigen\AdPhoto as Photo;
use Image;

class AdModel extends Model {

    public static function boot() {
      parent::boot();
      self::observe(new \Yoan1005\Admigen\Observers\AdModelObserver);
    }

    public function textes($lang_id = 1, $key = null) {
      if($key)
        return Texte::where('umodel', class_basename($this))->where('utype', $key)->where('uid', $this->id)->where('lang_id', $lang_id)->first();

      return Texte::where('umodel', class_basename($this))->where('uid', $this->id)->where('lang_id', $lang_id)->get();
    }

    public function photos($lang_id = 1, $key = null) {
      if($key)
        return Photo::where('umodel', class_basename($this))->where('utype', $key)->where('lang_id', $lang_id)->where('uid', $this->id)->orderBy('position', 'ASC')->first();

      return Photo::where('umodel', class_basename($this))->where('lang_id', $lang_id)->where('uid', $this->id)->orderBy('position', 'ASC')->get();
    }

    public function saveTextes($lang_id = 1, $key, $value){
      return Texte::updateOrCreate(['lang_id' => $lang_id, 'umodel' => class_basename($this), 'utype' => $key, 'uid' => $this->id], ['data' => $value]);
    }
    public function saveImgs($lang_id = 1, $images){

      foreach ($images as $utype => $image) {

        if ($image) {

          if (is_int($utype))
            $utype = 'image_upload_'.time().'_'.$utype;

            $fichier = Photo::create(['lang_id' => $lang_id, 'umodel' => class_basename($this), 'utype' => $utype, 'uid' => $this->id, 'data' => '']);
            $public_path = 'userfiles/'.mb_strtolower(class_basename($this)).'s/';
            $path = public_path( $public_path );

            if (!is_dir($path)) {
                mkdir($path, 0777);
            }
            $photo = $image;
            $name = sha1(date('YmdHis') . str_random(30));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
            $resize_name = 'thumb_' . $name . '.' . $photo->getClientOriginalExtension();

            Image::make($photo)
                ->resize(250, null, function ($constraints) {
                    $constraints->aspectRatio();
                    $constraints->upsize();
                })
                ->save($path . '/' . $resize_name);

            Image::make($photo)
                ->resize(1600, null, function ($constraints) {
                    $constraints->aspectRatio();
                    $constraints->upsize();
                })
                ->save($path . '/' . $save_name);

            // $photo->move($path, $save_name);


            $fichier->data = '/'.$public_path.$save_name;
            $fichier->save();

        }

      }
      return true;

    }


}

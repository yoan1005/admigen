<?php
namespace  Yoan1005\Admigen\Observers;

use Yoan1005\Admigen\AdModel;

class AdModelObserver
{

    public function retrieved(Admodel $myModel) {

      $textes = $myModel->textes(1);
      $textes = $textes->pluck('data', 'utype');
      foreach ($textes as $key => $value) {
        $myModel->$key = $value;
      }

      $photos = $myModel->photos(1);

      // $photos = $photos->map(function ($obj) {
      //   $objT = new \stdClass;
      //    $objT->{$obj->id.'_'.$obj->utype} = $obj->data;
      //   return $objT;
      // });
      //
      $photos = $photos->pluck('data','uniq_utype');

      foreach ($photos as $key => $value) {
          $myModel->$key = $value;
      }
       return $myModel;
    }

}

<?php

namespace Yoan1005\Admigen;

use Illuminate\Database\Eloquent\Model;

class AdPhoto extends Model
{
    protected $table = "ad_photos";
    protected $fillable = ['id', 'lang_id', 'umodel', 'utype', 'uid', 'data'];

    public function getUniqUtypeAttribute() {
        return ucwords('img__' . $this->id . '__' . $this->utype);
    }

}

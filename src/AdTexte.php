<?php

namespace Yoan1005\Admigen;

use Illuminate\Database\Eloquent\Model;

class AdTexte extends Model
{
    protected $table = "ad_textes";
    protected $fillable = ['id', 'lang_id', 'umodel', 'utype', 'uid', 'data'];

}

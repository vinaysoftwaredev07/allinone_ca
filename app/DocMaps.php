<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class DocMaps extends Model
{

    public $table = 'docmaps';

    protected $fillable = [
        'service_id',
        'document_id',
    ];
    

}

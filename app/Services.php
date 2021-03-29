<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Services extends Model
{
    use SoftDeletes;
    
    public $table = 'services';
    
    public function __construct(){
        // echo "<pre>"; print_r($this); exit;
    }

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'icon',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');

    }

    public function documents()
    {
        return $this->belongsToMany(Documents::class, 'docmaps', 'service_id', 'document_id');

    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);

    }
}

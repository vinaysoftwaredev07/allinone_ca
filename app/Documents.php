<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Documents extends Model
{
    use SoftDeletes;

    public $table = 'documents';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');

    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);

    }

    public function documents()
    {
        return $this->belongsTo(Documents::class, 'docmaps', 'document_id', 'service_id');

    }
}

<?php
namespace Larakit\LkNg;

use Illuminate\Database\Eloquent\Model;
use Larakit\Helpers\HelperPhone;

class FeedbackFile extends Model {
    
    protected $table = 'feedback_files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'token',
        'ext',
        'size',
        'feedback_id',
    ];
    protected $appends  = [
        'url',
        'full_url',
    ];
    
    function getUrlAttribute() {
        $prefix   = [];
        $prefix[] = '!';
        $prefix[] = 'feedback-files';
        $prefix[] = mb_substr($this->id, -1);
        $prefix[] = mb_substr($this->id, -2, 1);
        $prefix[] = $this->id;
        $link     = '/' . implode('/', $prefix) . '/';
        
        return $link . $this->makeName();
    }
    
    function getFullUrlAttribute() {
        return url($this->url);
    }
    
    function makeName() {
        return hashids_encode($this->id) . '.' . $this->ext;
    }
}

FeedbackFile::deleting(function ($model) {
    $path = public_path($model->url);
    if(file_exists($path)) {
        unlink($path);
    }
});
<?php

namespace Larakit\LkNg;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {
    
    protected $table = 'feedbacks';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'comment',
        'ip',
        'state',
    ];
    protected $appends  = [
        'phone_ext',
    ];
    protected $with     = [
        'files',
    ];
    
    function files() {
        return $this->hasMany(FeedbackFile::class, 'feedback_id');
    }
    
    function getPhoneExtAttribute() {
        $phone = $this->denormalize($this->phone);
        
        return $phone ? $phone : $this->phone;
    }
    
    function denormalize($phone) {
        $phone = $this->normalize($phone);
        
        return '+7 (' . mb_substr($phone, 1, 3) . ') ' . mb_substr($phone, 4, 3) . '-' . mb_substr(
            $phone, 7, 2
        ) . '-' . mb_substr($phone, 9, 2);
    }
    
    function normalize($phone) {
        $p = preg_replace('/\D/', '', $phone);
        switch(true) {
            case 11 == mb_strlen($p):
                return $p;
                break;
            case (10 == mb_strlen($p) && 7 != mb_substr($p, 0, 1)):
                return '7' . $p;
                break;
            default:
                break;
        }
        
        return null;
    }
    
    static function freeFiles() {
        $ret          = [];
        $files        = \Larakit\LkNg\FeedbackFile::where('token', '=', csrf_token())->where('feedback_id', '=', 0)->get()->pluck('name', 'id');
        $ret['files'] = $files;
        $html         = [];
        foreach($files as $id => $name) {
            $html[] = '<div class="feedback-file">';
            $html[] = $name;
            $html[] = '&nbsp;';
            $html[] = '<i class="fa fa-times js-feedback-file-remove pointer" title="Удалить вложение" data-id="' . $id . '"></i>';
            $html[] = '</div>';
        }
        $ret['html'] = implode(PHP_EOL, $html);
        
        return $ret;
    }
    
}

Feedback::creating(function ($model) {
    $phone = $model->normalize($model);
    if($phone) {
        $model->phone = $phone;
    }
    
});

Feedback::created(function ($model) {
    $send   = [];
    $send[] = 'ИМЯ: ' . $model->name;
    $send[] = 'ТЕЛЕФОН: ' . $model->phone_ext;
    $send[] = 'IP: ' . $model->ip;
    $send[] = 'КОММЕНТАРИЙ: ' . $model->comment;
    \Larakit\TelegramBot::add('#обратная_связь');
    \Larakit\TelegramBot::add(implode(PHP_EOL, $send));
    \Larakit\TelegramBot::send(env('FEEDBACK_TELEGRAM'));
    
    $email = env('FEEDBACK_EMAIL');
    if($email) {
        \Mail::send('!.mails.order', ['data' => $send], function (\Illuminate\Mail\Message $message) use ($model) {
            $message->to($model->getEmail());
            $message->subject(ENV('FEEDBACK_TITLE') . ': обратная связь');
        });
    }
});

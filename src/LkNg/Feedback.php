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
    
    protected $appends = [
        'phone_ext',
    ];
    
    function getPhoneExtAttribute() {
        return $this->denormalize($this->phone);
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
    
    function getTags() {
        $tags   = [];
        $tags[] = '#обратная_связь';
        
        return implode(' ', $tags);
    }
    
}

Feedback::created(function ($model) {
    $send   = [];
    $send[] = 'ИМЯ: ' . $model->name;
    $send[] = 'ТЕЛЕФОН: ' . HelperPhone::denormalize($model->phone);
    $send[] = 'IP: ' . $model->ip;
    $send[] = 'КОММЕНТАРИЙ: ' . $model->comment;
    \Larakit\TelegramBot::add($model->getTags());
    \Larakit\TelegramBot::add(implode(PHP_EOL, $send));
    \Larakit\TelegramBot::send($model->getTelergam());
    
    $email = env('TO_EMAIL');
    if($email) {
        \Mail::send('!.mails.order', ['data' => $send], function (\Illuminate\Mail\Message $message) use ($model) {
            $message->to($model->getEmail());
            $message->subject('ТЕРЕМ: обратная связь');
        });
    }
});

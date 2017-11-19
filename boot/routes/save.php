<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 13.06.17
 * Time: 17:29
 */
Route::any('!/ajax/admin-feedback/save', function () {
    $id    = (int) \Request::get('id');
    $state = (int) \Request::get('state');
    if(!in_array($state, [-1, 1, 2, 3])) {
        $state = 0;
    }
    $model = \Larakit\LkNg\Feedback::findOrFail($id);
    if($model) {
        if(-1 == $state) {
            $model->delete();
        } else {
            $model->state = $state;
            $model->save();
        }
        $message = 'Статус обращения изменен!';
    }
    
    return [
        'result'  => 'success',
        'model'   => $model,
        'message' => $message,
    ];
});
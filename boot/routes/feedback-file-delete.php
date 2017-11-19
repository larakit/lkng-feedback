<?php
Route::post(
    '/!/ajax/feedback/file-delete',
    function () {
        $id            = Request::input('id');
        $model         = \Larakit\LkNg\FeedbackFile::find($id);
        $ret           = [];
        $ret['result'] = 'error';
        if($model && !$model->feedback_id && $model->token == csrf_token()) {
            $model->delete();
            $ret['result'] = 'success';
        }
        
        return array_merge($ret, \Larakit\LkNg\Feedback::freeFiles());
    }
)->name('feedback-file-delete')->middleware('web');
<?php
Route::post(
    '/!/feedback/send',
    function () {
        $data   = Request::all();
        $errors = \Larakit\LkNg\FeedbackValidator::instance()
            ->validate($data);
        if($errors) {
            return [
                'result'  => 'error',
                'message' => implode('<br>', $errors),
                'errors'  => $errors,
            ];
        }
        $model = \Larakit\LkNg\Feedback::create([
            'name'    => (string) Request::input('name'),
            'email'   => (string) Request::input('email'),
            'comment' => (string) Request::input('comment'),
            'phone'   => (string) Request::input('phone'),
            'ip'      => Request::ip(),
        ]);
        
        //прикрепим файло
        $files = \App\Models\FeedbackFile::where('token', '=', csrf_token())->where('feedback_id', '=', 0)->get();
        foreach($files as $file) {
            $file->feedback_id = $model->id;
            $file->save();
        }
        
        $ret = [
            'result' => 'success',
        ];
        
        return $ret;
        
    }
)->name('feedback-send');
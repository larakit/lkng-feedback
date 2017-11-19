<?php
Route::post(
    '/!/ajax/feedback/file',
    function () {
        $file          = Request::file('file');
        $ret['result'] = 'success';
        
        if(!in_array($file->extension(), [
            ".phtml",
            ".php",
            ".php5",
            ".html",
            ".htm",
            ".pl",
            ".xml",
            ".inc",
        ])
        ) {
            $model = \Larakit\LkNg\FeedbackFile::create([
                'name'        => $file->getClientOriginalName(),
                'token'       => csrf_token(),
                'ext'         => $file->extension(),
                'size'        => $file->getSize(),
                'feedback_id' => 0,
            ]);
            $path  = $model->url;
            $dir   = dirname(public_path(trim($path, '/')));
            $dir   = str_replace('\\', '/', $dir);
            if(!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $ret = [];
            if($file->move($dir, str_replace($dir . '/', '', $path))) {
                $ret['result'] = 'success';
            } else {
                $ret['result'] = 'error';
            }
        }
        return array_merge($ret, \Larakit\LkNg\Feedback::freeFiles());
    }
)->name('feedback-file')->middleware('web');
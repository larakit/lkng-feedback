<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 13.06.17
 * Time: 17:10
 */

Route::any('!/ajax/admin-feedback/load', function () {
    return \Larakit\LkNg\FeedbackFormFilter::load();
});


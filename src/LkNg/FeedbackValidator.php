<?php

/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 13.06.17
 * Time: 17:30
 */
namespace Larakit\LkNg;

use Larakit\ValidateBuilder;

class FeedbackValidator extends ValidateBuilder {
    
    function build() {
//        $this->to('cnt')
//            ->ruleNumeric('Не число')
//            ->ruleMin(1, 'Не указали число гостей');
        // $this->to('comment')
        //     ->ruleRequired('Вы забыли сообщение');
        $this->to('name')
            ->ruleRequired('Вы не представились');
        $this->to('phone')
            ->ruleRequired('Вы не указали телефон');
    }
    
}
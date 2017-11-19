<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 17.05.17
 * Time: 13:46
 */

namespace Larakit\LkNg;

use Larakit\FormFilter\FilterIn;
use Larakit\FormFilter\FilterLike;
use Larakit\FormFilter\Sorter;

class FeedbackFormFilter extends \Larakit\FormFilter\FormFilter {
    
    protected $per_page = 20;
    
    function init() {
        $this->addFilter(
            FilterIn::factory('state')
                ->label('Статус')
                ->isVertical(true)
                ->setTypeCheckbox()
                ->options([
                    [
                        'id'       => -1,
                        'toString' => '<span class="label label-default">в мусорке</span>',
                    ],
                    [
                        'id'       => 0,
                        'toString' => '<span class="label label-info">новый заказ</span>',
                    ],
                    [
                        'id'       => 1,
                        'toString' => '<span class="label label-success">благодарность</span>',
                    ],
                    [
                        'id'       => 2,
                        'toString' => '<span class="label label-danger">жалоба</span>',
                    ],
                ])
        );
        $this->addFilter(
            FilterLike::factory('name')
                ->label('Имя заказчика')
        );
        $this->addFilter(
            FilterLike::factory('phone')
                ->label('Телефон заказчика')
        );
        
        $this->addSorter(
            Sorter::factory('id')->label('ID'), true, true
        );
        $this->addSorter(
            Sorter::factory('state')->label('Статус')
        );
        
    }
}
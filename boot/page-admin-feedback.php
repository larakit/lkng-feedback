<?php
$url = \Larakit\LkNgRoute::adminUrl('feedbacks');

//##################################################
//      Регистрация компонента страницы
//##################################################
$components_directory = '/packages/larakit/lkng-feedback/components/';
\Larakit\LkNgComponent::register('page-admin-feedback', $components_directory);

\Larakit\Event\Event::listener('lkng::init', function () use ($url) {
    if(me('is_admin')) {
        $cnt   = \Larakit\LkNg\Feedback::where('state', '=', 0)->count();
        $title = 'Управление заявками';
        $title = 'Обратная связь';
        $icon  = 'fa fa-question-circle';
        //##################################################
        //      Добавление в sidebar администратора
        //##################################################
        $r = \Larakit\LkNgSidebar::section('admin', 'CRM')
            ->item('feedback', $title, $icon, $url);
        
        if($cnt) {
            $r->addLabel('feedback', '+' . $cnt, 'info');
        }
        
        //##################################################
        //      Добавление в Angular - routing
        //##################################################
        \Larakit\LkNgRoute::factory($url, 'admin-feedback')
            ->title($title)
            ->subtitle('Работа с жалобами и благодарностями')
            ->icon($icon);
    }
});



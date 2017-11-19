<?php
\Larakit\Boot::register_boot(__DIR__ . '/boot');

\Larakit\LkNgModule::register('lkng-feedback');
\Larakit\StaticFiles\Manager::package('larakit/lkng-feedback')
    ->usePackage('larakit/ng-adminlte')
    ->setSourceDir('public')
    ->jsPackage('page-admin-feedback/component.js');
<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 26.06.17
 * Time: 14:29
 */
\Larakit\Boot::register_boot(__DIR__ . '/boot');

\Larakit\StaticFiles\Manager::package('larakit/lkng-snippet')
    ->usePackage('larakit/ng-adminlte')
    ->setSourceDir('public')
    ->jsPackage('page-admin-snippet/component.js');
\Larakit\Twig::register_function('snippet', function ($context, $code) {
    return \Larakit\LkNg\LkNgSnippet::get($code, $context);
});
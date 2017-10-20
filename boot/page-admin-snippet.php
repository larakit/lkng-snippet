<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 29.05.17
 * Time: 17:52
 */

$url = \Larakit\LkNgRoute::adminUrl('snippets');

//##################################################
//      Регистрация компонента страницы
//##################################################
$dir = '/packages/larakit/lkng-snippet/';
\Larakit\LkNgComponent::register('page-admin-snippet', $dir);
\Larakit\LkNgComponent::register('modal-form-snippet', $dir);

\Larakit\Event\Event::listener('lkng::init', function () use ($url) {
    if(me('is_admin')) {
        $title = 'Управление текстами';
        $icon  = 'fa fa-language';
        //##################################################
        //      Добавление в sidebar администратора
        //##################################################
        $items = \Larakit\LkNg\LkNgSnippet::all();
        foreach(\Illuminate\Support\Arr::get($items, 'items') as $context => $_items) {
            $key = md5($context);
            \Larakit\LkNgSidebar::section('admin', $title)
                ->item('snippet' . $key, $context, $icon, $url . '-' . $key);
        }
        
        //##################################################
        //      Добавление в Angular - routing
        //##################################################
        \Larakit\LkNgRoute::factory($url . '-:key', 'admin-snippet')
            ->title($title)
            ->subtitle('Управление кусочками текста')
            ->icon($icon);
    }
});
Route::get('/!/lkng-snippet/load', function () {
    $items = \Larakit\LkNg\LkNgSnippet::all();
    $ret   = [];
    foreach(\Illuminate\Support\Arr::get($items, 'items') as $context => $_items) {
        $key       = md5($context);
        $ret[$key] = [
            'context' => $context,
            'items'   => $_items,
        ];
    }
    
    return $ret;
});
Route::post('/!/lkng-snippet/save', function () {
    $items = (array) Request::input('items');
    
    $data  = [];
    foreach($items as $group) {
        $context = \Illuminate\Support\Arr::get($group, 'context');
        $_items   = (array) \Illuminate\Support\Arr::get($group, 'items');
        foreach($_items as $code => $val) {
            $langs = (array) \Illuminate\Support\Arr::get($val, 'langs');
            foreach($langs as $locale => $translate) {
                $data[$locale][$context][$code] = $translate;
            }
        }
    }
    foreach($data as $locale => $val) {
        $file = resource_path('lang/' . $locale . '/lkng-snippets.php');
        $dir  = dirname($file);
        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($file, '<?php' . PHP_EOL . 'return ' . var_export($val, true) . ';');
    }
    
    return [
        'result'  => 'success',
        'message' => 'Переводы успешно обновлены',
    ];
});
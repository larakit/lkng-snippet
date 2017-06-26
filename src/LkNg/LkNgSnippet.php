<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 26.06.17
 * Time: 14:29
 */

namespace Larakit\LkNg;

use Illuminate\Support\Arr;

class LkNgSnippet {
    
    protected static $snippets = [];
    protected static $values   = null;
    protected        $context  = null;
    
    static function to($context) {
        return new LkNgSnippet($context);
    }
    
    function __construct($context) {
        $this->context = $context;
    }
    
    /**
     * Регистрация сниппета
     *
     * @param $code
     * @param $context
     * @param $default
     */
    function register($code, $default) {
        static::$snippets[$this->context][$code]['default'] = $default;
        static::$snippets[$this->context][$code]['langs']   = [];
        return $this;
    }
    
    /**
     * Получение сниппета на нужном нам языке
     *
     * @param      $code
     * @param      $context
     * @param null $lang
     *
     * @return array|null|string
     */
    static function get($code, $context, $lang = null) {
        $key     = $context . '.' . $code;
        $default = Arr::get(self::$snippets, $key . '.default');
        if(\Lang::has('lkng-snippets.' . $key, $lang, false)) {
            $translate = \Lang::get('lkng-snippets.' . $key, [], $lang, false);
            
            return $translate ? : $default;
        } else {
            return $default;
        }
    }
    
    static function all() {
        $locales = config('app.locales');
        if(!$locales) {
            $locales = [config('app.locale') => config('app.locale')];
        }
        foreach(self::$snippets as $context => $data) {
            foreach($locales as $locale) {
                foreach($data as $code => $snippet) {
                    static::$snippets[$context][$code]['langs'][$locale] = self::get($code, $context, $locale);
                }
                
            }
        }
        
        return [
            'contents' => array_keys(self::$snippets),
            'items'    => self::$snippets,
        ];
    }
}
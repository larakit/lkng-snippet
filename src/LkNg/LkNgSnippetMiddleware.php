<?php
/**
 * Created by Larakit.
 * Link: http://github.com/larakit
 * User: Alexey Berdnikov
 * Date: 12.05.17
 * Time: 9:20
 */

namespace Larakit\LkNg;

class LkNgSnippetMiddleware {
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next) {
        $file = base_path('bootstrap/snippets.php');
        if(file_exists($file)){
            include_once $file;
        }
        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use App\Models\Announcement;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class ManagerAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('manager/login');
            }
        }

        $menu = $this->getMenu();
        $request->attributes->set('menu', $menu);

        //权限过滤
        $previousUrl = URL::previous();
        $routeName = starts_with(Route::currentRouteName(), 'manager.') ? Route::currentRouteName() : 'manager.' . Route::currentRouteName();
        if (!Gate::forUser(auth('manager')->user())->check($routeName)) {
            if ($request->ajax() && ($request->getMethod() != 'GET')) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'status' => -1,
                        'code'   => 403,
                        'description'    => '您没有权限执行此操作',
                    ]
                ]);
            } else {
                return response()->redirectToRoute($menu[$menu['top'][0]['id']][0]['name']);
            }
        }
        return $next($request);
    }

    /**
     * 获取左边菜单栏
     * @return array
     */
    function getMenu()
    {
        $openArr = [];
        $data = [];
        $data['top'] = [];
        //查找并拼接出地址的别名值
        $path_arr = explode('/', URL::getRequest()->path());
        if (isset($path_arr[1])) {
            $urlPath = $path_arr[0] . '.' . $path_arr[1];
        } else {
            $urlPath = $path_arr[0] . '.index';
        }
        //查找出所有的地址
        $table = Cache::store('file')->rememberForever('menus', function () {
            return \App\Models\Permission::where('name', 'LIKE', '%index')
                ->orWhere('cid', 0)
                ->orderBy('sort', 'desc')
                ->get();
        });
        //$tests = Gate::forUser(auth('manager')->user())->check('manager.permission');
        foreach ($table as $v) {
            if ($v->cid == 0 || Gate::forUser(auth('manager')->user())->check($v->name)) {
                if ($v->name == $urlPath) {
                    $openArr[] = $v->id;
                    $openArr[] = $v->cid;
                }
                $data[$v->cid][] = $v->toarray();
            }
        }
        foreach ($data[0] as $v) {
            if (isset($data[$v['id']]) && is_array($data[$v['id']]) && count($data[$v['id']]) > 0) {
                $data['top'][] = $v;
            }
        }
        unset($data[0]);
        //ation open 可以在函数中计算给他
        $data['openarr'] = array_unique($openArr);
        return $data;

    }
}

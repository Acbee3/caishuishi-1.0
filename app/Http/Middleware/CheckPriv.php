<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Priv;

class CheckPriv
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // 执行存储 会员权限 session 数据...
        Priv::CFS_get_Priv_session();

        // 生成或更新各级菜单 session 数据 (正常情况下为调用后台更新的菜单session数据)

        return $response;
    }

    public function terminate($request, $response)
    {

    }
}

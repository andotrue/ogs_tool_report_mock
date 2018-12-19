<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      # 商用環境以外だった場合、SQLログを出力する
      if (config('app.env') !== 'production') {
        \DB::listen(function ($query) {
          $sql = $query->sql;
          for ($i = 0; $i < count($query->bindings); $i++) {
            $sql = preg_replace("/\?/", $query->bindings[$i], $sql, 1);
          }
          \Log::info("Query Time:{$query->time}s] $sql");
        });
      }

      Validator::extend('kana', function($attribute, $value, $parameters, $validator) {
        // 半角空白、全角空白、全角記号、全角かなを許可
        return preg_match("/^[ぁ-んー 　！-＠［-｀｛-～]+$/u", $value);
      });
      Validator::extend('alpha_custom', function($attribute, $value, $parameters, $validator) {
        return preg_match('/^\pL+$/', $value);
        //return preg_match('/^[\pL\pM]+$/', $value);
      });
      Validator::extend('alpha_num_custom', function($attribute, $value, $parameters, $validator) {
        //return preg_match('/^\pL+$/', $value);
        return preg_match('/^[\pL\pN]+$/', $value);
      });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

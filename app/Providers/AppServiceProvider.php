<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // 自分以外からの未読メッセージ数を集計
                $unreadCount = Transaction::where(function ($query) use ($user) {
                        $query->where('buyer_id', $user->id)
                            ->orWhere('seller_id', $user->id);
                    })
                    ->whereHas('messages', function ($q) use ($user) {
                        $q->where('is_read', false)
                            ->where('user_id', '!=', $user->id);
                    })
                    ->with(['messages' => function ($q) use ($user) {
                        $q->where('is_read', false)->where('user_id', '!=', $user->id);
                    }])
                    ->get()
                    ->pluck('messages')
                    ->flatten()
                    ->count();

                $view->with('globalUnreadCount', $unreadCount);
            }
        });
    }
}

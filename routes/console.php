<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

Artisan::command('inspire', function () {
    if (Auth::check() && Auth::user()->is_admin) {
        $this->comment(Inspiring::quote());
    } else {
        $this->comment('Bu komutu çalıştırmaya yetkiniz yok.');
    }
})->purpose('Display an inspiring quote');

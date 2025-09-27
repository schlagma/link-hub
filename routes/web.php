<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Public\Dashboard::class)->name('public.dashboard');
Route::get('/p/{id}', \App\Livewire\Public\LinkPage::class)->name('public.link-page');

Route::group(['middleware'=>['auth']], function() {
    Route::get('/admin', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/page/new', \App\Livewire\Admin\LinkPageCreate::class)->name('admin.link-page-create')->can('admin');
    Route::get('/admin/page/{id}/edit', \App\Livewire\Admin\LinkPageEdit::class)->name('admin.link-page-edit');
    Route::get('/admin/groups', \App\Livewire\Admin\Groups::class)->name('admin.groups')->can('admin');
});

Route::get('/imprint', function() {
    return redirect(config('app.imprint_url'));
})->name('imprint');

Route::get('/privacy', function() {
    return redirect(config('app.privacy_url'));
})->name('privacy');

Route::get('/source-code', function() {
    return redirect(config('app.source_code_url'));
})->name('source-code');

require __DIR__.'/auth.php';

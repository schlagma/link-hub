<?php

use App\Livewire\Admin\Groups;
use App\Livewire\Admin\LinkPageCreate;
use App\Livewire\Admin\LinkPageEdit;
use App\Livewire\Public\Dashboard;
use App\Livewire\Public\LinkPage;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('public.dashboard');
Route::get('/p/{id}', LinkPage::class)->name('public.link-page');

Route::group(['middleware' => ['auth']], function (): void {
    Route::get('/admin', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/page/new', LinkPageCreate::class)->name('admin.link-page-create')->can('admin');
    Route::get('/admin/page/{id}/edit', LinkPageEdit::class)->name('admin.link-page-edit');
    Route::get('/admin/groups', Groups::class)->name('admin.groups')->can('admin');
});

Route::get('/imprint', function () {
    return redirect(config('app.imprint_url'));
})->name('imprint');

Route::get('/privacy', function () {
    return redirect(config('app.privacy_url'));
})->name('privacy');

Route::get('/source-code', function () {
    return redirect(config('app.source_code_url'));
})->name('source-code');

require __DIR__.'/auth.php';

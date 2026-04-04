<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof FileNotFoundException) {
            // ✅ Route exist karta hai to redirect karo
            if (\Illuminate\Support\Facades\Route::has('home')) {
                return redirect('/');
            }
            return redirect('/');
        }
        return parent::render($request, $e);
    }
}
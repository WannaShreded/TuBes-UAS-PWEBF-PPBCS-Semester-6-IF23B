<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register()
    {
        // Keep parent registration
        parent::register();
    }

    public function report(Throwable $e)
    {
        try {
            if (app()->runningUnitTests()) {
                // Log full exception string (includes stack trace)
                \Illuminate\Support\Facades\Log::error((string) $e);
            }
        } catch (Throwable $ex) {
            // avoid interfering with test flow
        }

        parent::report($e);
    }
}

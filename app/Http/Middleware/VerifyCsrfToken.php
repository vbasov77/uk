<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'q_result',
        'q_success',
        'upload_file',
        'add_room',
        'delete_file',
        '/upload_img',
        '/delete_file',
        'get_cost',
    ];
}

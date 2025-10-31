<?php

namespace App\Http\Controllers;

// VOEG DEZE TWEE IMPORT-REGELS TOE
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // EN VOEG DEZE REGEL TOE
    use AuthorizesRequests, ValidatesRequests;
}

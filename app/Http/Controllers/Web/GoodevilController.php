<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web\BaseController;
use Illuminate\Http\Request;
use DB;


class GoodevilController extends BaseController
{
    private $typeGood = 1;
    private $typeEvil = 2;

    public function getGood()
    {
        $articles = DB::table('articles');


    }

    public function getEvil()
    {


    }

    


}

?>
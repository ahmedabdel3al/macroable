<?php
/**
 * Created by PhpStorm.
 * User: Code95
 * Date: 4/18/2019
 * Time: 2:28 PM
 */

namespace App\Http;


use App\Support\Macroable;

class Request
{
    use Macroable;
    protected $method = ['GET'];
}
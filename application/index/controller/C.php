<?php

namespace app\index\controller;

class C
{
    public function index($name = 'World')
    {
        return 'Hello,' . $name . '！';
    }
}
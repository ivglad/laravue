<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    public int $perPage = 15;

    public function __construct(Request $request)
    {
        $request->validate(
            ['per_page' => ['nullable', 'integer']],
            ['per_page' => 'Поле кол-во элементов на страницу должно быть целым числом.']
        );
        if (!blank($request->per_page)) {
            $this->perPage = $request->per_page;
        }
    }
}

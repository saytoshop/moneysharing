<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $params = ['groups' => []];

        if (auth()->user()) {
            $groups = auth()->user()->groups()->get();
            foreach ($groups as &$group) {

                if ($group->isTokenExpired()) {
                    $group->token = null;
                    $group->token_expired = null;
                }
                $params['groups'] = $groups;
            }
        }
        return view('home.index', $params);
    }
}

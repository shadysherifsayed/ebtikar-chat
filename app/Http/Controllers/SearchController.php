<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{


    public function user(Request $request)
    {

        if (!$request->filled('term')) {
            return [];
        }

        $term = "%{$request->term}%";

        $user = User::where(function ($query) use ($term) {
            $query->orWhere('email', 'like', $term)
                ->orWhere('name', 'like', $term);
            })->where('id', '<>', auth()->id())->get();

        return $user;
    }
}

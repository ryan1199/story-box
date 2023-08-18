<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $new_novels = Novel::with(['tags', 'categories', 'chapters', 'user', 'image'])->orderBy('created_at', 'desc')->paginate(
            $perPage = 10, $columns = ['*'], $pageName = 'new_novels'
        );
        $update_novels = Novel::with(['tags', 'categories', 'chapters', 'user', 'image'])->orderBy('updated_at', 'desc')->paginate(
            $perPage = 10, $columns = ['*'], $pageName = 'update_novels'
        );
        return view('home', [
            'new_novels' => $new_novels,
            'update_novels' => $update_novels
        ]);
    }   
}

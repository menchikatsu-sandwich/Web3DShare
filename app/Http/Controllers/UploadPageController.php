<?php

namespace App\Http\Controllers;

use App\Models\Category;

class UploadPageController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('upload.index', compact('categories'));
    }
}
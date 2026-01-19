<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        return view('documents.templates.index');
    }
    public function create()
    {
        return view('documents.templates.create');
    }
}

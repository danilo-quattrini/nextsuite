<?php

namespace App\Http\Controllers;

use App\Models\Template;
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

    public function layout(Template $template)
    {
        return view('documents.templates.layout', compact('template'));
    }
}

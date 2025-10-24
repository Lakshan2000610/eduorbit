<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.teachers.index', compact('teachers'));
    }
}

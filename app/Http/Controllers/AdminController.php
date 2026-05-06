<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Model3D;
use App\Models\Category;
use App\Models\Report;
use App\Models\VerificationRequest;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'models' => Model3D::count(),
            'users'  => User::count(),
            'reports'=> Report::count(),
            'verify' => VerificationRequest::count()
        ];

        return view('admin.panel', [
            'stats'    => $stats,
            'models'   => Model3D::with('user')->latest()->get(),
            'users'    => User::all(),
            'reports'  => Report::with('model')->latest()->get(),
            'requests' => VerificationRequest::with('user')->latest()->get(),
            'categories'=> Category::all()
        ]);
    }

    public function deleteModel($id)
    {
        Model3D::findOrFail($id)->delete();
        return back();
    }

    public function promote($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'moderator';
        $user->save();

        return back();
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // delete semua model user
        Model3D::where('user_id', $user->id)->delete();

        $user->delete();

        return back();
    }

    // CATEGORY
    public function storeCategory()
    {
        request()->validate(['name' => 'required']);

        Category::create([
            'name'       => request('name'),
            'created_by' => Auth::id(), // ← fix: ambil id user yang login
        ]);

        return back();
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        return back();
    }
}
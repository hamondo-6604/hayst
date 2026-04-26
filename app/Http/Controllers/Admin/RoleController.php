<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use App\Models\DiscountType;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $userTypes = UserType::withCount('users')->get();
        $discountTypes = DiscountType::withCount('users')->get();
        
        return view('admin.roles.index', compact('userTypes', 'discountTypes'));
    }
}

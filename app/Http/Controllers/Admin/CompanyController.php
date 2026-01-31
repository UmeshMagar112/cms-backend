<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
  

    // Get all companies
    public function index()
    {
        $companies = User::whereHas('roles', function ($q) {
            $q->where('name', 'company');
        })->get(['id', 'name', 'email', 'phone', 'is_verified']);

        return response()->json($companies);
    }

    // Get only pending companies
    public function pending()
    {
        $pending = User::whereHas('roles', function ($q) {
            $q->where('name', 'company');
        })->where('is_verified', false)
          ->get(['id', 'name', 'email', 'phone', 'is_verified']);

        return response()->json($pending);
    }

    // Approve company
    public function approve($id)
    {
        $company = User::where('id', $id)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'company');
            })->first();

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $company->is_verified = true;
        $company->save();

        return response()->json([
            'message' => 'Company approved successfully',
            'company' => $company
        ]);
    }
}

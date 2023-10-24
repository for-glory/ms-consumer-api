<?php declare(strict_types=1);

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::search()->simplePaginate(
            $request->input('perPage'),
            $request->input('pageName'),
            $request->input('page')
        );

        return response()->json($users);
    }
}
<?php declare(strict_types=1);

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::search()
            ->options([
                'body' => [
                    'sort' => [
                        '_script' => [
                            'type' => 'number',
                            'script' => 'return Integer.parseInt(doc[\'id\'].value)',
                            'order' => 'asc',
                        ],
                    ],
                ],
            ])
            ->simplePaginate(
                $request->input('perPage'),
                $request->input('pageName', 'page'),
                $request->input('page')
            );

        return response()->json($users);
    }
}
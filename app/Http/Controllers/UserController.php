<?php declare(strict_types=1);

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::search()
            ->when($request->has('name'), function ($query) use ($request) {
                $name = $request->input('name');

                return $query->where('multi_match', [
                    'query' => $name,
                    'fields' => [
                        'name',
                        'email',
                    ],
                    'fuzziness' => '7',
                    'auto_generate_synonyms_phrase_query' => true,
                ]);
            })
            ->simplePaginate(
                $request->input('perPage'),
                $request->input('pageName', 'page'),
                $request->input('page')
            );

        return response()->json($users);
    }
}
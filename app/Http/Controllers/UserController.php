<?php declare(strict_types=1);

namespace App\Http\Controllers;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::search()
            ->when($request->get('filter'), function ($query) use ($request) {
                $filter = $request->input('filter');

                return $query->where('multi_match', [
                    'query' => $filter,
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

    public function store(StoreUserRequest $request): JsonResponse
    {
        User::create($request->validated());

        return response()->json([], Response::HTTP_CREATED);
    }
}
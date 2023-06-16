<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDTO;
use Core\UseCase\DTO\Category\CategoryUpdateInputDTO;
use Core\UseCase\DTO\Genre\GenreCreateInputDTO;
use Core\UseCase\DTO\Genre\GenreInputDTO;
use Core\UseCase\DTO\Genre\GenreUpdateInputDTO;
use Core\UseCase\DTO\Genre\ListGenreInputDTO;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    public function index(Request $request, ListGenresUseCase $usecase)
    {
        $response = $usecase->execute(
            input: new ListGenreInputDTO(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15)
            )
        );

        return GenreResource::collection(collect($response->items))
                                    ->additional([
                                        'meta' => [
                                            'total' => $response->total,
                                            'current_page' => $response->current_page,
                                            'last_page' => $response->last_page,
                                            'first_page' => $response->first_page,
                                            'per_page' => $response->per_page,
                                            'to' => $response->to,
                                            'from' => $response->from,
                                        ]
                                    ]);

    }

    public function store(StoreGenreRequest $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new GenreCreateInputDTO(
                name: $request->name,
                isActive: (bool) $request->is_active ?? true,
                categoriesId: $request->categories_ids
            )
        );

        return (new GenreResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListGenreUseCase $useCase, $id)
    {
        $category = $useCase->execute(new GenreInputDTO($id));

        return (new GenreResource($category))->response();
    }

    public function update(UpdateGenreRequest $request, UpdateGenreUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new GenreUpdateInputDTO(
                id: $id,
                name: $request->name,
                categoriesId: $request->categories_ids
            )
        );

        return (new GenreResource($response))
            ->response();
    }

    public function destroy(DeleteGenreUseCase $useCase, $id)
    {
        $useCase->execute(new GenreInputDTO($id));

        return response()->noContent();
    }
}

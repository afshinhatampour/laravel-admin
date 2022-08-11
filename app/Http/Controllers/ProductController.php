<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Uploader;
use Database\Seeders\ProductSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::query()->paginate());
    }

    /**
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function store(ProductCreateRequest $request)
    {
        $product = Product::create($request->only('title', 'description', 'image', 'price'));
        return response()->json($product, Response::HTTP_CREATED);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only('title', 'description', 'image', 'price'));
        return response()->json($product, Response::HTTP_ACCEPTED);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Api\Product;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Class ProductController handles API endpoints related to products.
 *
 * @package App\Http\Controllers\Api
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection A collection of products in the form of anonymous resources.
     */
    public function index(): AnonymousResourceCollection
    {
        $search = request('search', false);
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Product::query();
        $query->orderBy($sortField, $sortDirection);
        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        return ProductListResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request The incoming product creation request.
     * @return Response|ProductResource A response containing the newly created product resource.
     * @throws Exception
     */
    public function store(ProductRequest $request): Response|ProductResource
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        /** @var UploadedFile $image */
        $image = $data['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
        }

        $product = Product::create($data);

        return new ProductResource($product);
    }

    /**
     * Save image to a file system.
     *
     * @param UploadedFile $image The uploaded image file.
     * @return string The relative path to the saved image.
     * @throws Exception
     */
    private function saveImage(UploadedFile $image): string
    {
        $path = 'images/' . Str::random();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product The specific product to display.
     * @return ProductResource The resource representation of the specific product.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request The incoming product update request.
     * @param Product $product The product to be updated.
     * @return ProductResource The updated resource representation of the product.
     * @throws Exception
     */
    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        /** @var UploadedFile $image */
        $image = $data['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();

            // If there is an old image, delete it
            if ($product->image) {
                Storage::deleteDirectory('/public' . dirname($product->image));
            }
        }

        $product->update($data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product The product to be deleted.
     * @return Response A response indicating the successful deletion of the product.
     */
    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}

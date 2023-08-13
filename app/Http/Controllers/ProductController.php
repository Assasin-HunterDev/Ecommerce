<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

/**
 * Class ProductController handles actions related to products.
 *
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $products = Product::query()->paginate(5);
        return view('product.index', [
            'products' => $products
        ]);
    }

    /**
     * Display a specific product.
     *
     * @param Product $product Display a specific product.
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function view(Product $product): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('product.view', ['product' => $product]);
    }
}

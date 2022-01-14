<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Product:: Get list of products
     *
     * @return Response
     */
    public function getAllProducts() {
        $products = Product::select('id', 'name', 'description', 'image')->orderBy('created_at', 'desc')->paginate(10);

        return $products;
    }

    /**
     * Product:: store new product
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        if ($request->image) {
            $file = time() . '.' . $request->image->extension();

            $img = \Intervention\Image\Facades\Image::make($request->image->path());
            $img->resize(240, 240, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->stream();

            Storage::disk('local')->put('public/'. Carbon::now()->format('d-m-Y') . '/' . $file, $img, 'public');
        }
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['SERVER_NAME']. '/storage/'. Carbon::now()->format('d-m-Y') . '/' . $file,
        ]);

        return response()->json('success');
    }

    /**
     * Product:: get single product
     * @param $id
     *
     * @return Response
     */
    public function getProduct(Request $request) {
        $product = Product::find($request->id);

        return $product;
    }

    /**
     * Product:: update product
     * @param Request $request
     *
     * @return Response
     */
    public function update(Request $request) {
        $product = Product::find($request->id);
        if ($request->image != 'undefined') {
            $file = time() . '.' . $request->image->extension();

            $img = \Intervention\Image\Facades\Image::make($request->image->path());
            $img->resize(240, 240, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->stream();

            Storage::disk('local')->put('public/'. Carbon::now()->format('d-m-Y') . '/' . $file, $img, 'public');
            $product->image = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['SERVER_NAME']. '/storage/'. Carbon::now()->format('d-m-Y') . '/' . $file;
        }
        $product->name = $request->name;
        $product->description = $request->description;
        $product->save();

        return response()->json('success');
    }

    /**
     * Product:: delete product
     * @param $id
     *
     * @return Response
     */
    public function delete($id) {
        $product = Product::find($id);
        $product->delete();

        return response()->json('success');
    }
}

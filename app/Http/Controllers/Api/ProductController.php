<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\CssSelector\Parser\Reader;

class ProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var CategoryController
     */
    private $category;

    /**
     * ProductController constructor.
     * @param Product $product
     * @param CategoryController $category
     */
    public function __construct(Product $product, CategoryController $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->product->with('category')->orderBy('name', 'ASC')->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products|max:64',
            'category_id' => 'exists:categories,id',
            'price' => 'between:0,99.99',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            if ($request->get('file') !== 'undefined') {
                $image = $this->imageUpload($request->get('file'));
                $request->offsetSet('image', $image);
            }

            $product = $this->product->create($request->except('file'));
            return response()->json($product);

        }
    }

    public function show($id)
    {
        return $this->product->where('id', '=', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->get('file') !== 'undefined') {
            $image = $this->imageUpload($request->get('file'));
            $request->offsetSet('image', $image);
        }

        Product::where('id', $request->get('id'))
            ->update($request->except('file'));
        return response()->json(Product::where('id', $request->get('id'))->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);

        if ($product) {
            $product->delete();
            if ($product->image)
                unlink('images/product/' . $product->image);

            return response()->json([
                'success' => '200',
                'message' => 'Produto excluido com sucesso'
            ]);
        }
    }

    public function imageUpload()
    {
        request()->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.jpg';
        request()->file->move('images/product', $imageName);
        return $imageName;

    }

    public function productImport()
    {
        if (request()->image->getClientOriginalExtension() == 'csv') {
            $imageName = time() . '.csv';
            request()->image->move(storage_path('product/import'), $imageName);

            return response()->json([
                'error' => 'Arquivo importado com sucesso.'
            ]);

        } else {
            return response()->json([
                'error' => 'Erro ao importar lista de produtos'
            ]);
        }
    }

    public function verifyEdit(Request $request)
    {
        $verify = DB::table('products')
            ->where('name', '=', $request->get('name'))
            ->where('id', '<>', $request->get('id'))->get();
        return $verify ? response()->json($verify) : response()->json();
    }

    public function verifyCreate(Request $request)
    {
        $verify = DB::table('products')
            ->where('name', '=', $request->get('name'))->get();
        return $verify ? response()->json($verify) : response()->json();
    }
}

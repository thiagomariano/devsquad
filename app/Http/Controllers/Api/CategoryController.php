<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Product
     */
    private $product;

    /**
     * CategoryController constructor.
     * @param Category $category
     * @param Product $product
     */
    public function __construct(Category $category, Product $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('categories')->paginate();
        //return $this->category->query()->paginate();
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
            'name' => 'required|unique:categories',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            return $this->category->create($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->category->where('id', '=', $id)->first();
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

        $category = DB::table('categories')
            ->where('name', '=', $request->get('name'))
            ->where('id', '<>', $request->get('id'))->get();

        if (isset($category[0]->id)) {
            return response()->json(['error' => 'Nome da categoria já está em uso.']);
        } else {
            DB::table('categories')->where('id', '=', $request->get('id'))->update([
                'name' => $request->get('name')
            ]);
            return response()->json(['message' => 'Categoria alterada com sucesso']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $product = DB::table('products')->where('category_id', '=', $id)->first();

        if ($product === null) {

            $category = DB::table('categories')->where('id', '=', $id)->delete();

            if ($category == 1) {
                return response()->json([
                    'success' => 'Categoria excluida com sucesso.'
                ]);
            }
            return response()->json([
                'error' => 'Um erro inesperado ocorreu'
            ]);
        } else {
            return response()->json([
                'error' => 'Categoria contém registros filhos em produtos, não pode ser deletada'
            ]);
        }
    }
}

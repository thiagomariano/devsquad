<?php

namespace App\Models;

use App\Mail\MailImportProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'price',
        'image',
        'categoria'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function importCron()
    {
        $path = storage_path('product/import');
        $diretorio = dir($path);

        while ($arquivo = $diretorio->read()) {
            if (substr($arquivo, -3) == 'csv') {

                $meuArray = Array();
                $file = fopen(storage_path('product/import/' . $arquivo), 'r');
                while (($line = fgetcsv($file)) !== false) {
                    $meuArray[] = $line;
                }

                $enviados = [];
                for ($i = 1; $i < count($meuArray); $i++) {
                    $dados = explode(';', $meuArray[$i][0]);

                    //print_r($dados);
                    if (!DB::table('products')->where('name', $dados[0])->first()) {
                        $product = Product::create([
                            'name' => $dados[0],
                            'category_id' => $dados[1],
                            'price' => $dados[2],
                            'description' => $dados[3]
                        ]);
                        $enviados = $product;
                    }
                }

                $file = DB::table('import_products')->where('file', $arquivo)->first();

                if (!$file) {
                    ImportProduct::create([
                        'file' => $arquivo,
                        'user_id' => 1
                    ]);
                }

                Mail::to('newuser@example.com')->send(new MailImportProduct($enviados));


            }
        }
    }
}

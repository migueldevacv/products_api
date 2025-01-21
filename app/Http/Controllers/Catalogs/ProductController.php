<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Requests\Catalogs\ProductRequest;
use App\Http\Controllers\Controller;
use App\Providers\MessagesResponse;
use App\Models\Catalogs\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::join('categories as c', 'products.category_id', '=', 'c.id')
            ->select(['products.*', 'c.description as category'])
            ->get();
        return MessagesResponse::indexOk($product);
    }

    public function show(ProductRequest $req, $id)
    {
        $product = Product::find($id);
        return MessagesResponse::showOk($product);
    }

    public function store(ProductRequest $req)
    {
        $data = collect($req->validated())->toArray();
        $data['user_id'] = $req->user()->id;
        $product = Product::create($data);
        return MessagesResponse::createdOk('product', $product);
    }

    public function update(ProductRequest $req, $id)
    {
        $product = Product::find($id);
        $product->update($req->validated());
        return MessagesResponse::updatedOk('product', $product);
    }

    public function destroy(ProductRequest $req, $id)
    {
        $product = Product::find($id);
        $product->update(['status' => !$product->status]);
        return MessagesResponse::disabledOk('product', $product);
    }

    public function exportXsls(Request $req)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Price');
        $sheet->setCellValue('D1', 'Stock');

        $products = Product::where('user_id', $req->user()->user_id)
            ->get();

        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue("A{$row}", $product->id);
            $sheet->setCellValue("B{$row}", $product->name);
            $sheet->setCellValue("C{$row}", $product->price);
            $sheet->setCellValue("D{$row}", $product->stock);
            $row++;
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="products.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}

<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel; 

class Product extends ResourceController
{
    public function __construct() {
        $this->productModel = new ProductModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $products = $this->productModel->paginate(2, 'products');

        $payload = [
            "products" => $products,
            "pager" => $this->productModel->pager
        ];

        echo view('product/index', $payload);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        echo view('product/new');
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {

        $fileName = "";

        $photo = $this->request->getFile('photo');

        if ($photo) {
            $fileName = $photo->getRandomName(); // Mendapatkan nama file baru secara acak

            $photo->move('photos', $fileName); // Memindahkan file ke public/photos dengan nama acak
        }

        $payload = [
            "name" => $this->request->getPost('name'),
            "stock" => (int) $this->request->getPost('stock'),
            "price" => (int) $this->request->getPost('price'),
            "category" => $this->request->getPost('category'),
            "photo" => $fileName, // Kita simpan nama filenya saja
        ];

        $this->productModel->insert($payload);
        return redirect()->to('/product');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            throw new \Exception("Data not found!");   
        }
        
        echo view('product/edit', ["data" => $product]);
    }


    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $payload = [
            "name" => $this->request->getPost('name'),
            "stock" => (int) $this->request->getPost('stock'),
            "price" => (int) $this->request->getPost('price'),
            "category" => $this->request->getPost('category'),
        ];

        $this->productModel->update($id, $payload);
        return redirect()->to('/product');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->productModel->delete($id);
        return redirect()->to('/product');
    }
}
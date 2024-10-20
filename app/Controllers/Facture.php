<?php

namespace App\Controllers;

use App\Models\BillDetailsModel;
use App\Models\InventoriesModel;
use App\Models\PaymentmehodBillModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Facture extends ResourceController
{
    protected $modelName = 'App\Models\BillModel';
    protected $format    = 'json';

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {

        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');

        return $this->respond($this->model->findAll());
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');

        $data = $this->model->where('User_id', $id)->findAll();
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound('Factura no encontrada ' . $id);
    }


    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {

        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        $data = $this->request->getJSON(true);
        $referencia = rand(1000, 9999);

        $this->model->insert([
            'User_id' => $data['User_id'],
            'Date' => date('Y-m-d H:i:s'),
            'reference' => $referencia
        ]);
        $IdFactura = $this->model->insertID();

        $DetallesFacturaModel = new BillDetailsModel();

        $DetallesFacturaModel->insert([
            'Bill_Id' => $IdFactura,
            'Product_Id' => $data['Product_Id'],
            'Amount_product' => $data['Amount_product'],
            'Price_unitary' => $data['Price_unitary']
        ]);

        $MetodoPagoModel = new PaymentmehodBillModel();
        $MetodoPagoModel->insert([
            'Bill_id' => $IdFactura,
            'MethodPay_id' => $data['MethodPay_id'],
            'Total_amount' => $data['Total_amount']
        ]);

        $RegistroInventarioModel = new InventoriesModel();

        $cantidad = $RegistroInventarioModel->find($data['Product_Id'])->Amount_inventory;

        $RegistroInventarioModel->update(
            $data['Product_Id'],
            [
                'Amount_inventory' => $cantidad - $data['Amount_product']
            ]
        );

        return $this->respondCreated($data, 'Factura creada');
    }


    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'PUT, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'DELETE, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}

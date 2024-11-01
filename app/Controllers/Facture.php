<?php

namespace App\Controllers;

use App\Models\BillCkeckoutModel;
use App\Models\BillDetailsModel;
use App\Models\BillModel;
use App\Models\InventoriesModel;
use App\Models\PaymentmehodBillModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use PhpParser\Node\Stmt\Echo_;

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
            'reference' => $referencia,
            'Status' => 0
        ]);
        $idFactura = $this->model->insertID();

        $DetallesFacturaModel = new BillDetailsModel();
        foreach ($data['Products'] as $producto) {
            $DetallesFacturaModel->insert([
                'Bill_Id' => $idFactura,
                'Product_Id' => $producto['Product_id'],
                'Amount_product' => $producto['Amount_product'],
                'Price_unitary' => $producto['Price_unitary']
            ]);
        }

        $MetodoPagoModel = new PaymentmehodBillModel();
        $monto_total = 0;

        foreach ($data['Products'] as $producto) {
            $monto_total += $producto['Price_unitary'] * $producto['Amount_product'];
        }

        $MetodoPagoModel->insert([
            'Bill_id' => $idFactura,
            'Total_amount' => $monto_total,
        ]);


        $RegistroInventarioModel = new InventoriesModel();

       

        foreach ($data['Products'] as $producto) { 
            $cantidad = $RegistroInventarioModel->find($producto['Product_id'])->Amount_inventory;          
            $RegistroInventarioModel->update(
                $producto['Product_id'],
                [
                    'Amount_inventory' => $cantidad - $producto['Amount_product']
                ]
            );    
        }

        array_push($data['Products'], ['Total_amount' => $monto_total]);
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

    public function Capture($id = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        if (empty($_POST['IdFactura'])) {
            return $this->fail('Falta el campo Id');
        }
        if (empty($_POST['IdUsuario'])) {
            return $this->fail('Falta el campo Id');
        }

        $idFactura = $this->request->getPost('IdFactura');
        $idProducto = $this->request->getPost('IdUsuario');

        $file = $this->request->getFile('Capture');

        if (empty($file)) {
            return $this->fail('No se ha subido ningun archivo');
        }

        if (!$file->isValid()) {
            return $this->fail('No se ha podido subir el archivo');
        }



        if (!$file->hasMoved()) {
            $ruta = ROOTPATH . 'public/ImageCapture';
            $file->move($ruta, $file->getName(), true);
        }

        $url = 'public/ImageCapture/' . $file->getName();

        $data = [
            'Bill_id' => $idFactura,
            'User_id' => $idProducto,
            'Capture' => $url
        ];

        $captureMOdel = new BillCkeckoutModel();

        $captureMOdel->insert($data);

        return $this->respondCreated("Imagenes Subidas", 'Imagen subida');
    }

    public function VerifyFacture()
    {

        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        $data = $this->request->getJSON(true);
        $idFactura = $data['IdFactura'];
        $checkout = $data['checkout'];


        if (empty($idFactura) || empty($checkout)) {
            return $this->failValidationErrors('IdFactura y checkout son requeridos');
        }


        $facturaModel = new BillModel();

        if ($facturaModel->update($idFactura, ['Status' => $checkout])) {
            return $this->respondUpdated('Factura actualizada');
        } else {
            return $this->fail('Error al actualizar la factura');
        }
    }
}

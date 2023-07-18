<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KomoditiModel;
use CodeIgniter\API\ResponseTrait;

class Komoditi extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $komoditiModel = new KomoditiModel();
        $komoditi = $this->request->getVar("komoditi");
        $wilayah = $this->request->getVar("wilayah");
        $is_show = $this->request->getVar("is_show");

        try {
            if(!is_null($komoditi) and $komoditi != ""){
                $komoditiModel = $komoditiModel->where('ckd_komoditi', $komoditi);
            }
            if(!is_null($wilayah) and $wilayah != ""){
                $komoditiModel = $komoditiModel->where('ckd_wilayah', $wilayah);
            }
            if(!is_null($is_show) and $is_show != ""){
                $komoditiModel = $komoditiModel->where('is_show', $is_show);
            }
            $data = $komoditiModel->findAll();
            return $this->respond($data, 200);
        } catch (\Exception $e) {
            return $this->failServerError();
        }
    }
}

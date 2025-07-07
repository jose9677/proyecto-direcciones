<?php

namespace App\Http\Controllers;

use App\Models\Edo;
use App\Models\Mun;
use App\Models\Prq;
use Illuminate\Http\Request;

class UbicGeograficController extends Controller
{
    public function getAllEdo()
    {
        $data = Edo::orderBy('id_edo')->get('*');

        return response()->json($data, 200);
    }

    public function getEdo($id_edo)
    {
        $data = Edo::where('id_edo', $id_edo)->get('estado');

        return response()->json($data, 200);
    }

    public function getAllMun()
    {
        $data = Mun::orderBy('id_edo')->get('*');

        return response()->json($data, 200);
    }

    public function getMun($id_edo, $id_mun)
    {
        $data = Mun::where(['id_edo' => $id_edo, 'id_mun' => $id_mun])->get('municipio');

        return response()->json($data, 200);
    }

    public function getAllPrq()
    {
        $data = Prq::orderBy('id_mun')->get('*');

        return response()->json($data, 200);
    }

    public function getPrq($id_mun, $id_prq)
    {
        $data = Prq::where(['id_mun' => $id_mun, 'id_prq' => $id_prq])->get('parroquia');

        return response()->json($data, 200);
    }
}

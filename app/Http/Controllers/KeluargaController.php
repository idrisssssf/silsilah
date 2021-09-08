<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluarga;
use Illuminate\Support\Facades\DB;

class KeluargaController extends Controller
{
    public static function getAnggota($nama)
    {
        return Keluarga::where('nama_anggota', $nama)->first();
    }

    public function getSemuaAnak($nama)
    {
        $anggota = self::getAnggota($nama);
        $anak = Keluarga::where('id_orang_tua', $anggota->id_anggota)->get();

        return response()->json([
            'message' => 'Semua Anak '.$anggota->nama_anggota,
            'anak'    => $anak  
        ], 200); 
    }

    public function getSemuaCucu($nama)
    {
        $anggota = self::getAnggota($nama);
        $keluarga = new Keluarga();
        $cucu = $keluarga->where([['id_orang_tua', '!=', 0], ['id_orang_tua' , '!=', $anggota->id_anggota]])->get();

        return response()->json([
            'message' => 'Semua Cucu '.$keluarga->nama_anggota,
            'cucu'    => $cucu
        ], 200);
    }

    public function getCucuPerempuan($nama)
    {
        $anggota = self::getAnggota($nama);
        $keluarga = new Keluarga();
        $cucu = $keluarga->where([['id_orang_tua', '!=', 0], 
            ['id_orang_tua' , '!=', $anggota->id_anggota], 
            ['jenis_kelamin', '=', "P"]])
        ->get();

        return response()->json([
            'message' => 'Semua Cucu '.$keluarga->nama_anggota,
            'cucu'    => $cucu
        ], 200);
    }

    public function getBibi($nama)
    {
        $anggota = self::getAnggota($nama);
        $orangTua = Keluarga::where('id_anggota', $anggota->id_orang_tua)->first();
        $bibi = Keluarga::where([['id_orang_tua', '=', $orangTua->id_orang_tua], ['jenis_kelamin' , '=', "P"]])->get();

        return response()->json([
            'message' => 'Bibi Dari '.$anggota->nama_anggota,
            'bibi'    => $bibi
        ], 200);
    }

    public function getSepupuLaki($nama)
    {
        $anggota = self::getAnggota($nama);
        $sepupu = Keluarga::where([['id_orang_tua', '!=', 0], 
            ['id_orang_tua' , '!=', 1],
            ['id_orang_tua' , '!=', $anggota->id_orang_tua], 
            ['jenis_kelamin', '=', "L"]])
        ->get();

        return response()->json([
            'message' => 'Sepupu Laki-laki Dari '.$anggota->nama_anggota,
            'sepupu'    => $sepupu
        ], 200);
    }

    public function createAnggota(Request $request)
    {
        $request->validate([
            'nama_anggota'  => 'required',
            'jenis_kelamin' => 'required',
            'orang_tua'     => 'required',
        ]);

        $orangTua = Keluarga::where('nama_anggota', $request->orang_tua)->first();
        $anggota = new Keluarga();
        $anggota->nama_anggota = $request->nama_anggota;
        $anggota->jenis_kelamin = $request->jenis_kelamin;
        $anggota->id_orang_tua = $orangTua->id_anggota;
        $anggota->save();

        return response()->json([
            'message'   => 'Anggota Baru Telah Ditambahkan',
            'anggota'   => $anggota
        ], 200);
    }

    public function getSemuaAnggota()
    {
        $anggota = Keluarga::all();

        return response()->json([
            'message'   => 'Semua Anggota Keluarga',
            'anggota'   => $anggota
        ], 200);
    }

    public function getDetailAnggota($nama)
    {
        $anggota = self::getAnggota($nama);
        $orangTua = Keluarga::where('id_anggota', $anggota->id_orang_tua)->first();

        return response()->json([
            'message'       => 'Detail Anggota Keluarga',
            'nama'          => $anggota->nama_anggota,
            'jenis_kelamin' => $anggota->jenis_kelamin,
            'nama_orang_tua'=> $orangTua->nama_anggota
        ], 200);
    }

    public function editAnggota(Request $request, $nama)
    {
        $anggota = self::getAnggota($nama);
        $request->validate([
            'nama_anggota'  => 'required',
            'jenis_kelamin' => 'required',
            'orang_tua'     => 'required',
        ]);

        $orangTua = Keluarga::where('nama_anggota', $request->orang_tua)->first();
        
        $anggota->nama_anggota = $request->nama_anggota;
        $anggota->jenis_kelamin = $request->jenis_kelamin;
        $anggota->id_orang_tua = $orangTua->id_anggota;
        $anggota->save();

        return response()->json([
            'message'   => 'Anggota Telah Diedit',
            'anggota'   => $anggota
        ], 200);
    }

    public function deleteAnggota($nama)
    {
        $anggota = Keluarga::where('nama_anggota', $nama)->delete();

        return response()->json([
            'message'   => 'Anggota Telah Dihapus'
        ], 200);
    }
}

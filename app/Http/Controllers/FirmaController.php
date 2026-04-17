<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\SesionTratamiento;

class FirmaController extends Controller
{
    // 🔐 Generar token + URL
    public function generarToken(Request $request)
    {
        $token = Str::random(64);

        DB::table('firmas_tokens')->insert([
            'sesion_id' => $request->sesion_id,
            'token' => $token,
            'expira_en' => Carbon::now()->addMinutes(5),
            'created_at' => now()
        ]);

        return response()->json([
            'url' => url('/firmar/' . $token)
        ]);
    }

    // 🌐 Mostrar página para firmar
    public function verFormulario($token)
    {
        $data = DB::table('firmas_tokens')
            ->where('token', $token)
            ->first();

        if (!$data || Carbon::now()->gt($data->expira_en)) {
            return "El enlace expiró o es inválido ❌";
        }

        return view('firma', compact('token'));
    }

    // 💾 Guardar firma
    public function guardarFirma(Request $request)
    {
        $data = DB::table('firmas_tokens')
            ->where('token', $request->token)
            ->first();

        if (!$data || Carbon::now()->gt($data->expira_en)) {
            return response()->json(['error' => 'Token inválido'], 400);
        }

        if (!$request->firma) {
            return response()->json(['error' => 'Firma vacía'], 400);
        }

        // 🔥 limpiar base64
        $image = str_replace('data:image/png;base64,', '', $request->firma);
        $image = str_replace(' ', '+', $image);

        // 🖼️ nombre del archivo
        $fileName = 'firmas/' . Str::random(20) . '.png';

        // 💾 guardar imagen en storage
        Storage::disk('public')->put($fileName, base64_decode($image));

        // 🗄️ guardar ruta en BD
        DB::table('sesiones_tratamiento')
            ->where('id', $data->sesion_id)
            ->update([
                'firma' => $fileName
            ]);

        // ❗ eliminar token
        DB::table('firmas_tokens')
            ->where('token', $request->token)
            ->delete();

        return response()->json(['ok' => true]);
    }

    public function firmaStatus($id){
        $sesion = SesionTratamiento::find($id);

        return response()->json([
            'firmado' => !is_null($sesion->firma),
            'firma' => $sesion->firma
        ]);
    }
}
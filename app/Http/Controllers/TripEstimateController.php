<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TripEstimateController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $origin = Str::trim($request->get('origin', ''));
        $destination = Str::trim($request->get('destination', ''));

        if (strlen($origin) < 2 || strlen($destination) < 2) {
            return response()->json(['error' => 'Nereden ve nereye alanları gerekli.'], 422);
        }

        $coords = config('city_coordinates', []);

        $originNorm = $this->normalizeCity($origin, $coords);
        $destNorm = $this->normalizeCity($destination, $coords);

        $coord1 = $originNorm ? $coords[$originNorm] : null;
        $coord2 = $destNorm ? $coords[$destNorm] : null;

        if (!$coord1 || !$coord2) {
            return response()->json(['error' => 'Bu güzergah için tahmin yapılamıyor.'], 422);
        }

        try {
            $response = Http::timeout(8)->get('https://router.project-osrm.org/route/v1/driving/' .
                $coord1[1] . ',' . $coord1[0] . ';' . $coord2[1] . ',' . $coord2[0] . '?overview=false');

            if (!$response->successful()) {
                return response()->json(['error' => 'Mesafe hesaplanamadı.'], 502);
            }

            $data = $response->json();
            $distance = $data['routes'][0]['distance'] ?? null;

            if ($distance === null) {
                return response()->json(['error' => 'Rota bulunamadı.'], 422);
            }

            $km = (int) round($distance / 1000);

            return response()->json(['km' => $km]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Bağlantı hatası. Tekrar deneyin.'], 502);
        }
    }

    private function normalizeCity(string $input, array $coords): ?string
    {
        $input = trim($input);
        if (isset($coords[$input])) {
            return $input;
        }
        $inputNorm = $this->trLower($input);
        foreach (array_keys($coords) as $city) {
            $cityNorm = $this->trLower($city);
            if ($cityNorm === $inputNorm || Str::startsWith($cityNorm, $inputNorm) || Str::contains($cityNorm, $inputNorm)) {
                return $city;
            }
        }
        return null;
    }

    private function trLower(string $s): string
    {
        $s = str_replace(['İ', 'I'], ['i', 'ı'], $s);
        return mb_strtolower($s, 'UTF-8');
    }
}

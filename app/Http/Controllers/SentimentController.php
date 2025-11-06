<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SentimentController extends Controller
{
    public function analyze(Request $request)
    {
        $review = $request->input('review');

        $response = Http::post('http://127.0.0.1:5000/predict', [
            'review' => $review
        ]);

        return response()->json([
            'review' => $review,
            'sentiment' => $response->json()['sentiment'] ?? 'Tidak diketahui'
        ]);
    }
}

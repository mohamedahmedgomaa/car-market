<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class AiSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim((string)$request->query('q', ''));

        if ($q === '') {
            return response()->json([
                'query' => $q,
                'parsed' => null,
                'message' => 'Query is empty'
            ], 422);
        }

        // ✅ مهم: خلي الـ AI يرجع JSON فقط
        $system = <<<SYS
You are an assistant that converts a car search sentence into structured filters.
Return ONLY valid JSON (no markdown, no extra text).
JSON schema:
{
  "brand": string|null,
  "model": string|null,
  "year_from": integer|null,
  "year_to": integer|null,
  "price_min": integer|null,
  "price_max": integer|null,
  "km_max": integer|null,
  "city": string|null,
  "fuel": "gasoline"|"diesel"|"hybrid"|"electric"|null,
  "gearbox": "automatic"|"manual"|null,
  "features": array of strings,
  "sort": "price_asc"|"price_desc"|"year_desc"|null
}
If a field is not mentioned, return null.
SYS;

        $user = "User query: {$q}";

        $res = OpenAI::chat()->create([
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user],
            ],
            'temperature' => 0.2,
        ]);

        $content = $res->choices[0]->message->content ?? '';

        // حاول نقرأه كـ JSON
        $parsed = json_decode($content, true);

        if (!is_array($parsed)) {
            return response()->json([
                'query' => $q,
                'raw' => $content,
                'error' => 'AI did not return valid JSON'
            ], 500);
        }

        return response()->json([
            'query' => $q,
            'parsed' => $parsed,
        ]);
    }
}

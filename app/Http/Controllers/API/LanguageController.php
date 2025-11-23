<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    // List available languages
    public function index()
    {
        $langPath = resource_path('lang');
        $languages = [];

        if (File::exists($langPath)) {
            $files = File::files($langPath);
            foreach ($files as $file) {
                $locale = pathinfo($file, PATHINFO_FILENAME);
                $languages[] = [
                    'code' => $locale,
                    'name' => $this->getLanguageName($locale)
                ];
            }
        }

        return response()->json($languages, 200);
    }

    // Get translations for a specific language
    public function show($locale)
    {
        $filePath = resource_path("lang/{$locale}.json");

        if (!File::exists($filePath)) {
            return response()->json(['message' => 'Language not found'], 404);
        }

        $translations = json_decode(File::get($filePath), true);

        return response()->json([
            'locale' => $locale,
            'translations' => $translations
        ], 200);
    }

    // Set user's language preference (if authenticated)
    public function set(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|in:en,sw'
        ]);

        $user = auth()->user();
        if ($user) {
            $user->update(['locale' => $validated['locale']]);
        }

        // Also set for current request
        app()->setLocale($validated['locale']);

        return response()->json([
            'message' => 'Language set successfully',
            'locale' => $validated['locale']
        ], 200);
    }

    private function getLanguageName($locale)
    {
        $names = [
            'en' => 'English',
            'sw' => 'Swahili'
        ];

        return $names[$locale] ?? $locale;
    }
}

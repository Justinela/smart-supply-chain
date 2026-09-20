<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    // Safe unambiguous character pool (excluding 0, O, 1, I, l, 5, S, 8, B)
    private const CHARSET = 'ACDEFGHJKMNPRTWXYZ234679';

    /**
     * Generate distorted SVG CAPTCHA image and store secret code in session.
     */
    public function generateImage(Request $request)
    {
        // 1. Generate random 6-character code
        $codeLength = 6;
        $code = '';
        $maxIndex = strlen(self::CHARSET) - 1;
        for ($i = 0; $i < $codeLength; $i++) {
            $code .= self::CHARSET[random_int(0, $maxIndex)];
        }

        // 2. Store secret code and 5-minute expiration timestamp in session
        session([
            'captcha_code' => $code,
            'captcha_expires_at' => now()->addMinutes(5)->timestamp,
        ]);

        // 3. Detect requested theme (default: light)
        $theme = strtolower($request->query('theme', 'light'));
        $isDark = ($theme === 'dark');

        // Color palettes
        $bgColor = $isDark ? '#172033' : '#FFFFFF';
        $borderColor = $isDark ? '#334155' : '#CBD5E1';
        $gridLineColor = $isDark ? 'rgba(255, 255, 255, 0.12)' : 'rgba(15, 23, 42, 0.1)';
        
        $charColors = $isDark 
            ? ['#6366F1', '#38BDF8', '#4ADE80', '#FBBF24', '#C084FC', '#F472B6', '#E2E8F0']
            : ['#0F172A', '#4F46E5', '#0284C7', '#16A34A', '#D97706', '#9333EA', '#DC2626'];

        $noiseColors = $isDark
            ? ['rgba(99, 102, 241, 0.3)', 'rgba(56, 189, 248, 0.3)', 'rgba(255, 255, 255, 0.2)']
            : ['rgba(79, 70, 229, 0.25)', 'rgba(2, 132, 199, 0.25)', 'rgba(15, 23, 42, 0.2)'];

        $width = 240;
        $height = 65;

        // 4. Build SVG elements
        $svgElements = [];

        // Outer Container & Background
        $svgElements[] = "<rect width=\"{$width}\" height=\"{$height}\" rx=\"8\" fill=\"{$bgColor}\" stroke=\"{$borderColor}\" stroke-width=\"1.5\" />";

        // Background Wave Pattern / Noise Lines
        for ($i = 0; $i < 6; $i++) {
            $x1 = random_int(0, 30);
            $y1 = random_int(5, $height - 5);
            $cx1 = random_int(60, 120);
            $cy1 = random_int(5, $height - 5);
            $cx2 = random_int(120, 180);
            $cy2 = random_int(5, $height - 5);
            $x2 = random_int(190, $width);
            $y2 = random_int(5, $height - 5);

            $stroke = $noiseColors[array_rand($noiseColors)];
            $strokeWidth = random_int(1, 2);

            $svgElements[] = "<path d=\"M {$x1} {$y1} C {$cx1} {$cy1}, {$cx2} {$cy2}, {$x2} {$y2}\" fill=\"none\" stroke=\"{$stroke}\" stroke-width=\"{$strokeWidth}\" stroke-linecap=\"round\" />";
        }

        // Random Noise Dots (Interference)
        for ($i = 0; $i < 35; $i++) {
            $cx = random_int(5, $width - 5);
            $cy = random_int(5, $height - 5);
            $r = random_int(1, 2);
            $fill = $noiseColors[array_rand($noiseColors)];
            $svgElements[] = "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$r}\" fill=\"{$fill}\" />";
        }

        // 5. Render Distorted Characters
        $charSpacing = ($width - 30) / $codeLength;
        for ($i = 0; $i < $codeLength; $i++) {
            $char = $code[$i];
            
            // Random attributes per character
            $x = 20 + ($i * $charSpacing) + random_int(-3, 3);
            $y = 44 + random_int(-5, 5); // Wavy baseline
            $fontSize = random_int(26, 32);
            $rotation = random_int(-25, 25);
            $color = $charColors[array_rand($charColors)];

            $svgElements[] = "<text x=\"{$x}\" y=\"{$y}\" font-family=\"'Inter', 'Courier New', monospace\" font-size=\"{$fontSize}\" font-weight=\"800\" fill=\"{$color}\" transform=\"rotate({$rotation}, {$x}, {$y})\">{$char}</text>";
        }

        // Foreground Crossing Noise Line (OCR prevention)
        $fx1 = 10;
        $fy1 = random_int(20, 45);
        $fx2 = $width - 10;
        $fy2 = random_int(20, 45);
        $fstroke = $isDark ? 'rgba(255, 255, 255, 0.4)' : 'rgba(15, 23, 42, 0.35)';
        $svgElements[] = "<line x1=\"{$fx1}\" y1=\"{$fy1}\" x2=\"{$fx2}\" y2=\"{$fy2}\" stroke=\"{$fstroke}\" stroke-width=\"2\" stroke-dasharray=\"4 2\" />";

        // Assemble SVG markup
        $svgContent = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $svgContent .= "<svg width=\"{$width}\" height=\"{$height}\" viewBox=\"0 0 {$width} {$height}\" xmlns=\"http://www.w3.org/2000/svg\">\n";
        $svgContent .= implode("\n", $svgElements);
        $svgContent .= "\n</svg>";

        return response($svgContent, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Server-side static helper to validate user CAPTCHA input.
     * Returns null if valid, or string error message if invalid.
     */
    public static function validate(string $userInput): ?string
    {
        $sessionCode = session('captcha_code');
        $expiresAt = session('captcha_expires_at');

        // Check if session code exists
        if (!$sessionCode || !$expiresAt) {
            return 'CAPTCHA session missing. Please refresh the CAPTCHA and try again.';
        }

        // Check expiration (5 minutes)
        if (time() > $expiresAt) {
            session()->forget(['captcha_code', 'captcha_expires_at']);
            return 'CAPTCHA expired. Please generate a new CAPTCHA.';
        }

        // Normalize input (trim whitespace and convert to uppercase)
        $normalizedInput = strtoupper(trim($userInput));

        // Rate limiting / failure tracking
        $attempts = session('captcha_attempts', 0);
        if ($attempts >= 5) {
            // Require new CAPTCHA after 5 failed attempts
            session()->forget(['captcha_code', 'captcha_expires_at', 'captcha_attempts']);
            return 'Too many failed CAPTCHA attempts. A new CAPTCHA has been generated. Please try again.';
        }

        // Strict comparison
        if (!hash_equals($sessionCode, $normalizedInput)) {
            session(['captcha_attempts' => $attempts + 1]);
            // Invalidate current code to prevent brute-forcing the same CAPTCHA
            session()->forget(['captcha_code', 'captcha_expires_at']);
            return 'Incorrect CAPTCHA. Please try again.';
        }

        // Clear CAPTCHA session on successful validation
        session()->forget(['captcha_code', 'captcha_expires_at', 'captcha_attempts']);
        return null;
    }
}

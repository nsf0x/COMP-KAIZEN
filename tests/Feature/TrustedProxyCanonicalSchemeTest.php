<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class TrustedProxyCanonicalSchemeTest extends TestCase
{
    public function test_https_forwarded_by_a_trusted_proxy_is_secure_without_an_application_redirect(): void
    {
        Route::middleware('web')->get('/_tests/trusted-proxy-scheme', function (Request $request) {
            return response()->json([
                'secure' => $request->isSecure(),
                'origin' => $request->getSchemeAndHttpHost(),
            ]);
        });

        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->withHeaders([
                'X-Forwarded-Proto' => 'https',
                'X-Forwarded-Host' => 'kaizenkreasi.com',
            ])
            ->get('/_tests/trusted-proxy-scheme')
            ->assertOk()
            ->assertJson([
                'secure' => true,
                'origin' => 'https://kaizenkreasi.com',
            ])
            ->assertHeaderMissing('Location');
    }

    public function test_apache_redirect_skips_https_requests_terminated_by_a_reverse_proxy(): void
    {
        $rewriteRules = file_get_contents(public_path('.htaccess'));

        $this->assertIsString($rewriteRules);
        $this->assertStringContainsString('RewriteCond %{HTTP:X-Forwarded-Proto} !https [NC]', $rewriteRules);
        $this->assertStringContainsString('RewriteCond %{HTTPS} !=on', $rewriteRules);
        $this->assertStringContainsString('RewriteCond %{HTTP_HOST} ^www\.kaizenkreasi\.com$ [NC]', $rewriteRules);
    }

    public function test_forwarded_https_from_an_untrusted_remote_is_not_trusted(): void
    {
        Route::middleware('web')->get('/_tests/untrusted-proxy-scheme', function (Request $request) {
            return response()->json(['secure' => $request->isSecure()]);
        });

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->withHeaders(['X-Forwarded-Proto' => 'https'])
            ->get('/_tests/untrusted-proxy-scheme')
            ->assertOk()
            ->assertJson(['secure' => false]);
    }
}

<?php

namespace Tests\Unit;

use App\Http\Middleware\RecruitsySyncToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class RecruitsySyncTokenMiddlewareTest extends TestCase
{
    private RecruitsySyncToken $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new RecruitsySyncToken();
    }

    public function test_middleware_passes_with_valid_token()
    {
        Config::set('services.recruitsy.sync_token', 'test_token_123');

        $request = Request::create('/test', 'POST');
        $request->headers->set('X-Recruitsy-Sync-Token', 'test_token_123');

        $response = $this->middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_middleware_fails_with_invalid_token()
    {
        Config::set('services.recruitsy.sync_token', 'test_token_123');

        $request = Request::create('/test', 'POST');
        $request->headers->set('X-Recruitsy-Sync-Token', 'wrong_token');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Invalid sync token');

        $this->middleware->handle($request, function ($req) {
            return response('OK');
        });
    }

    public function test_middleware_fails_with_missing_token()
    {
        Config::set('services.recruitsy.sync_token', 'test_token_123');

        $request = Request::create('/test', 'POST');
        // No token header set

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Invalid sync token');

        $this->middleware->handle($request, function ($req) {
            return response('OK');
        });
    }

    public function test_middleware_fails_when_token_not_configured()
    {
        Config::set('services.recruitsy.sync_token', null);

        $request = Request::create('/test', 'POST');
        $request->headers->set('X-Recruitsy-Sync-Token', 'test_token_123');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Sync token not configured');

        $this->middleware->handle($request, function ($req) {
            return response('OK');
        });
    }
}

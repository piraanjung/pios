<?php

namespace Tests\Unit\Shared;

use App\Shared\Http\ApiResponse;
use App\Shared\Tenancy\TenantContext;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;

class ApiResponseTest extends TestCase
{
    protected function tearDown(): void
    {
        TenantContext::reset();
        parent::tearDown();
    }

    public function test_success_envelope_structure(): void
    {
        TenantContext::setOrgId(10);

        $response = ApiResponse::success(['id' => 1, 'name' => 'ถังขยะเปียก'], 'โหลดข้อมูลสำเร็จ');
        $content = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($content['success']);
        $this->assertSame('โหลดข้อมูลสำเร็จ', $content['message']);
        $this->assertSame(['id' => 1, 'name' => 'ถังขยะเปียก'], $content['data']);
        $this->assertNull($content['errors']);
        $this->assertArrayHasKey('meta', $content);
        $this->assertSame(10, $content['meta']['org_id']);
        $this->assertSame('v1', $content['meta']['version']);
        $this->assertArrayHasKey('timestamp', $content['meta']);
    }

    public function test_created_envelope_returns_201_status(): void
    {
        $response = ApiResponse::created(['user_id' => 5], 'ลงทะเบียนเรียบร้อย');
        $content = json_decode($response->getContent(), true);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertTrue($content['success']);
        $this->assertSame('ลงทะเบียนเรียบร้อย', $content['message']);
        $this->assertSame(['user_id' => 5], $content['data']);
        $this->assertNull($content['errors']);
        $this->assertNotNull($content['meta']['timestamp']);
    }

    public function test_error_envelope_structure(): void
    {
        $errors = ['email' => ['อีเมลนี้ถูกใช้งานแล้ว']];
        $response = ApiResponse::error(
            message: 'ข้อมูลไม่ถูกต้อง',
            statusCode: 422,
            errors: $errors
        );
        $content = json_decode($response->getContent(), true);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertFalse($content['success']);
        $this->assertSame('ข้อมูลไม่ถูกต้อง', $content['message']);
        $this->assertNull($content['data']);
        $this->assertSame($errors, $content['errors']);
        $this->assertArrayHasKey('meta', $content);
    }

    public function test_paginated_envelope_structure(): void
    {
        $items = collect([['id' => 1], ['id' => 2]]);
        $paginator = new LengthAwarePaginator($items, total: 10, perPage: 2, currentPage: 1);

        $response = ApiResponse::paginated($paginator, 'รายการข้อมูล');
        $content = json_decode($response->getContent(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($content['success']);
        $this->assertCount(2, $content['data']);
        $this->assertArrayHasKey('pagination', $content['meta']);
        $this->assertSame(1, $content['meta']['pagination']['current_page']);
        $this->assertSame(5, $content['meta']['pagination']['last_page']);
        $this->assertSame(2, $content['meta']['pagination']['per_page']);
        $this->assertSame(10, $content['meta']['pagination']['total']);
    }

    public function test_no_content_returns_204(): void
    {
        $response = ApiResponse::noContent();
        $this->assertSame(204, $response->getStatusCode());
    }
}

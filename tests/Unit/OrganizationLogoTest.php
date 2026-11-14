<?php

namespace Tests\Unit;

use App\Models\Admin\Organization;
use Tests\TestCase;

class OrganizationLogoTest extends TestCase
{
    /** @test */
    public function it_resolves_existing_logo_file()
    {
        // ko_envsogo.png is a guaranteed existing asset
        $url = Organization::resolveLogoUrl('ko_envsogo.png', true);
        $this->assertNotNull($url);
        $this->assertStringContainsString('logo/ko_envsogo.png', $url);

        // ku_csc.png is now a guaranteed asset
        $kuUrl = Organization::resolveLogoUrl('ku_csc.png', true);
        $this->assertNotNull($kuUrl);
        $this->assertStringContainsString('logo/ku_csc.png', $kuUrl);
    }

    /** @test */
    public function it_falls_back_to_default_when_logo_is_missing_and_show_default_is_true()
    {
        $url = Organization::resolveLogoUrl('non_existent_fake_logo_12345.png', true);
        $this->assertNotNull($url);
        $this->assertStringContainsString('logo/ko_envsogo.png', $url);

        $emptyUrl = Organization::resolveLogoUrl(null, true);
        $this->assertNotNull($emptyUrl);
        $this->assertStringContainsString('logo/ko_envsogo.png', $emptyUrl);
    }

    /** @test */
    public function it_returns_null_when_logo_is_missing_and_show_default_is_false()
    {
        $url = Organization::resolveLogoUrl('non_existent_fake_logo_12345.png', false);
        $this->assertNull($url);

        $emptyUrl = Organization::resolveLogoUrl('', false);
        $this->assertNull($emptyUrl);
    }
}

<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $sqlitePdo = \Illuminate\Support\Facades\DB::connection('sqlite')->getPdo();
            \Illuminate\Support\Facades\DB::connection('envsogo_main')->setPdo($sqlitePdo);
            \Illuminate\Support\Facades\DB::connection('tenant')->setPdo($sqlitePdo);
        }
    }
}

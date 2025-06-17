<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    Protected function setUp(): void{
        parent::setUp();
        DB::delete("delete from users");
        DB::delete("delete from transactions");
    }
}

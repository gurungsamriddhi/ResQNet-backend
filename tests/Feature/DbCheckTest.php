<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DbCheckTest extends TestCase
{
    public function test_db_name()
    {
        $dbName = DB::connection()->getDatabaseName();
        file_put_contents('db_test_output.txt', $dbName);
        $this->assertTrue(true);
    }
}

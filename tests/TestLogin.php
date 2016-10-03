<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

//model
use App\User;
use App\Device;

class TestLogin extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test1AccountUsing4Devices()
    {
        // test login 1 success
        $response  = $this->call('POST','/login',[
            'userId'=>'testUser@gmail.com',
            'password' => 'secret',
            'deviceId' => 'testDeviceId1',
            'platform' => 'android'
        ]);

        $result = json_decode($response->content());
        $this->assertEquals(200,$result->status_code);
        $this->assertEquals(200,$result->status_code);
        $this->assertEquals('success',$result->status);
        $this->assertEquals('Authenticated',$result->message);

        // test login 2 success
        $response  = $this->call('POST','/login',[
            'userId'=>'testUser@gmail.com',
            'password' => 'secret',
            'deviceId' => 'testDeviceId1',
            'platform' => 'android'
        ]);

        $result = json_decode($response->content());
        $this->assertEquals(200,$result->status_code);
        $this->assertEquals(200,$result->status_code);
        $this->assertEquals('success',$result->status);
        $this->assertEquals('Authenticated',$result->message);

    }
}

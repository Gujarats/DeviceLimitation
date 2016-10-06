<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

//repositories
use App\Repositories\PolicyLogin;

//model
use App\User;
use App\Device;

class PolicyLoginTest extends TestCase
{

    use DatabaseTransactions;
    
    public function testIsUserReachLimitDeviceFalse(){
        
        //dummy data
        $user = new User();
        $user->name = 'testName';
        $user->email = 'testName@gamil.com';
        $user->password = 'secret';
        $user->save();

        $policyLogin = new PolicyLogin();
        $this->assertEquals(false,$policyLogin->isUserReachLimitDevice($user->id));
    }
    
    public function testIsUserReachLimitDeviceFalse2(){

        //dummy data
        $user = new User();
        $user->name = 'testName';
        $user->email = 'testName@gamil.com';
        $user->password = 'secret';
        $user->save();
        
        //create dummy data user with 1 device
        $device = new Device();
        $device->user_id = $user->id;
        $device->device_id = 'testDeviceId1';
        $device->platform = 'android';
        $device->save();

        $policyLogin = new PolicyLogin();
        $this->assertEquals(false,$policyLogin->isUserReachLimitDevice($user->id));
    }

    public function testIsUserReachLimitDeviceFalse3(){

        //dummy data
        $user = new User();
        $user->name = 'testName';
        $user->email = 'testName@gamil.com';
        $user->password = 'secret';
        $user->save();
        
        //create dummy data user with 2 devices
        $device = new Device();
        $device->user_id = $user->id;
        $device->device_id = 'testDeviceId1';
        $device->platform = 'android';
        $device->save();
        
        $device = new Device();
        $device->user_id = $user->id;
        $device->device_id = 'testDeviceId2';
        $device->platform = 'android';
        $device->save();

        $policyLogin = new PolicyLogin();
        $this->assertEquals(false,$policyLogin->isUserReachLimitDevice($user->id));
    }

    public function testIsUserReachLimitDeviceTrue(){

        //dummy data
        $user = new User();
        $user->name = 'testName';
        $user->email = 'testName@gamil.com';
        $user->password = 'secret';
        $user->save();
        
        //create dummy data user with 3 devices
        $device = new Device();
        $device->user_id = $user->id;
        $device->platform = 'android';
        $device->device_id = 'testDeviceId1';
        $device->save();
        
        $device = new Device();
        $device->platform = 'android';
        $device->user_id = $user->id;
        $device->device_id = 'testDeviceId2';
        $device->save();

        $device = new Device();
        $device->platform = 'android';
        $device->user_id = $user->id;
        $device->device_id = 'testDeviceId3';
        $device->save();

        $policyLogin = new PolicyLogin();
        $this->assertEquals(true,$policyLogin->isUserReachLimitDevice($user->id));
    }

    public function testIsDeviceBannedTrue()
    {
        $device = new Device();
        $device->platform = 'android';
        $device->user_id= 'testID';
        $device->device_id='deviceIdForTesting';
        $device->is_banned = true;
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceBanned('deviceIdForTesting'),true);
    }

    public function testIsDeviceBannedFalse()
    {
        $device = new Device();
        $device->platform = 'android';
        $device->user_id='testID';
        $device->device_id='deviceIdForTesting';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceBanned('deviceIdForTesting'),false);
    }

    public function testIsDeviceExistTrue()
    {
        $device = new Device();
        $device->platform = 'android';
        $device->user_id='testID';
        $device->device_id='deviceIdForTesting';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceExistWithUser('deviceIdForTesting','testID'),true);
    }

    public function testIsDeviceExistFalse()
    {
        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceExistWithUser('deviceIdForTesting','testID'),false);
    }
}

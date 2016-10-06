<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Device;

class PolicyLoginTest extends TestCase
{

    use DatabaseTransactions;

    public function testHasUserLoginToTwoDevicesTrue()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting';
        $device->save();


        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting2';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->hasUserLoginToTwoDevices('userIdForTesting'),true);
    }

    public function testHasUserLoginToTwoDevicesFalse1()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->hasUserLoginToTwoDevices('userIdForTesting'),false);
    }

    public function testHasUserLoginToTwoDevicesFalse2()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting';
        $device->save();


        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting2';
        $device->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting3';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->hasUserLoginToTwoDevices('userIdForTesting'),false);
    }

    public function testHasUserLoginToTwoDevicesFalse3()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting';
        $device->save();


        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting2';
        $device->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting3';
        $device->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting4';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->hasUserLoginToTwoDevices('userIdForTesting'),false);
    }

    public function testIsDeviceBannedTrue()
    {
        $device = new Device();
        $device->user_id= 'testID';
        $device->deviceId='deviceIdForTesting';
        $device->is_banned = true;
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceBanned('deviceIdForTesting'),true);
    }

    public function testIsDeviceBannedFalse()
    {
        $device = new Device();
        $device->user_id='testID';
        $device->deviceId='deviceIdForTesting';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceBanned('deviceIdForTesting'),false);
    }

    public function testIsDeviceExistTrue()
    {
        $device = new Device();
        $device->user_id='testID';
        $device->deviceId='deviceIdForTesting';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceExistWithUser('deviceIdForTesting','testID'),true);
    }

    public function testIsDeviceExistFalse()
    {
        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceExistWithUser('deviceIdForTesting','testID'),false);
    }


    public function testIsDeviceUsedBeforeTrue()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting';
        $device->save();


        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceUsedBefore('userIdForTesting','deviceIdForTesting'),true);
    }

    public function testIsDeviceUsedBeforeFalse()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isDeviceUsedBefore('userIdForTesting','deviceIdForTesting'),false);
    }


    public function testIsUserHasMoreThanTwoDevices()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting';
        $device->save();


        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting2';
        $device->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting3';
        $device->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting4';
        $device->save();

        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isUserHasMoreThanTwoDevices('userIdForTesting'),true);
    }

    public function testIsUserHasMoreThanTwoDevices2()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting';
        $device->save();


        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isUserHasMoreThanTwoDevices('userIdForTesting'),false);
    }

    public function testIsUserHasMoreThanTwoDevices3()
    {
        $user = new User();
        $user->name = 'userIdForTesting';
        $user->device = 'android';
        $user->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting';
        $device->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting2';
        $device->save();

        $device = new Device();
        $device->user_id=$user->id;
        $device->deviceId='deviceIdForTesting3';
        $device->save();


        $policyLogin = new PolicyLogin;
        $this->assertEquals($policyLogin->isUserHasMoreThanTwoDevices('userIdForTesting'),true);
    }
}

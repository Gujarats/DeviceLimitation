<?php

namespace App\Repositories;
//model
use App\User;
use App\Device;

class PolicyLogin
{
    /**
     * Login checking : One Users can only have two devices/ reject if have more than that
     * check device if has a record in users
     * once a deivce login and success, it stores to database table users
     **/

    public function hasUserLoginToTwoDevices($user_id){
        $user = User::where(['name'=>$user_id])->withCount('devices')->first();
        if($user){
            //user found check number of devices
            if($user->devices_count == 2){
                return true;
            }else{
                return false;
            }
        }else {
            //user not found insert to device to database
            return false;
        }
    }

    public function isUserHasMoreThanTwoDevices($user_id)
    {
        $user = User::where(['name'=>$user_id])->withCount('devices')->first();
        if($user){
            //user found check number of devices
            if($user->devices_count > 2){
                return true;
            }else{
                return false;
            }
        }else {
            //user not found insert to device to database
            return false;
        }
    }

    public function isDeviceBanned($deviceId)
    {
        $device = Device::where(['deviceId'=>$deviceId])->first();
        if($device){
            return $device->is_banned;
        }else{
            return false;
        }

    }

    public function isDeviceExistWithUser($deviceId,$userId)
    {
        $device = Device::where(['deviceId'=>$deviceId,'user_id'=>$userId])->first();
        if($device){
            return true;
        }else{
            return false;
        }
    }

    public function bannedDevice($user_id,$deviceId)
    {
        $user = User::where(['name'=>$user_id])->first();
        $device = new Device();
        $device->user_id = $user->id;
        $device->deviceId = $deviceId;
        $device->is_banned = true;
        $device->save();
    }

    public function isDeviceUsedBefore($user_id,$deviceId)
    {
        $user = User::where(['name'=>$user_id])->first();
        $devices = Device::where(['user_id'=>$user->id])->get();
        foreach ($devices as $device) {
            if($device->deviceId == $deviceId){
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * Login checking : One Users can only have two devices/ reject if have more than that
     * check device if has a record in users
     * once a deivce login and success, it stores to database table users
     **/

    public function isLoginValid($userId,$deviceId,$userIdTable){
        if($policyLogin->hasUserLoginToTwoDevices($userId,$deviceId)){
            if($policyLogin->isDeviceUsedBefore($userId,$deviceId)){
                // return ok response device not banned
                return true;
            }else{
                //save the device with banned status
                $policyLogin->bannedDevice($userId,$deviceId);

                // return not ok response device banned
                return false;

            }
        }elseif($policyLogin->isUserHasMoreThanTwoDevices($userId,$deviceId)){
            if(!$policyLogin->isDeviceExistWithUser($deviceId,$userIdTable)){
                $policyLogin->bannedDevice($userId,$deviceId);

                // return not ok response device banned
                return false;

            }else{
                //check device is banned or not
                if(!$policyLogin->isDeviceBanned($deviceId)){
                    // return ok response device not banned
                    return true;
                }else{
                    // return not ok response device banned
                    return false;
                }
            }


        }else{

            if(!$policyLogin->isDeviceExistWithUser($deviceId,$userIdTable)){
                //create new device if not Exist
                $device = new Device();
                $device->deviceId = $deviceId;
                $device->user_id =$userIdTable;
                $device->save();
            }

            // return ok response device not banned
            return true;

        }

    }

    public function isLoginValid2(){
        //retrive user or create new one if doesnt exist
        $params = [
            'user_id' => $request->userId,
            'deviceId' => $request->deviceId
        ];
        $device = Device::where($params)->first();

        if (!$device) {

            // check if not exceed limit (with active status)
            if (Device::where(['user_id', $request->userId, 'is_banned' => false])->count() == $deviceLimit) {
                return $response->json([
                    'Error'=>array(
                        "Type"=>"Error",
                        "Code"=>401,
                        "Message"=>"Your account is has reached device limit"
                    ),
                    'Data'=>null], 401);
            }

            // if not create user with active status
            $device = new Device();
            $device->user_id= $request->userId;
            $device->platform= $request->platform;
            $device->is_banned = false;
            $device->deviceId = $request->deviceId;
            $device->save();
        }
        else {
            // check if banned
            if ($device->is_banned) {
                return $response->json([
                    'Error'=>array(
                        "Type"=>"Error",
                        "Code"=>401,
                        "Message"=>"Your account is has been disabled"
                    ),
                    'Data'=>null], 401);
            }
        }

        // return success message
        return $response->json([
            'Error'=>null,
            'Data'=>array(
                'AuthToken' => $token,
                'InactiveTimeout' => 20
            )], 200);
    }

}

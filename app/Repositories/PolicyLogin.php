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

    public function isUserReachLimitDevice($user_id){
        $device_limit = 2;
        if($device = Device::where(['user_id'=>$user_id, 'is_banned'=>false])->count()<=2){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Login checking : One Users can only have two devices/ reject if have more than that
     * check device if has a record in users
     * once a deivce login and success, it stores to database table users
     * Note :  the banned device is strored in devices table
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


    /*
     * using different algorithm 
     * device this algorithm will not save the banned device,
     * checking the current device that user used in the device table
     *
     * ===== Question ======
     * a function must do only 1 job and only one job
     * below we can separate some line and create function that 
     * 1. will return the device
     * 2. checking if the user has reach limit device
     * 3.  checking if device is banned or not
     */
    public function isLoginValid2($user_id,$device_id){

        $deviceLimit = 2;

        //retrive device or create new one if doesnt exist
        $params = [
            'user_id' => $request->userId,
            'deviceId' => $request->deviceId
        ];
        $device = Device::where($params)->first();

        if (!$device) {

            // check if not exceed limit  
            if (Device::where(['user_id', $request->userId, 'is_banned' => false])->count() <= $deviceLimit) {
                return true;
            }else{
                // the device is above llmit
                return false;
            }

        }else {
            // check if banned
            if ($device->is_banned) {
                // the device is banned user is not pemmitted to login
                return false;
            }else{
                return true;
            }
        }

    }


    /*
     * isLoginValid2Refactored sepaarate some line so we can test it,
     * and the function will do 1 job onlye
     *
     */
    public function isLoginValid2Refactored($user_id,$device_id){
        if($this->isDeviceExistWithUser($user_id,$device_id)){
            //device is exist check if its banned
            if($this->isDeviceBanned($device_id)){
                return false;
            }else{
                return true;
            }
        }else{
            //device is not exits check device limit
            if($this->isUserReachLimitDevice($user_id)){
                //user reach limit device 
                return false;
            }else{
                return true;
            }
        }
    }

}

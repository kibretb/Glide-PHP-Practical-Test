<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorApiRequest;
use App\Models\OrganisationallyUniqueIdentifier;

class VendorApiController extends Controller
{

    public function singleMacLookUp(VendorApiRequest $request)
    {
        $validator = $request->validateSingleMacAddress();

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $validatedInput = $validator->validated();
        $macAddress = $validatedInput['mac_address'];

        $lookupAddress = substr(preg_replace("/[^a-zA-Z0-9]+/", "", $macAddress),0,6);
        $lookupAddress = $request->checkMacRandomisation($lookupAddress);
        $vendorOUI = OrganisationallyUniqueIdentifier::where('assignment',$lookupAddress)->first();

        if($vendorOUI){
            return response()->json(['error'=>false,'vendor'=>['mac_address' => $macAddress, 'vendor'=>$vendorOUI->organization_name],'code'=>200]);
        }else{
            return response()->json(['error'=>true,'code'=>'404']);
        }
    }

    public function multipleMacLookUp(VendorApiRequest $request)
    {
        $validator = $request->validateMultipleMacAddresses();

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $validatedInput =  $validator->validated();
        $macAddressess  =  $validatedInput['mac_addresses'];

        $lookupAddresses  = [];
        foreach( $macAddressess as $macAddress){
            $lookupAddress = substr(preg_replace("/[^a-zA-Z0-9]+/", "", $macAddress),0,6);
            $lookupAddresses[$macAddress] = $lookupAddress;
        }

        //exclude random mac addresess from search
        $lookupAddresses  =  $request->checkMacRandomisation($lookupAddresses);

        $vendorOUIRecords = OrganisationallyUniqueIdentifier::whereIn('assignment',$lookupAddresses)
                                                      ->get();
        $vendorRecords = [];
        foreach($vendorOUIRecords as $vendorOUI){
            $macAddress = array_search($vendorOUI->assignment,$lookupAddresses);
            array_push($vendorRecords,['mac_address' => $macAddress,'vendor'=>$vendorOUI->organization_name]);
        }

        if(count($vendorRecords) > 0){
            return response()->json(['error'=>false,'vendors'=>$vendorRecords,'total'=>count($vendorRecords),'code'=>200]);
        }else{
            return response()->json(['error'=>true,'code'=>'404']);
        }
    }
}

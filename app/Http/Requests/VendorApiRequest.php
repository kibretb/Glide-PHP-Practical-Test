<?php

namespace App\Http\Requests;

use App\Models\OrganisationallyUniqueIdentifier;
use App\Rules\MacAddressRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class VendorApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function validateMultipleMacAddresses()
    {
        $validator = Validator::make($this->all(),[
                'mac_addresses'   => 'required|array',
                'mac_addresses.*' => ['string','regex:/^[A-Za-z0-9]+[A-Za-z0-9.\-:]+[A-Za-z0-9]+$/',new MacAddressRule]
            ]
        );

        return $validator;
    }

    public function validateSingleMacAddress()
    {
        $validator = Validator::make($this->all(),[
            'mac_address' => ['required','string','regex:/^[A-Za-z0-9]+[A-Za-z0-9.\-:]+[A-Za-z0-9]+$/',new MacAddressRule]
        ]);

        return $validator;
    }


    //function that checks Random Mac Addresses and excludes them from the search
    public function checkMacRandomisation($macAddress)
    {
        if(is_array($macAddress)){
            foreach($macAddress as $key => $address){
                $rendomisationCharacter = $address[1];
                if(in_array($rendomisationCharacter,OrganisationallyUniqueIdentifier::RandomMacIndicators))
                {
                    unset($macAddress[$key]);
                }
            }
        }else{
            $rendomisationCharacter = $macAddress[1];
            if(in_array($rendomisationCharacter,OrganisationallyUniqueIdentifier::RandomMacIndicators)){
                $macAddress = null;
            }
        }

        return $macAddress;
    }
}

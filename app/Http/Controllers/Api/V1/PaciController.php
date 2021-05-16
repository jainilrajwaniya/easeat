<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\AppSettings;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseTrait;
use Exception;
use Auth;

class PaciController extends Controller {
    use ResponseTrait;
    
    public function __construct() {
        $this->currentUser = Auth::guard('api')->user();
    }
    
    /**
     * Get paci data
     * @param Request $request
     * @return type
     */
    function getPaciData(Request $request) {
        try {
            //get user id or guest user id
            $user_id = $guest_user_id = null;
            if(!empty($this->currentUser->id)) {
                $user_id = $this->currentUser->id;
            } else {
                $guestUserObj = $this->getGuestUserDetails();
                if(!empty($guestUserObj->id)) {
                    $guest_user_id = $guestUserObj->id;
                }
            }

            //return if both user id and guest user id not found
            if($guest_user_id == NULL && $user_id == NULL) {
                return $this->error('USER_OR_GUEST_USER_NOT_FOUND');
            }

            if (!isset($request->lat) || $request->lat == '' || !isset($request->long) || $request->long == '') {
                return $this->error("PLEASE_PASS_LAT_LONG");
                exit;
            }
            $lat = $request->lat;
            $long = $request->long;

            //if paci token is not there then create
            $paciToken = AppSettings::select('value')->where('type', 'PACI_TOKEN')->first();
            if (!$paciToken) {
                $tokenResult = $this->getPaciToken();
                //if cant generate paci token then return
                if (!isset($tokenResult['token'])) {
                    return $this->error('PACI_API_ISSUE');
                    exit;
                } else {
                    $paciToken = $tokenResult['token'];
                }
            } else {
                $paciToken = $paciToken->value;
            }

            //call paci address api
            $dataResponse = $this->getAddressFromPaciApi($lat, $long, $paciToken);

            //if api erro then return
            if (isset($dataResponse['error']) && $dataResponse['error'] == 1) {
                return $this->error('PACI_API_ISSUE');
            } else if (isset($dataResponse['error']) && $dataResponse['error'] == 'INVALID_TOKEN') {
                //if token error then generate new and call data api again
                $tokenResult = $this->getPaciToken();
                if (!isset($tokenResult['token'])) {
                    return $this->error('PACI_API_ISSUE'); //$tokenResult['msg']
                    exit;
                } else {
                    $paciToken = $tokenResult['token'];
                }
                //call paci address api
                $dataResponse = $this->getAddressFromPaciApi($lat, $long, $paciToken);
                if (isset($dataResponse['error']) && $dataResponse['error'] == 1) {
                    return $this->error('PACI_API_ISSUE');
                } else {
                    $dataResponse = count($dataResponse) > 0 ? $dataResponse : [];
                    return $this->success($dataResponse, 'ADDRESS_FOUND');
                }
            } else {
                $dataResponse = count($dataResponse) > 0 ? $dataResponse : [];
                return $this->success($dataResponse, 'ADDRESS_FOUND');
            }
        } catch (Exception $ex) {
            return $this->error('ERROR');
        }
    }

    /**
     * Get PACI Token
     * @return type
     */
    public function getPaciToken() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://kuwaitportal.paci.gov.kw/arcgis/sharing/generateToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"request\"\r\n\r\ngetToken\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"f\"\r\n\r\njson\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"referer\"\r\n\r\nhttps://kuwaitportal.paci.gov.kw/arcgis/sharing\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"expiration\"\r\n\r\n300\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"username\"\r\n\r\nEaseatUser\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"password\"\r\n\r\n".'E@$un*20a'."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        if ($err) {
            return ['error' => 1, 'msg' => 'Cannot generate token'];
        } else {
            $arr = json_decode($response, 1);
            if (isset($arr['token'])) {
                AppSettings::where(['type' => 'PACI_TOKEN'])->delete();//delete previous tokens
                AppSettings::create(['type' => 'PACI_TOKEN', 'value' => $arr['token']]);
                return ['error' => 0, 'msg' => 'Token generated', 'token' => $arr['token']];
            } else {
                return ['error' => 1, 'msg' => isset($arr['error']['details'][0]) ? $arr['error']['details'][0] : "Error in generating token"];
            }
        }
    }

    /**
     * Get address content from paci api
     * @param type $lat
     * @param type $long
     * @param type $token
     * @return type
     */
    public function getAddressFromPaciApi($lat, $long, $token) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://kuwaitportal.paci.gov.kw/arcgisportal/rest/services/PACIAddressSearch/MapServer/identify?geometry=%7By%3A$lat%2C%20x%3A$long%2C%20Spatial%20Reference%3A4326%7D&geometryType=esriGeometryPoint&sr=4326&layers=all&token=$token&tolerance=10&mapExtent=XMin%3A%205069581.550573585%20YMin%3A%203310706.102544307%20XMax%3A%205534770.219630809%20YMax%3A%203526899.554510004%20Spatial%20Reference%3A%20102100%20(3857)&imageDisplay=600%2C550%2C96&returnGeometry=false&f=pjson",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return ['error' => 1];
        } else {
            $result = json_decode($response, 1);
            //return if token is invalid
            if (isset($result['error']['code']) && $result['error']['code'] == 498) {
                return ['error' => 'INVALID_TOKEN'];
                exit;
            }

            if (isset($result['results'][0]['attributes'])) {
                $area_ar = isset($result['results'][0]['attributes']['neighborhoodarabic']) ? $result['results'][0]['attributes']['neighborhoodarabic'] : '';
                $area_en = isset($result['results'][0]['attributes']['neighborhoodenglish']) ? $result['results'][0]['attributes']['neighborhoodenglish'] : '';
                $gov_en = isset($result['results'][0]['attributes']['governorateenglish']) ? $result['results'][0]['attributes']['governorateenglish'] : '';
                $gov_ar = isset($result['results'][0]['attributes']['governoratearabic']) ? $result['results'][0]['attributes']['governoratearabic'] : '';
                $block = isset($result['results'][0]['attributes']['blockarabic']) ? $result['results'][0]['attributes']['blockarabic'] : '';
                
                return ['area_en' => $area_en, 'area_ar' => $area_ar, 'gov_en' => $gov_en, 'gov_ar' => $gov_ar, 'block' => $block];
            } else {
                return [];
            }
            
        }
    }

}

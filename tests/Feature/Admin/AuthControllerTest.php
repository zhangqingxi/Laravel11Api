<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\Admin;
use App\Services\AesEncryptionService;
use App\Services\RsaEncryptionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Random\RandomException;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{


    /**
     * 测试登录成功
     * @作者 Qasim
     * @日期 2023/6/28
     * @return void
     * @throws RandomException
     * @throws \Exception
     */
    public function testLoginSuccess(): void
    {


        $data = [
            'account' => 'admin',
            'password' => '123456789',
        ];

        $aesService = new AesEncryptionService();

        $aesKey = $aesService->generateAesKey();

        $encryptData = $aesService->encrypt(json_encode($data), $aesKey);

        //加密请求aesKey
        $rsaService = new RsaEncryptionService();

        $rsaService->setKeys('admin');

        $encryptAesKey = $rsaService->encrypt($aesKey);

        $headers = [
            'X-AES-KEY: '. $encryptAesKey
        ];

        $data = ['data' => $encryptData];

        dd($data, $headers);
        // 发送登录请求
        $response = $this->postJson('/admin/login', $data, headers: $headers);

        // 断言响应状态码
        $response->assertStatus(200);

        // 断言响应结构
        $response->assertJsonStructure([
            'code',
            'message',
            'data' => [
                'token',
                'user' => [
                    'id',
                    'account',
                    'nickname',
                    'email',
                    'phone',
                    'avatar',
                    'status',
                    'login_ip',
                    'last_login_at',
                    'login_attempts',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        // 断言响应数据
        $responseData = $response->json('data');
        $this->assertNotEmpty($responseData['token']);
        $this->assertEquals($this->admin->account, $responseData['user']['account']);

    }

    /**
     * 测试登录失败
     * @作者 Qasim
     * @日期 2023/6/28
     * @return void
     * @throws \Exception
     */
    public function testLogout(): void
    {
        $data = "Jx5FDGYq695AOHCl6Q6zc1FuNE1GWGhER3ZrSXNnRHo5VGNHZFBURmpWV24rOXJwVFVHZzBFSDlXR2JJNEVkY1JSNTdHVDlISU10MDRFVVdhbmh5bXZRSkRiYURNczhtUlJzanA0ODlpc0lnZmhSSFNWR1Mya09nWXNmUFJ1L1d0bERWSWgvODZadVFra3VYaDdkU1V6dTZIY1ErM0FoN1ZXVjVzS2xYQ2prTDhsV2plOE03NkZtRCs1blMyWlQ0UHp4czlSakFpSmlEUURlMDc2em44TDZXdHZVZHBmcDNqTjNicStZL1FEZkt2L0tyZWtUeTNqSHhQSnpZdlorVGM3TUpHY2JDOFgvUWhQK202TkMyNThPNFpkVWlVL3RIN010MmJHUXlLMkdUQVJYNVZtYXp5RWJEajEzbUllWHlUVm9HS0JzOWhFSFlWQytCZHRpSXVPU3lZdGpXaG1UaU9sSkRLNEdiMGFpUDRIK2tXZ0RMd2VZcWZUVE9iRkNjQi9wZSt4bVlsTXFHS0lPWUZsa04wd09CejR0bGpFcnUxaDhMVU11eEptZjJVRGRhckF5VjNLWWJRZHVGaEFsR29iNTcxbHdVdGFCZlRSMi90d2lxcmtXWDBLWUEyZnJPT1hKN2s2QTZkUDlKeHVreE91SXJqZ3dyTWRaZjQyaFhMbWZCQ1d6akttOUZSSkM1UGp6dDRZUWJUVzJVRG5BZDNVQWFrRFB0UHpsd1pWYTZmbTdrbDdCQkQrUUFxbUVZRnVkVFQ4cEpWM1Q5SGtPUjE0WktINFFzMzdiU3k4dHdWRU4wUkQ3VWVsWjFLUnh4Rm0vbVlUSWF0bnlneGE1VXZXTmhtdHFaZXlCWjFHOTdMTzBXblVwc2gyTUQzSlBzYUxVd0JkUC9pSWE4VWVYdEZQNkMwSVdxbC9iYUc4U2VzVEFsZ1FHUG1RbDRickZU";

        $aesKey = "f969dd1cabe78839080327ae59c41e71";

        $encryptAesKey1 = "w3E87Dy5KoTIDdppTDEFX7hVqB/lbuAEn1gPtvx2dImdOsWoQYcINqs9NWieLZtpCVsk/ZZdmpSDl1b3gIKHiLFzweP3WP0bk1v2XY9jszef+tUMyqrUqyblqsJK02IDHcjXfsiUlNcTx1nd2ZNVtkYjUFDY4nGT66l1PY6a2XR990VGdxFkuuKG77iWMibwMahDG6YMSXJvqtMTQY/0CRQWgVJNx2iekNPWr5QLtvV1xrDnp1GNfimyZwCFx5H6yC4XTJW5+ajCtPCW713v2ZGA2wBP4nKAnZMjy4AVJLftvAizRVPmDbbXn+RzuiHfuUgWClIH1VsSUz9joL2ttQ==";

        $aesService = new AesEncryptionService();

        $rsaService = new RsaEncryptionService();

        $rsaService->setKeys('admin');

        $this->assertEquals($aesKey, $rsaService->decrypt($encryptAesKey1));

        $data = $aesService->decrypt($data, $aesKey);

        $data = json_decode($data, true);

        // 发送错误的登录请求
        $response = $this->postJson('/admin6666/logout', [
        ], [
            "Authorization" => "Bearer " . $data['token']
        ]);


        dd($response->json());
        // 验证响应
        $response->assertStatus(401);
        $response->assertJson([
            'code' => 401,
            'message' => __('auth.failed'),
            'data' => null,
        ]);
    }

    public function testCurl()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://local.web.cn/admin6666/logout',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"data": "FAiq9LWj/qOteJ/tGAVaQml1clNoVGNCNldDL3hxcytWeFJjQXBOK2NFRnM3dGMvdUZscllWMjBsdnZDc3dxN1Q5VnJQeXY3aDJlTVd2WVM="}',
            CURLOPT_HTTPHEADER => array(
                'X-AES-KEY: w3E87Dy5KoTIDdppTDEFX7hVqB/lbuAEn1gPtvx2dImdOsWoQYcINqs9NWieLZtpCVsk/ZZdmpSDl1b3gIKHiLFzweP3WP0bk1v2XY9jszef+tUMyqrUqyblqsJK02IDHcjXfsiUlNcTx1nd2ZNVtkYjUFDY4nGT66l1PY6a2XR990VGdxFkuuKG77iWMibwMahDG6YMSXJvqtMTQY/0CRQWgVJNx2iekNPWr5QLtvV1xrDnp1GNfimyZwCFx5H6yC4XTJW5+ajCtPCW713v2ZGA2wBP4nKAnZMjy4AVJLftvAizRVPmDbbXn+RzuiHfuUgWClIH1VsSUz9joL2ttQ==',
                'Authorization: Bearer 63|gMs6oFzlgFhT3XZQWP0wg190ezg3jUydbyQFpLcZ7d2af474',
                'User-Agent: Apifox/1.0.0 (https://apifox.com)',
                'Content-Type: application/json',
                'Accept: */*',
                'Host: local.web.cn',
                'Connection: keep-alive'
            ),
        ));

        $response = curl_exec($curl);

        dd($response);
        curl_close($curl);
        echo $response;
    }
}

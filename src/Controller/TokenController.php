<?php

namespace App\Controller;

use App\Security\GoogleApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    /**
     * @TODO POST
     * @Route("/connect/google/api-token", name="connect_google_api_token")
     */
    public function getApiToken(Request $request, GoogleApiClient $client)
    {
        $email = '';

        $a = $request->getScheme();
        // @TODO check for https
        $b = $request->headers->all();

        $client->setRedirectUri($this->generateUrl('connect_google_api_token', [], 0))
            ->addScope("email")
            ->addScope("profile");

        $code = $request->query->get('code');

        if ($code) {
            try {
                $client->setAccessTokenWithAuthCode($code);
                $googleUser = $client->getUserInfo();
                $email = $googleUser->email;

                // @TODO THIS IS JUST A TEST!!!

                // user = userRepo->getByEmail($email)
            } catch (\Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        $idToken = $request->query->get('id_token');

        if ($idToken) {
            try {
                $idToken = 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImIxNmRlMWIyYWIwYzE2YWMwYWNmNjYyZWYwMWY3NTY3ZTU0NDI1MmEiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIzOTg4Nzc2NzI1MzItZTMyMjB2Z2UxYzdkdGoxdHFhbzlxZ2RlZjNmdjY0NmouYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiIzOTg4Nzc2NzI1MzItYnZkNnJ1MTZkNTFlZ2Vhdm01NThwcWNwNTA5NzV1ODkuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDQ0MjA3NjM4MzY4MDUwNTkwNjIiLCJlbWFpbCI6ImVsa3VrdS5uN0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmFtZSI6Im5pa3AzaCIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0vQU9oMTRHZzloelo4cDk1WW1xR1kydUktVmpTZ3d6MHh1bzc4Nkl1U2Y3VFk9czk2LWMiLCJnaXZlbl9uYW1lIjoibmlrcDNoIiwibG9jYWxlIjoiZXMtNDE5IiwiaWF0IjoxNTkyMTU2NTc4LCJleHAiOjE1OTIxNjAxNzh9.ubORYivds99tnQ-63Jj1ul3OmM30HKnvCVjHg30jfnpSXFHxnSh-RnN59KqZfK_nkNm7w8NXNqDNFVF6thKNj8YD5vN1M3GGgnSoOVgKppFq2QtL-uCO1Ux8_6vH6On_m40TXZQdtzFEiIAc1GTecG1KlJ9_Veo_0nlUtevdoD5IDHpotVJCc5fHABewVLFVuT8tNsL0-hzcGxhs6hgMWnwGs3bWANNzqmqGFJZy5AdqQIneoNrFk4ISlFNDGhu-WGCAc8jIj-mZ9ZgZ16Co6lSRY1_ogQZLm-DMcUY9NUWBscshr_sK1vGZ7DhDLjLYsbObnS2bLO2lv-gjmD-yOg';
                $idToken = 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImIxNmRlMWIyYWIwYzE2YWMwYWNmNjYyZWYwMWY3NTY3ZTU0NDI1MmEiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIzOTg4Nzc2NzI1MzItZTMyMjB2Z2UxYzdkdGoxdHFhbzlxZ2RlZjNmdjY0NmouYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiIzOTg4Nzc2NzI1MzItYnZkNnJ1MTZkNTFlZ2Vhdm01NThwcWNwNTA5NzV1ODkuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDk1MjgzNTc5ODU1NDAwMTM2NTciLCJlbWFpbCI6ImVsa3VrdS5zNEBnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmFtZSI6IkVsIEt1a3UiLCJwaWN0dXJlIjoiaHR0cHM6Ly9saDYuZ29vZ2xldXNlcmNvbnRlbnQuY29tLy1CRVhuQW1pU0F4WS9BQUFBQUFBQUFBSS9BQUFBQUFBQUFBQS9BTVp1dWNtOFlQc3NVamhQYTNNMjFNZmVyb3pxTFFnZ0RnL3M5Ni1jL3Bob3RvLmpwZyIsImdpdmVuX25hbWUiOiJFbCIsImZhbWlseV9uYW1lIjoiS3VrdSIsImxvY2FsZSI6ImVuIiwiaWF0IjoxNTkyMTY1MDM0LCJleHAiOjE1OTIxNjg2MzR9.fAR0dpwpDcucSsO5DkLV5Q8GeFjl5HgQh0R7Ed-kpyILxR4P_BWVQChQR3C2y8x8jDxo-o7gSMboXDa5Xj4xHhqEJ8sUCh3JISq0S_0gn5keF4ShVuW4Sv7373XoGRp6O7pnMacyK74NePf6kB_TGrVxhOPI4tSWGjbvdvV2JJXceGjz78N2atU23lZLn7c2veSVQZENzDSAgnBw0xEwkBXXeCDM5-uojuDN9yPRm4oRcpxdj6INqZhxT2zHKAg8wkbkn-1g_9WRe48cDsDKNNq0YDkaD1Mrx2RaDeRlcR_OrGNtnlOfLGC5mgU8kW6xjUiczx280H_KO-CvMtSoMw';
                $email = $client->fetchEmailWithToken($idToken);
                // user = userRepo->getByEmail($email)
                return $this->json(['token' => 'T0KEN_f0r_'.$email]);
            } catch (\Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render(
            'test/tokentest.html.twig',
            [
                'google_login_url' => $client->createAuthUrl(),
                'email' => $email
            ]
        );
    }
}

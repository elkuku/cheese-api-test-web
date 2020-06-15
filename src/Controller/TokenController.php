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
                $email = $client->fetchEmailWithToken($idToken);
                // user = userRepo->getByEmail($email)
                return $this->json(['token' => 'T0KEN_f0r_'.$email]);
            } catch (\Exception $exception) {
                return $this->json(['error' => $exception->getMessage()]);
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

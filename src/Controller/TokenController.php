<?php

namespace App\Controller;

use App\Repository\UserRepository;
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
    public function getApiToken(Request $request, GoogleApiClient $client, UserRepository $userRepository)
    {
        if ('https' !== $request->getScheme()) {
            // WTF!!!
            // return $this->json(['error' => 'Scheme not allowed - please use SSL!'.$request->getScheme()], 200);
        }

        $email = '';

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

        // @TODO POSTPOSTPOST...
        $idToken = $request->query->get('idtoken');

        if (!$idToken) {
            $idToken = $request->request->get('idtoken');
        }

        if ($idToken) {
            try {
                $email = $client->fetchEmailWithToken($idToken);

                $user = $userRepository->findOneBy(['email' => $email]);

                if (!$user) {
                    throw new \RuntimeException('User not found!');
                }

                if (!$this->isGranted('ROLE_X', $user)) {
                    // @TODO check agent access
                    throw new \RuntimeException('User not permitted!');
                }

                $apiToken = $user->getApiToken();

                if (!$apiToken) {
                    $apiToken = $userRepository->refreshApiToken($user);
                }

                return $this->json(['token' => $apiToken]);
            } catch (\Exception $exception) {
                return $this->json(['error' => $exception->getMessage()], 401);
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render(
            'test/tokentest.html.twig',
            [
                'google_login_url' => $client->createAuthUrl(),
                'email'            => $email,
            ]
        );
    }
}

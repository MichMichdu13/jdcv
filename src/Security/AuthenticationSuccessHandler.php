<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as BaseAuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AuthenticationSuccessHandler extends \Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler

{
    protected $serializer;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        SerializerInterface $serializer,
        $cookieProviders = [],
        bool $removeTokenFromBodyWhenCookiesUsed = true,

    ) {
        parent::__construct($jwtManager, $dispatcher, $cookieProviders, $removeTokenFromBodyWhenCookiesUsed);
        $this->serializer = $serializer;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token ): Response
    {
        $response = $this->handleAuthenticationSuccess($token->getUser());

        if ($response instanceof JsonResponse) {
            $data = json_decode($response->getContent(), true);
            $data['profile'] = json_decode($this->serializer->serialize($token->getUser(), 'json',['groups' => 'getProfile']), true);
            $response->setData($data);
        }

        return $response;
    }


    // the rest of your custom logic here
}

<?php


namespace Grocy\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

use Grocy\Services\SessionService;

class SessionAuthMiddleware extends AuthMiddleware
{
    public function __construct(\DI\Container $container, ResponseFactoryInterface $responseFactory)
    {
        parent::__construct($container, $responseFactory);
        $this->SessionCookieName = $this->AppContainer->get('LoginControllerInstance')->GetSessionCookieName();
    }

    protected $SessionCookieName;

    function authenticate(Request $request)
    {
        if (!defined('GROCY_SHOW_AUTH_VIEWS'))
        {
            define('GROCY_SHOW_AUTH_VIEWS', true);
        }

        $sessionService = SessionService::getInstance();
        if (!isset($_COOKIE[$this->SessionCookieName]) || !$sessionService->IsValidSession($_COOKIE[$this->SessionCookieName]))
        {
            return null;
        }
        else
        {
            return $sessionService->GetUserBySessionKey($_COOKIE[$this->SessionCookieName]);
        }
    }
}

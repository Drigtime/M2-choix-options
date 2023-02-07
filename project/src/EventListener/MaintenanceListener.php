<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class MaintenanceListener implements EventSubscriberInterface
{
    private bool $maintenance;
    private RouterInterface $router;

    public function __construct(bool $maintenance, RouterInterface $router)
    {
        $this->maintenance = $maintenance;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($this->maintenance && $request->attributes->get('_route') !== 'maintenance') {
            if (!$this->isAuthorized($request->getSession()->get('user'))) {
                $event->setResponse(new RedirectResponse($this->router->generate('maintenance')));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    private function isAuthorized($user): bool
    {
        if (null === $user) {
            return false;
        }
        return $user->hasRole('ROLE_ADMIN');
    }
}
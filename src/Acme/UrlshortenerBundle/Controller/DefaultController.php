<?php

namespace Acme\UrlshortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Acme\UrlshortenerBundle\Entity\Url;
use Symfony\Component\Validator\Constraints\Url as UrlConstraint;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $username = $this->get('security.context')->getToken()->getUser()->getUsername();

        $urls    = $em->getRepository('AcmeUrlshortenerBundle:Url')->findBy(['user' => $username], ['id' => 'DESC']);
        $history = [];

        foreach ($urls as $url) {
            $history[] = [
                'date'         => $url->getCreateDatetime()->format('Y-m-d H:i:s'),
                'original_url' => $url->getOriginalUrl(),
                'short_url'    => $this->get('urlshortener.service')->generateCode($url->getId())
            ];
        }

        return $this->render('AcmeUrlshortenerBundle:Default:index.html.twig', [
            'history'    => $history,
            'server_url' => $this->container->getParameter('server_url')
        ]);

        return $this->render('AcmeUrlshortenerBundle:Default:index.html.twig');
    }

    public function shortUrlAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $source_url = $request->request->get('url');
        $username   = $this->get('security.context')->getToken()->getUser()->getUsername();

        $redirect = $this->redirect($this->generateUrl('acme_urlshortener_homepage'));

        if (!$source_url || !$username) {
            $this->get('session')->getFlashBag()->add('error', 'Не указан url или вы не авторизованы');
            return $redirect;
        }

        $urlConstraint = new UrlConstraint();
        $errorList = $this->get('validator')->validateValue($source_url, $urlConstraint);

        if (count($errorList) !== 0) {
            $this->get('session')->getFlashBag()->add('error', 'URL не валиден');
            return $redirect;
        }

        $url = new Url();
        $url->setOriginalUrl($source_url);
        $url->setUser($username);

        $em->persist($url);
        $em->flush();

        return $redirect;

    }

    public function decodeAction($code)
    {
        $urlId = $this->get('urlshortener.service')->decode($code);

        $em = $this->getDoctrine()->getManager();

        $url = $em->getRepository('AcmeUrlshortenerBundle:Url')->find($urlId);

        if ($url === NULL) {
            return $this->redirect($this->generateUrl('acme_urlshortener_homepage'));
        } else {
            return $this->redirect($url->getOriginalUrl());
        }
    }
}

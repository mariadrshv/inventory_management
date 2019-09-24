<?php

namespace App\Controller;

use App\Entity\Requests;
use App\Form\RequestsType;
use App\Repository\RequestsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/requests")
 */
class RequestsController extends AbstractController
{
    /**
     * @Route("/", name="requests_index", methods={"GET"})
     * @param RequestsRepository $requestsRepository
     * @return Response
     */
    public function index(RequestsRepository $requestsRepository): Response
    {
        return $this->render('requests/index.html.twig', [
            'requests' => $requestsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="requests_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $request = new Requests();
        $form = $this->createForm(RequestsType::class, $request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($request);
            $entityManager->flush();

            return $this->redirectToRoute('requests_index');
        }

        return $this->render('requests/new.html.twig', [
            'request' => $request,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="requests_show", methods={"GET"})
     * @param Requests $request
     * @return Response
     */
    public function show(Requests $request): Response
    {
        return $this->render('requests/show.html.twig', [
            'request' => $request,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="requests_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Requests $requests
     * @return Response
     */
    public function edit(Request $request, Requests $requests): Response
    {
        $form = $this->createForm(RequestsType::class, $request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('requests_index');
        }

        return $this->render('requests/edit.html.twig', [
            'request' => $request,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="requests_delete", methods={"DELETE"})
     * @param Request $request
     * @param Requests $requests
     * @return Response
     */
    public function delete(Request $request, Requests $requests): Response
    {
        if ($this->isCsrfTokenValid('delete'.$request->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($request);
            $entityManager->flush();
        }

        return $this->redirectToRoute('requests_index');
    }
}

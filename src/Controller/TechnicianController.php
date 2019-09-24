<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Technician;
use App\Form\TechnicianType;
use App\Repository\TechnicianRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/technician")
 * Class TechnicianController
 * @package App\Controller
 */
class TechnicianController extends AbstractController
{
    /**
     * @Route("/", name="technician_index", methods={"GET"})
     * @param Request $request
     * @param TechnicianRepository $technicianRepository
     * @return Response
     */
    public function index(Request $request, TechnicianRepository $technicianRepository): Response
    {
        $field = $request->query->get('field', 'id');
        $order = $request->query->get('order', 'asc');
        return $this->render('technician/index.html.twig', [
            'technicians' => $technicianRepository->findBy(
                array(),
                array($field => $order)
            ),
        ]);
    }

    /**
     * @param Request $request
     * @param TechnicianRepository $technicianRepository
     * @return Response
     * @Route("/search_tech", name="search_tech", methods={"GET","POST"})
     */
    public function search (Request $request, TechnicianRepository $technicianRepository): Response
    {
        if ($request->request->get('search')) {
            $search = $request->request->get('search');
            $technicians = $technicianRepository->findBy([
                'name' => $search,
            ]);

            return $this->render('technician/index.html.twig', [
                'technicians' => $technicians,
            ]);
        }
    }

    /**
     * @Route("/new", name="technician_new", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $technician = new Technician();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(TechnicianType::class, $technician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photo = new Photos();
                $photoFileName = $fileUploader->upload($photoFile);
                $photo->setPath($photoFileName);
                $photo->setTechnician($technician);
                $technician->setPhoto($photo);
                $entityManager->persist($photo);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($technician);
            $entityManager->flush();

            return $this->redirectToRoute('technician_index');
        }

        return $this->render('technician/new.html.twig', [
            'technician' => $technician,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="technician_show", methods={"GET"})
     * @param Technician $technician
     * @return Response
     */
    public function show(Technician $technician): Response
    {
        return $this->render('technician/show.html.twig', [
            'technician' => $technician,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="technician_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Technician $technician
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function edit(Request $request, Technician $technician, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(TechnicianType::class, $technician);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photo = new Photos();
                $photoFileName = $fileUploader->upload($photoFile);
                $photo->setPath($photoFileName);
                $photo->setTechnician($technician);
                $technician->setPhoto($photo);
                $entityManager->persist($photo);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('technician_index');
        }

        return $this->render('technician/edit.html.twig', [
            'technician' => $technician,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="technician_delete", methods={"DELETE"})
     * @param Request $request
     * @param Technician $technician
     * @return Response
     */
    public function delete(Request $request, Technician $technician): Response
    {
        if ($this->isCsrfTokenValid('delete'.$technician->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($technician);
            $entityManager->flush();
        }

        return $this->redirectToRoute('technician_index');
    }

    /**
     * @Route("/{id}/change", name="technician_change", methods={"GET","POST"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function change($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $technician = $entityManager->getRepository(Technician::class)->find($id);
        if ($technician->getStatus() == false) {
            $technician->setStatus('1');
        } else {
            $technician->setStatus('0');
        }
        $entityManager->flush();
        return $this->redirectToRoute('technician_index');
    }
}

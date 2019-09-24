<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

/**
 * @Route("/property")
 * Class PropertyController
 * @package App\Controller
 */
class PropertyController extends AbstractController
{
    /**
     * @param PropertyRepository $propertyRepository
     * @return Response
     * @Route("/", name="property_index", methods={"GET"})
     */
    public function index(PropertyRepository $propertyRepository): Response
    {
        if ($user = $this->getUser()) {
            $userProperties = $propertyRepository->findBy(['user_id' => $user->getId()]);
            return $this->render('property/index.html.twig', ['properties' => $userProperties]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/new", name="property_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        if ($user = $this->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();
            $property = new Property();
            $property->setUserId($user);
            $form = $this->createForm(PropertyType::class, $property);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $zip = $form->get('zip')->getData();
                $formZip = preg_replace("/[^0-9]/", "", $zip);
                $property->setZip($formZip);
                $phone = $form->get('phone_number')->getData();
                $formPhone =  preg_replace("/[^0-9]/", "", $phone);
                $property->setPhoneNumber($formPhone);
                $photoFile = $form['photo']->getData();
                if ($photoFile) {
                    $photo = new Photos();
                    $photoFileName = $fileUploader->upload($photoFile);
                    $photo->setPath($photoFileName);
                    $photo->addProperty($property);
                    $property->addPhoto($photo);
                    $entityManager->persist($photo);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($property);
                $entityManager->flush();

                return $this->redirectToRoute('property_index');
            }
            return $this->render('property/new.html.twig', [
                'property' => $property,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }

    }

    /**
     * @param Property $property
     * @return Response
     * @Route("/{id}", name="property_show", methods={"GET"})
     */
    public function show(Property $property): Response
    {
        $this->denyAccessUnlessGranted('view', $property);
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'rooms' => $property->getRooms(),
            'warranties' =>$property->getWarranties(),
            'photo' => $property->getPhotos()->first()->getPath()
        ]);
    }

    /**
     * @param Request $request
     * @param Property $property
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/{id}/edit", name="property_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Property $property, FileUploader $fileUploader): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->denyAccessUnlessGranted('edit', $property);
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $zip = $form->get('zip')->getData();
            $formZip =  preg_replace("/[^0-9]/", "", $zip);
            $property->setZip($formZip);
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photo = new Photos();
                $photoFileName = $fileUploader->upload($photoFile);
                $photo->setPath($photoFileName);
                $photo->addProperty($property);
                $property->addPhoto($photo);
                $entityManager->persist($photo);
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('property_index');
        }
        return $this->render('property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Property $property
     * @return Response
     * @Route("/{id}", name="property_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Property $property): Response
    {
        $this->denyAccessUnlessGranted('delete', $property);
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($property);
            $entityManager->flush();
        }
        return $this->redirectToRoute('property_index');
    }
}

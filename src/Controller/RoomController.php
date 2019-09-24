<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Property;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RoomController
 * @package App\Controller
 * @Route("/property/{prop_id}/room", requirements={"prop_id"="\d+"})
 * @ParamConverter("property", class="App\Entity\Property", options={"id" = "prop_id"})
 */
class RoomController extends AbstractController
{
    /**
     * @param Property $property
     * @return Response
     * @Route("/", name="room_index", methods={"GET"})
     */
    public function index(Property $property): Response
    {
        if ($user = $this->getUser()) {
            return $this->render('room/index.html.twig', [
                'rooms' => $property->getRooms(),
                'property' => $property]);
        } else {
            return $this->redirectToRoute('app_login');
        }

    }

    /**
     * @param Room $room
     * @param Property $property
     * @param RoomRepository $roomRepository
     * @return Response
     * @Route("/{id}", name="room_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Room $room, Property $property, RoomRepository $roomRepository): Response
    {
        $this->denyAccessUnlessGranted('view', $room);
        if ($roomRepository->findOneBy(['property' => $property])){
            return $this->render('room/show.html.twig', [
                'room' => $room,
                'property' => $property,
                'items' => $room->getItems(),
                'prop_id' => $property->getId(),
                'photo' => $room->getPhotos()->first()->getPath()
            ]);
        }
        throw new \LogicException('Wrong connection between room and property, try again');
    }

    /**
     * @param Property $property
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/new", name="room_new", methods={"GET","POST"})
     */
    public function new(Property $property, Request $request, FileUploader $fileUploader): Response
    {
        $room = new Room;
        $room->setProperty($property);
        $this->denyAccessUnlessGranted('new', $property);
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photo = new Photos();
                $photoFileName = $fileUploader->upload($photoFile);
                $photo->setPath($photoFileName);
                $photo->addRoom($room);
                $room->addPhoto($photo);
                $entityManager->persist($photo);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('room_index', [
                'prop_id' => $property->getId(),
            ]);
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
            'property' => $property,
        ]);
     }

    /**
     * @param Request $request
     * @param Room $room
     * @param Property $property
     * @param FileUploader $fileUploader
     * @param RoomRepository $roomRepository
     * @return Response
     * @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
     */
    public function edit(
        Request $request, Room $room, Property $property,
        FileUploader $fileUploader, RoomRepository $roomRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->denyAccessUnlessGranted('edit', $room);
        if ($roomRepository->findOneBy(['property' => $property])){
            $form = $this->createForm(RoomType::class, $room);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $photoFile = $form['photo']->getData();
                if ($photoFile) {
                    $photo = new Photos();
                    $photoFileName = $fileUploader->upload($photoFile);
                    $photo->setPath($photoFileName);
                    $photo->addRoom($room);
                    $room->addPhoto($photo);
                    $entityManager->persist($photo);
                }
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('room_index', [
                    'room' => $room,
                    'prop_id' => $property->getId(),
                ]);
            }
            return $this->render('room/edit.html.twig', [
                'room' => $room,
                'form' => $form->createView(),
                'property' => $property,
            ]);
        }
        throw new \LogicException('Wrong connection between room and property, try again');
    }

    /**
     * @param Request $request
     * @param Room $room
     * @param Property $property
     * @return Response
     * @Route("/{id}", name="room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Room $room, Property $property): Response
    {
        $this->denyAccessUnlessGranted('delete', $room);
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index', [
            'room' => $room,
            'property' => $property,
        ]);
    }
}

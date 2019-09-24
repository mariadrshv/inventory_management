<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Photos;
use App\Entity\Property;
use App\Entity\Room;
use App\Form\ItemType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/property/{prop_id}/room/{room_id}/item", requirements={"prop_id" = "\d+","room_id"="\d+"})
 * @ParamConverter("property", class="App\Entity\Property", options={"id" = "prop_id"})
 * @ParamConverter("room", class="App\Entity\Room", options={"id" = "room_id"})
 */
class ItemController extends AbstractController
{

    /**
     * @Route("/", name="item_index", methods={"GET"})
     * @param Room $room
     * @param Property $property
     * @return Response
     */
    public function index(Room $room, Property $property): Response
    {
        if ($user = $this->getUser()) {
            return $this->render('item/index.html.twig', [
                'items' => $room->getItems(),
                'room' => $room,
                'property' => $property,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     * @param Request $request
     * @param Room $room
     * @param Property $property
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function new(Request $request, Room $room, Property $property, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('new', $room);
            $entityManager = $this->getDoctrine()->getManager();
            $item = new Item();
            $item->setRoom($room);
            $form = $this->createForm(ItemType::class, $item);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $photoFile = $form['photo']->getData();
                if ($photoFile) {
                    $photo = new Photos();
                    $photoFileName = $fileUploader->upload($photoFile);
                    $photo->setPath($photoFileName);
                    $photo->addItem($item);
                    $item->addPhoto($photo);
                    $entityManager->persist($photo);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($item);
                $entityManager->flush();

                return $this->redirectToRoute('item_index', [
                    'room_id' => $room->getId(),
                    'prop_id' => $property->getId(),
                ]);
            }
            return $this->render('item/new.html.twig', [
                'item' => $item,
                'form' => $form->createView(),
                'room' => $room,
                'property' => $property,
            ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET"}, requirements={"id"="\d+"})
     * @param Item $item
     * @param Room $room
     * @param Property $property
     * @return Response
     */
    public function show(Item $item, Room $room, Property $property): Response
    {
        $this->denyAccessUnlessGranted('view', $item);
        if ($item->getRoom() === $room && $room->getProperty() == $property){
            return $this->render('item/show.html.twig', [
                'item' => $item,
                'room' => $room,
                'property' => $property,
                'photo' => $item->getPhotos()->first()->getPath()
            ]);
        } throw new \LogicException('Wrong connection between item, room and property, try again');
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Item $item
     * @param Room $room
     * @param Property $property
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function edit(Request $request, Item $item, Room $room, Property $property, FileUploader $fileUploader): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->denyAccessUnlessGranted('edit', $item);
        if ($item->getRoom() === $room && $room->getProperty() == $property){
            $form = $this->createForm(ItemType::class, $item);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $photoFile = $form['photo']->getData();
                if ($photoFile) {
                    $photo = new Photos();
                    $photoFileName = $fileUploader->upload($photoFile);
                    $photo->setPath($photoFileName);
                    $photo->addItem($item);
                    $item->addPhoto($photo);
                    $entityManager->persist($photo);
                }
                $item->setType($form['type']->getData());
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('item_index', [
                    'prop_id' => $property->getId(),
                    'room_id' => $room->getId()
                ]);
            }
            return $this->render('item/edit.html.twig', [
                'item' => $item,
                'form' => $form->createView(),
                'room' => $room,
                'property' => $property,
            ]);
        } throw new \LogicException('Wrong connection between item, room and property, try again');
    }

    /**
     * @Route("/{id}", requirements={"id":"\d+"}, name="item_delete", methods={"DELETE"})
     * @param Property $property
     * @param Room $room
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function delete(Property $property, Room $room, Request $request, Item $item): Response
    {
        $this->denyAccessUnlessGranted('delete', $item);
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }
        return $this->redirectToRoute('item_index',[
            'room_id' => $room->getId(),
            'prop_id' => $property->getId(),
        ]);
    }
}

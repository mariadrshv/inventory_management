<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Warranties;
use App\Form\WarrantiesType;
use App\Interfaces\EntityWithWarrantyInterface;
use App\Repository\WarrantiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use App\Entity\Room;
use App\Entity\Item;
use App\Service\FileUploader;

/**
 * Class WarrantiesController
 * @package App\Controller
 */
class WarrantiesController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityWithWarrantyInterface $entity
     * @param WarrantiesRepository $warrantiesRepository
     * @return Response
     * @Route("/{entity}/{entity_id}/warranties", name="warranties_list", requirements={"entity_id"="\d+"})
     */
    public function list(Request $request, EntityWithWarrantyInterface $entity, WarrantiesRepository $warrantiesRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $className = $entityManager->getClassMetadata(get_class($entity))->getName();
        $field = $request->query->get('field', 'id');
        $order = $request->query->get('order', 'asc');

        if ($className == Property::class) {
            return $this->render('warranties/index.html.twig', [
                'warranties' => $warrantiesRepository->findBy(
                    ['property' => $entity],
                    [$field => $order]
                ),
                'property'   => $entity,
                'prop_id'    => $entity->getId(),
            ]);
        } elseif ($className == Room::class) {
            return $this->render('warranties/index.html.twig', [
                'warranties' => $warrantiesRepository->findBy(
                    ['room' => $entity],
                    [$field => $order]
                ),
                'room'   => $entity,
                'room_id'    => $entity->getId(),
            ]);
        } elseif ($className == Item::class) {
            return $this->render('warranties/index.html.twig', [
                'warranties' => $warrantiesRepository->findBy(
                    ['item' => $entity],
                    [$field => $order]
                ),
                'item'   => $entity,
                'item_id'    => $entity->getId(),
            ]);
        }
    }

    /**
     * @param Request $request
     * @param EntityWithWarrantyInterface $entity
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/{entity}/{entity_id}/warranty", name="warranties_new", requirements={"entity_id":"\d+"}, methods={"GET","POST"})
     */
    public function new(Request $request, EntityWithWarrantyInterface $entity, FileUploader $fileUploader): Response
    {
        $warranty = new Warranties();
        $entityManager = $this->getDoctrine()->getManager();
        $className = $entityManager->getClassMetadata(get_class($entity))->getName();
        if ($className == Property::class) {
            $warranty->setProperty($entity);
        } elseif ($className == Room::class) {
            $warranty->setRoom($entity);
        } elseif ($className == Item::class) {
            $warranty->setItem($entity);
        }
        $form = $this->createForm(WarrantiesType::class, $warranty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photo = new Photos();
                $photoFileName = $fileUploader->upload($photoFile);
                $photo->setPath($photoFileName);
                $photo->addWarranty($warranty);
                $warranty->addPhoto($photo);
                $entityManager->persist($photo);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($warranty);
            $entityManager->flush();

            if ($className == Property::class) {
                return $this->redirectToRoute('warranties_list', [
                    'entity' => 'property',
                    'entity_id' => $entity->getId(),
                ]);
            } elseif ($className == Room::class) {
                return $this->redirectToRoute('warranties_list', [
                    'entity' => 'room',
                    'entity_id' => $entity->getId(),
                ]);
            } elseif ($className == Item::class) {
                return $this->redirectToRoute('warranties_list', [
                    'entity' => 'item',
                    'entity_id' => $entity->getId(),
                ]);
            }
        }
        return $this->render('warranties/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Warranties $warranty
     * @param EntityWithWarrantyInterface $entity
     * @return Response
     * @Route("/{entity}/{entity_id}/warrantie/{id}", name="warranties_show", methods={"GET"})
     */
    public function show(Warranties $warranty, EntityWithWarrantyInterface $entity): Response {
        $this->denyAccessUnlessGranted('view', $warranty);
        if ($warranty->getProperty() === $entity) {
            return $this->render('warranties/show.html.twig', [
                'warranty' => $warranty,
                'property' => $entity,
                'prop_id'  => $entity->getId(),
                'photo' => $warranty->getPhotos()->first()->getPath()
            ]);
        } elseif ($warranty->getRoom() === $entity) {
            return $this->render('warranties/show.html.twig', [
                'warranty' => $warranty,
                'room' => $entity,
                'room_id'  => $entity->getId(),
                'photo' => $warranty->getPhotos()->first()->getPath()
            ]);
        } elseif ($warranty->getItem() === $entity) {
            return $this->render('warranties/show.html.twig', [
                'warranty' => $warranty,
                'item' => $entity,
                'item_id'  => $entity->getId(),
                'photo' => $warranty->getPhotos()->first()->getPath()
            ]);
        } throw new \LogicException('Incorrect relationship between entities!');
    }

    /**
     * @param Request $request
     * @param Warranties $warranty
     * @param EntityWithWarrantyInterface $entity
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/{entity}/{entity_id}/warranty/{id}/edit", name="warranties_edit", requirements={"entity_id":"\d+"}, methods={"GET","POST"})
     */
    public function edit(Request $request, Warranties $warranty, EntityWithWarrantyInterface $entity, FileUploader $fileUploader
    ): Response {
        $this->denyAccessUnlessGranted('edit', $warranty);
        $form = $this->createForm(WarrantiesType::class, $warranty);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        $className = $entityManager->getClassMetadata(get_class($entity))->getName();
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photo = new Photos();
                $photoFileName = $fileUploader->upload($photoFile);
                $photo->setPath($photoFileName);
                $photo->addWarranty($warranty);
                $warranty->addPhoto($photo);
                $entityManager->persist($photo);
            }
            $this->getDoctrine()->getManager()->flush();

            $nameField = '';

            if ($className == Property::class) {
                $nameField = 'prop';
            } elseif ($className == Room::class) {
                $nameField = 'room';
            } elseif ($className == Item::class) {
                $nameField = 'item';
            }

            if ($nameField) {
                $typeField = sprintf('%s_id', $nameField);
                return $this->redirectToRoute(sprintf('warranties_show_%s', $nameField), [
                    $typeField => $entity->getId(),
                    'id'      => $warranty->getId()
                ]);
            }

        }
        return $this->render('warranties/edit.html.twig', [
            'warranty' => $warranty,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @param $id
     * @param Warranties $warranties
     * @param EntityWithWarrantyInterface $entity
     * @return Response
     * @Route("/{entity}/{entity_id}/warranty/{id}/delete", name="warranties_delete", requirements={"entity_id":"\d+"})
     */
    public function delete($id, Warranties $warranties, EntityWithWarrantyInterface $entity): Response
    {
        $this->denyAccessUnlessGranted('delete', $warranties);
        $entityManager = $this->getDoctrine()->getManager();
        $warranty = $entityManager->getRepository(Warranties::class)->find($id);
        if(!$warranty) {
            throw $this->createNotFoundException('Unable to find Maintenance entity.');
        }
        $entityManager->remove($warranty);
        $entityManager->flush();
        $className = $entityManager->getClassMetadata(get_class($entity))->getName();
        if ($className == Property::class) {
            return $this->redirectToRoute('warranties_list', [
                'entity' => 'property',
                'entity_id' => $entity->getId(),
            ]);
        } elseif ($className == Room::class) {
            return $this->redirectToRoute('warranties_list', [
                'entity' => 'room',
                'entity_id' => $entity->getId(),
            ]);
        } elseif ($className == Item::class) {
            return $this->redirectToRoute('warranties_list', [
                'entity' => 'item',
                'entity_id' => $entity->getId(),
            ]);
        }
    }
}



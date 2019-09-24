<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Entity\Photos;
use App\Form\MaintenanceType;
use App\Interfaces\EntityWithMaintenanceInterface;
use App\Service\FileUploader;
use App\Repository\MaintenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use App\Entity\Room;
use App\Entity\Item;

/**
 * Class MaintenanceController
 * @package App\Controller
 */
class MaintenanceController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityWithMaintenanceInterface $entity
     * @param MaintenanceRepository $maintenanceRepository
     * @return Response
     * @Route("/{entity}/{entity_id}/maintenance", name="maintenance_list", requirements={"entity_id"="\d+"})
     */
    public function list(Request $request, EntityWithMaintenanceInterface $entity, MaintenanceRepository $maintenanceRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $className = $entityManager->getClassMetadata(get_class($entity))->getName();
        $field = $request->query->get('field', 'id');
        $order = $request->query->get('order', 'asc');

        if ($className == Property::class) {
            return $this->render('maintenance/index.html.twig', [
                'maintenances' => $maintenanceRepository->findBy(
                    ['property' => $entity],
                    [$field => $order]
                ),
                'property'   => $entity,
                'prop_id'    => $entity->getId(),
            ]);
        } elseif ($className == Room::class) {
            return $this->render('maintenance/index.html.twig', [
                'maintenances' => $maintenanceRepository->findBy(
                    ['room' => $entity],
                    [$field => $order]
                ),
                'room'   => $entity,
                'room_id'    => $entity->getId(),
            ]);
        } elseif ($className == Item::class) {
            return $this->render('maintenance/index.html.twig', [
                'maintenances' => $maintenanceRepository->findBy(
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
     * @param EntityWithMaintenanceInterface $entity
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/{entity}/{entity_id}/maintenances/new", name="maintenance_new", requirements={"entity_id":"\d+"},
     *     methods={"GET","POST"})
     */
    public function new(Request $request, EntityWithMaintenanceInterface $entity, FileUploader $fileUploader): Response
    {
        $maintenance = new Maintenance();
        $entityManager = $this->getDoctrine()->getManager();
        $className = $entityManager->getClassMetadata(get_class($entity))->getName();
        if ($className == Property::class) {
            $maintenance->setProperty($entity);
        } elseif ($className == Room::class) {
            $maintenance->setRoom($entity);
        } elseif ($className == Item::class) {
            $maintenance->setItem($entity);
        }
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photo = new Photos();
                $photoFileName = $fileUploader->upload($photoFile);
                $photo->setPath($photoFileName);
                $photo->addMaintenance($maintenance);
                $maintenance->addPhoto($photo);
                $entityManager->persist($photo);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($maintenance);
            $entityManager->flush();

            if ($className == Property::class) {
                return $this->redirectToRoute('maintenance_list', [
                    'entity' => 'property',
                    'entity_id' => $entity->getId(),
                ]);
            } elseif ($className == Room::class) {
                return $this->redirectToRoute('maintenance_list', [
                    'entity' => 'room',
                    'entity_id' => $entity->getId(),
                ]);
            } elseif ($className == Item::class) {
                return $this->redirectToRoute('maintenance_list', [
                    'entity' => 'item',
                    'entity_id' => $entity->getId()
                ]);
            }
        }
        return $this->render('maintenance/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Maintenance $maintenance
     * @param EntityWithMaintenanceInterface $entity
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/{entity}/{entity_id}/maintenance/{id}/edit", name="maintenance_edit", requirements={"entity_id":"\d+"},
     *     methods={"GET","POST"})
     */
    public function edit(Request $request, Maintenance $maintenance, EntityWithMaintenanceInterface $entity,
        FileUploader $fileUploader
    ): Response {
        $this->denyAccessUnlessGranted('edit', $maintenance);
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        $className = $entityManager->getClassMetadata(get_class($entity))->getName();
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photo = new Photos();
                $photoFileName = $fileUploader->upload($photoFile);
                $photo->setPath($photoFileName);
                $photo->addMaintenance($maintenance);
                $maintenance->addPhoto($photo);
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
                    'id'      => $maintenance->getId()
                ]);
            }
        }
        return $this->render('maintenance/edit.html.twig', [
            'maintenance' => $maintenance,
            'form'        => $form->createView(),
        ]);
    }

    /**
     * @param Maintenance $maintenance
     * @param EntityWithMaintenanceInterface $entity
     * @return Response
     * @Route("/{entity}/{entity_id}/maintenance/{id}", name="show_maintenance", methods={"GET"})
     */
    public function show(Maintenance $maintenance, EntityWithMaintenanceInterface $entity): Response {
        $this->denyAccessUnlessGranted('view', $maintenance);
        if ($maintenance->getProperty() === $entity) {
            return $this->render('maintenance/show.html.twig', [
                'maintenance' => $maintenance,
                'property' => $entity,
                'prop_id'  => $entity->getId(),
                'photo' => $maintenance->getPhotos()->first()->getPath()
            ]);
        } elseif ($maintenance->getRoom() === $entity) {
            return $this->render('maintenance/show.html.twig', [
                'maintenance' => $maintenance,
                'room' => $entity,
                'room_id'  => $entity->getId(),
                'photo' => $maintenance->getPhotos()->first()->getPath()
            ]);
        } elseif ($maintenance->getItem() === $entity) {
            return $this->render('maintenance/show.html.twig', [
                'maintenance' => $maintenance,
                'item' => $entity,
                'item_id'  => $entity->getId(),
                'photo' => $maintenance->getPhotos()->first()->getPath()
            ]);
        } throw new \LogicException('Incorrect relationship between entities!');
    }

    /**
     * @param $id
     * @param Maintenance $maintenance
     * @param EntityWithMaintenanceInterface $entity
     * @return Response
     * @Route("/{entity}/{entity_id}/maintenance/{id}/delete", name="maintenance_delete", requirements={"entity_id":"\d+"})
     */
    public function delete($id, Maintenance $maintenance, EntityWithMaintenanceInterface $entity): Response
    {
        $this->denyAccessUnlessGranted('delete', $maintenance);
        $entityManager = $this->getDoctrine()->getManager();
        $maintenance = $entityManager->getRepository(Maintenance::class)->find($id);
        if(!$maintenance){
            throw $this->createNotFoundException('Unable to find Maintenance entity.');
        }
        $entityManager->remove($maintenance);
        $entityManager->flush();
        $className = $entityManager->getClassMetadata(get_class($entity))->getName();
        if ($className == Property::class) {
            return $this->redirectToRoute('maintenance_list', [
                'entity' => 'property',
                'entity_id' => $entity->getId(),
            ]);
        }
        elseif ($className == Room::class) {
            return $this->redirectToRoute('maintenance_list', [
                'entity' => 'room',
                'entity_id' => $entity->getId(),
            ]);
        }
        elseif ($className == Item::class) {
            return $this->redirectToRoute('maintenance_list', [
                'entity' => 'item',
                'entity_id' => $entity->getId(),
            ]);
        }
    }
}

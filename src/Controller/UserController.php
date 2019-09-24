<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\ItemRepository;
use App\Repository\PropertyRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swift_Message;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Swift_Mailer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @var ContainerBagInterface
     */
    private $param;

    /**
     * UserController constructor.
     * @param ContainerBagInterface $param
     */
    public function __construct(ContainerBagInterface $param)
    {
        $this->param = $param;
    }

    /**
     * @Route("/user", name="user_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->render('user/index.html.twig', [
                'user' =>  $this->getUser()
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }

    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        if ($user = $this->getUser()) {
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setName($form->getData()->getName());
                $user->setAddress($form->getData()->getAddress());
                $user->setPhoneNumber($form->getData()->getPhoneNumber());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $success = true;

                return $this->render('user/edit.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                    'success' => $success
                ]);
            }

            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @param Swift_Mailer $mailer
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/reset", name="user_reset", methods={"GET","POST"})
     */
    public function reset(Swift_Mailer $mailer, UserRepository $userRepository)
    {
        if ($user = $this->getUser()) {
            $unique_link = md5($user->getEmail());
            $user->setLink($unique_link);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new Swift_Message('Reset Pass'))
                ->setFrom($this->param->get('mail'))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/reset.html.twig',
                        ['name' => $user->getUsername(),
                         'unique_link' => $unique_link
                        ]
                    ),
                    'text/html'
                );

            $success = $mailer->send($message);

            return $this->render('user/index.html.twig', [
                'success' => $success,
                'users' => $userRepository->findOneBy(['email' => $user->getUsername()])
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @param $unique_link
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/user/newpass/{unique_link}")
     */
    public function newPass($unique_link, UserRepository $userRepository): Response
    {
        if ($user = $userRepository->findOneBy(['link' => $unique_link])) {
            $user->setLink(''); //maybe delete from there
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('user/reset.html.twig');
        } else {
            throw $this->createNotFoundException('Not right link to activate account!');
        }

    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @Route("/getpass", name="getpass", methods={"GET","POST"})
     */
    public function getPass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->request->get('password') == $request->request->get('repeat_password')){
            $user = $this->getUser();
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $success = true;
            return $this->render('user/reset.html.twig', ['success' => $success]);
        } else {
            $mistake = true;
            return $this->render('user/reset.html.twig', ['mistake' => $mistake]);
        }
    }

    /**
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/change", name="user_change", methods={"GET","POST"})
     */
    public function change(UserRepository $userRepository)
    {
        if ($user = $this->getUser()) {
            $success = false;
            $unique_link = md5($user->getEmail());
            $user->setLink($unique_link);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('user/changemail.html.twig', [
                'success' => $success,
                'users' => $userRepository->findOneBy(['email' => $user->getUsername()])
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }

    }

    /**
     * @param UserRepository $userRepository
     * @param Swift_Mailer $mailer
     * @param Request $request
     * @return Response
     * @Route("/maillink", name="maillink", methods={"GET","POST"})
     */
    public function mailLink(UserRepository $userRepository, Swift_Mailer $mailer, Request $request)
    {
        if ($user = $this->getUser()) {
            $user->setTemporarymail($request->request->get('email'));
            $unique_link = md5($request->request->get('email'));
            $user->setLink($unique_link);
            $message = (new Swift_Message('Confirm email'))
                ->setFrom($this->param->get('mail'))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/changemail.html.twig',
                        ['name' => $user->getUsername(),
                         'unique_link' => $unique_link
                        ]
                    ),
                    'text/html'
                );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $success = $mailer->send($message);

            return $this->render('user/changemail.html.twig', [
                'success' => $success,
                'users' => $userRepository->findOneBy(['email' => $user->getUsername()])
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/change/mail/{unique_link}")
     * @param $unique_link
     * @param UserRepository $userRepository
     * @return Response
     */
    public function confirm($unique_link, UserRepository $userRepository):Response
    {
        if ($user = $userRepository->findOneBy(['link' => $unique_link])) {
            $user->setEmail($this->getUser()->getTemporaryMail());
            $user->setTemporarymail('');
            $user->setLink('');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('user/index.html.twig', [
                'successemail' => true,
                'users' => $userRepository->findOneBy(['email' => $user->getUsername()])
            ]);
        } else {
            throw $this->createNotFoundException('Not right link to confirm email!');
        }
    }

    /**
     * @param Request $request
     * @param PropertyRepository $propertyRepository
     * @param RoomRepository $roomRepository
     * @param ItemRepository $itemRepository
     * @return Response
     * @Route("/user/db_search", name="search", methods={"GET","POST"})
     */
    public function search (Request $request, PropertyRepository $propertyRepository, RoomRepository $roomRepository, ItemRepository $itemRepository)
    {
        if ($request->request->get('search')) {
            $search = $request->request->get('search');
            $property = $propertyRepository->findOneBy(['name' => $search]);
            $room = $roomRepository->findOneBy(['name' => $search]);
            $item = $itemRepository->findOneBy(['name' => $search]);
            return $this->render('user/index.html.twig', [
                'user'     => $this->getUser(),
                'property' => $property,
                'room'     => $room,
                'item'     => $item
            ]);
        } else {
            return $this->render('user/index.html.twig', ['user' =>  $this->getUser()]);
        }
    }
}

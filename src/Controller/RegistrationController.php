<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Swift_Message;
use Swift_Mailer;

/**
 * Class RegistrationController
 * @package App\Controller
 */

class RegistrationController extends AbstractController
{
    private $param;
    /**
     * RegistrationController constructor.
     * @param ContainerBagInterface $param
     */
    public function __construct(ContainerBagInterface $param)
    {
        $this->param = $param;
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param \Swift_Mailer $mailer
     * @return Response
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        Swift_Mailer $mailer) : Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $success = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles([User::DEFAULT_ROLE]);

            $unique_link = md5($user->getEmail());

            $user->setLink($unique_link);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new Swift_Message('Hello Email'))
                ->setFrom($this->param->get('mail'))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        ['name' => $user->getUsername(),
                         'unique_link' => $unique_link
                        ]
                    ),
                    'text/html'
                );
            $success = $mailer->send($message);
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/email/verif/{unique_link}")
     * @param UserRepository $userRepository
     * @param $unique_link
     * @return string
     */
    public function link(UserRepository $userRepository, $unique_link)
    {
        if ($user = $userRepository->findOneBy(['link' => $unique_link])) {
            $user->setStatus('active');
            $user->setLink('');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        } else {
            throw $this->createNotFoundException('Not right link to activate account!');
        }
    }

}

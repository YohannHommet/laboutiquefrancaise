<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AccountPasswordController extends AbstractController
{

    /**
     * @Route("/account/password-update", name="app_account_password")
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function passwordUpdate(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        /** @var \App\Entity\User|$user */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($encoder->isPasswordValid($user, $form['old_password']->getData())) {
                $user->setPassword($encoder->encodePassword($user, $form['new_password']->getData()));
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('info', 'Your password have been updated');
            } else {
                $this->addFlash('danger', 'Error detected !');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

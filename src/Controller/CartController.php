<?php

namespace App\Controller;

use App\Classe\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/cart", name="app_cart")
     *
     * @param \App\Classe\Cart $cart
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Cart $cart): Response
    {
        if (!$cart->get()) {
            $this->addFlash('info', "Your cart is empty.");
            return $this->redirectToRoute('app_products');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
        ]);
    }

    /**
     * @Route("/cart/delete", name="app_cart_delete")
     *
     * @param \App\Classe\Cart $cart
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Cart $cart): Response
    {
        $cart->delete();
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/cart/add/{id}", name="app_cart_add")
     *
     * @param                  $id
     * @param \App\Classe\Cart $cart
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add($id, Cart $cart): Response
    {
        $cart->add($id);
        $this->addFlash("success", "Your product has been added to the cart");

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/remove/{id}", name="app_cart_remove")
     * @param \App\Classe\Cart $cart
     * @param                  $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(Cart $cart, $id): Response
    {
        $cart->remove($id);
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/decrease/{id}", name="app_cart_decrease")
     * @param \App\Classe\Cart $cart
     * @param                  $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('app_cart');
    }




}

<?php


namespace App\Classe;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class Cart
{
    private SessionInterface $session;

    private EntityManagerInterface $em;


    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }


    public function get()
    {
        return $this->session->get('cart');
    }

    public function getCart()
    {
        $cartComplete = [];

        if ($this->get()) {
            foreach((array)$this->get() as $id => $quantity) {
                $productObject = $this->em->getRepository(Product::class)->findOneBy(['id' => $id]);

                if (!$productObject) {
                    $this->remove($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $productObject,
                    'quantity' => $quantity
                ];
            }
        }


        return $cartComplete;
    }

    public function add(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function remove($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }

    public function delete()
    {
        return $this->session->remove('cart');
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }
        return $this->session->set('cart', $cart);
    }


}
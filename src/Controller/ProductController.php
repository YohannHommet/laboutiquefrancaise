<?php

namespace App\Controller;


use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/products", name="app_products")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request): Response
    {
        $search = new Search();
        $searchForm = $this->createForm(SearchType::class, $search);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $products = $this->em->getRepository(Product::class)->findWithSearch($search);
        } else {
            $products = $this->em->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'searchForm' => $searchForm->createView()
        ]);
    }


    /**
     * @Route("/product/{slug}", name="app_product_show")
     *
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($slug): Response
    {
        // On peut aussi faire : ->findOneBySlug($slug)
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        if (!$product) {
            $this->addFlash('warning', "This product does not exist");
            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}

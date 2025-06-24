<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductForm;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index')]
    public function index(ProductRepository $repository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $repository->findAll(),
        ]);
    }

    #[Route('/products/{id<\d+>}', name: 'product_item')]
    public function item(Product $product): Response
    {
        return $this->render('product/item.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/products/new', name: 'product_new')]
    public function new(): Response
    {
        $form = $this->createForm(ProductForm::class);

        return $this->render('product/new.html.twig', [
            "form" => $form,
        ]);
    }
}

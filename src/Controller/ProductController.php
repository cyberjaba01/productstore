<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index')]
    public function index(ProductRepository $repository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $repository->findAll(),
        ]);
    }

    #[Route('/products/{id<\d+>}')]
    public function showItem($id, ProductRepository $repository): Response
    {
        return $this->render('product/item.html.twig', [
            'product' => $repository->find($id) ?: throw $this->createNotFoundException('Product not found'),
        ]);
    }
}

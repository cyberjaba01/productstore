<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product;

        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($product);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Product created succesfully!'
            );

            return $this->redirectToRoute('product_item', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/new.html.twig', [
            "form" => $form,
        ]);
    }


    #[Route('/products/{id<\d+>}/edit', name: 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash(
                'notice',
                'Product updated succesfully!'
            );

            return $this->redirectToRoute('product_item', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/edit.html.twig', [
            "form" => $form,
        ]);
    }

    #[Route('/products/{id<\d+>}/delete', name: 'product_delete')]
    public function delete(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        if ($request->isMethod('POST')) {
            $manager->remove($product);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Product deleted succesfully!'
            );

            return $this->redirectToRoute('product_index');
        }


        return $this->render('product/delete.html.twig', [
            "id" => $product->getId(),
        ]);
    }
}

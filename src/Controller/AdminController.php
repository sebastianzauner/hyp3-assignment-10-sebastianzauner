<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    #[Route("/admin/orders", name:"admin_orders")]
    public function ordersAction()
    {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route("/admin/orders/edit/{id}", name:"admin_order_edit")]
    public function editOrderAction(Order $order, Request $request)
    {
        $form = $this->createFormBuilder($order)
            ->add('name', TextType::class)
            ->add('address', TextareaType::class)
            ->add('phone', TextType::class)
            ->add('email', EmailType::class)
            ->add('size', ChoiceType::class, [
                'choices'  => [
                    'Small' => 'small',
                    'Medium' => 'medium',
                    'Large' => 'large',
                    'Extra Large' => 'x-large',
                ],
            ])
            ->add('ingredients', ChoiceType::class, [
                'choices'  => [
                    'Cheese' => 'cheese',
                    'Salami' => 'salami',
                    'Peperoni' => 'peperoni',
                    'Mushrooms' => 'mushrooms',
                    'Onions' => 'onions',
                    'Tuna' => 'tuna',
                    'Garlic' => 'garlic',
                    'Corn' => 'corn',
                    'Artichokes' => 'artichokes',
                    'Gorgonzola' => 'gorgonzola',
                    'Olives' => 'olives',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('delivery', ChoiceType::class, [
                'choices'  => [
                    'Delivery' => 'delivery',
                    'Pickup' => 'pickup',
                ],
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Save Changes'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('admin_orders');
        }

        return $this->render('admin/edit_order.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/admin/orders/delete/{id}", name:"admin_order_delete")]
    public function deleteOrderAction(Order $order)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        return $this->redirectToRoute('admin_orders');
    }
}


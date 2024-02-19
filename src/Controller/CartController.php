<?php

namespace App\Controller;

use App\Service\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * Récupère un panier détaillé contenant des objets Products et les totaux de quantité et de prix 
     * 
     * @param Cart $cart
     * @return Response
     */
    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {
        $cartCreneaux = $cart->getDetails();

        return $this->render('cart/cart.html.twig', [
            'cart' => $cartCreneaux['creneaux'],
            // 'cart' => $cartProducts['date'],
            // 'cart' => $cartProducts['Is Available'],
            // 'cart' => $cartProducts['Moniteurs'],
            // 'cart' => $cartProducts['Permis Type'],
            'totalQuantity' => $cartCreneaux['totals']['quantity'],
            'totalPrice' =>$cartCreneaux['totals']['price']
        ]);
    }

    /**
     * Ajoute un article au panier (id du produit) et incrémente la quantitée (voir classe Cart)
     * @param Cart $cart
     * @param int $id
     * @return Repsonse
     */
    #[Route('/panier/ajouter/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, int $id): Response
    {
        $cart->addToCart($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * Réduit de 1 la quantité pour un article du panier
     * @param Cart $cart
     * @param int $id
     * @return Repsonse
     */
    #[Route('/panier/réduire/{id}', name: 'decrease_item')]
    public function decrease(Cart $cart, int $id): Response
    {
        $cart->decreaseItem($id);
        return $this->redirectToRoute('cart');
    }
    
    /**
     * Supprime une ligne d'articles du panier
     *
     * @param Cart $cart
     * @return Response
     */
    #[Route('/panier/supprimer/{id}', name: 'remove_cart_item')]
    public function removeItem(Cart $cart, int $id): Response
    {
        $cart->removeItem($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * Vide le panier entièrement
     *
     * @param Cart $cart
     * @return Response
     */
    #[Route('/panier/supprimer/', name: 'remove_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_creneaux_index');
    }
    
  
    /**
     *  essaie pour bouton panier!!!!
     *
     * @param Cart $cart
     * @return Response
     */
    #[Route('/panier/details', name: 'cart_details')]
    public function details(Cart $cart): Response
    {
        $cartDetails = $cart->getDetails();

        return $this->render('cart/cart_details.html.twig', [
            'cart' => $cartDetails['creneaux'],
            'totalQuantity' => $cartDetails['totals']['quantity'],
            'totalPrice' => $cartDetails['totals']['price']
        ]);
    }


}

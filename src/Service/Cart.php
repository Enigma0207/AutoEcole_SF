<?php
namespace App\Service;

use App\Repository\CreneauxRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Permet de gérer un panier en session plutot que de tout implémenter dans le controller
 */
class Cart 
{
    private $requestStack;
    private $repository;

    public function __construct(RequestStack $requestStack, CreneauxRepository $repository)
    {
        $this->requestStack = $requestStack;
        $this->repository = $repository;
    }


    /**
     * Crée un tableau associatif id => quantité et le stocke en session
     *
     * @param int $id
     * @return void
     */
    public function addToCart(int $id):void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        
        if (empty($cart[$id])) {
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }
        $this->requestStack->getSession()->set('cart', $cart);

    }

    /**
     * Récupère le panier en session
     *
     * @return array
     */
    public function get(): array
    {
        return $this->requestStack->getSession()->get('cart');
    }


    /**
     * Supprime entièrement le panier en session
     *
     * @return void
     */
    public function remove(): void
    {
        $this->requestStack->getSession()->remove('cart');
    }


    /**
     * Supprime entièrement un produit du panier (quelque soit sa quantité)
     *
     * @param int $id
     * @return void
     */
    public function removeItem(int $id): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$id]);
        $this->requestStack->getSession()->set('cart', $cart);
    }


    /**
     * Diminue de 1 la quantité d'un produit
     *
     * @param int $id
     * @return void
     */
    public function decreaseItem(int $id): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if ($cart[$id] < 2) {
            $this->requestStack->getSession();
            unset($cart[$id]);
        } else {
            $cart[$id]--;
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }


    /**
     * Récupère le panier en session, puis récupère les objets produits de la bdd
     * et calcule les totaux
     *
     * @return array
     */
    public function getDetails(): array
    {
        $cartCreneaux = [
            'creneaux' => [],
            'totals' => [
                'quantity' => 0,
                'price' => 0,
            ],
        ];

        $cart = $this->requestStack->getSession()->get('cart', []);
        if ($cart) {
            foreach ($cart as $id => $quantity) {
                $currentCreneau = $this->repository->find($id);
                if ($currentCreneau) {
                    $cartCreneaux['creneaux'][] = [
                        'creneau' => $currentCreneau,
                        'quantity' => $quantity
                    ];
                    $cartCreneaux['totals']['quantity'] += $quantity;
                    $cartCreneaux['totals']['price'] += $quantity * $currentCreneau->getPermis()->getPrice();
                }
            }
        }
        return $cartCreneaux;
    }
}
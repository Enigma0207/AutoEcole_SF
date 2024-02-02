<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserController extends AbstractController
{
   #[Route('/user', name: 'app_user')]
   public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
   {
       $user = new User();
       $form = $this->createForm(UserFormType::class, $user);
       $form->handleRequest($request);
      
      if($form->isSubmitted() && $form->isValid()){
         $Password = $form->get('password')->getData();
        //  dump($Password);
         $user->setPassword(
           $userPasswordHasher->hashPassword(
               $user,
              $Password
          ) 
    );
    $entityManager->persist($user);
       $entityManager->flush();
    return $this->redirectToRoute('app_login');
    }
   
       return $this->render('user/user.html.twig',[
        'form' => $form
       ]);
    }

    
    #[Route('/userliste', name: 'app_userliste')]
    public function userListe(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->render('user/userliste.html.twig', [
            'controller_name' => 'UserController',
             'user' => $user,
        ]);
    }

     #[Route('/edituser/{id}', name: 'app_edit_user')]
     public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
     {
         $form = $this->createForm(UserFormType::class, $user);
         $form->handleRequest($request);
     
         if ($form->isSubmitted() && $form->isValid()) {
            //  // Obtenir les données du formulaire
            //  $formData = $form->getData();
     
            //  // Mettre à jour l'utilisateur avec les nouvelles données du formulaire
            //  $user->setFirstname($formData->getFirstname());
            //  $user->setLastname($formData->getLastname());
            //  $user->setPhone($formData->getPhone());
            //  $user->setEmail($formData->getEmail());
     
            //  // Enregistrez les modifications dans la base de données
             $entityManager->flush();
     
             return $this->redirectToRoute('app_userliste');
         }
     
         return $this->render('user/edituser.html.twig', [
             'form' => $form->createView(),
         ]);
     }
     
     
    #[Route('/updateuser/{id}', name: 'app_update_user')]
    public function updateUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_userliste', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/updateuser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_user_delete')]
       public function deleteUser(Request $request, UserRepository $user, EntityManagerInterface $entityManager,$id): Response
       {
              if (!empty($id)) {
                  $userEntities = $user->findById($id);
                  if (!empty($userEntities)) {
                      $userEntity = $userEntities[0];
                      $entityManager->remove($userEntity);
                      $entityManager->flush();
                    } else {
                  echo "L'utilisateur n'existe pas";
                 }
             } else {
                 echo "Identifiant invalide";
               }
            return $this->redirectToRoute('app_userliste', [], Response::HTTP_SEE_OTHER);
    }



}

CONCEPT CLE:
1.ORM:L'ORM permet de représenter les données de la base de données sous forme d'objets en utilisant un mapping entre les entités (tables de la base de données) et les classes (objets dans le code).

2.DOCTRINE:-Doctrine est un ORM largement utilisé dans l'écosystème Symfony pour faciliter la manipulation des bases de données relationnelles en utilisant des entités PHP
-Doctrine permet de définir des relations entre les entités, telles que les relations ManyToOne, OneToMany, ManyToMany, etc. Ces relations sont définies dans le mapping et permettent de modéliser les liens entre les entités.

3.Entité :Une entité représente une classe PHP qui est mappée sur une table de base de données. 

4.Mapping :Le mapping est le processus de déclaration de la correspondance entre les propriétés d'une classe (attributs) et les colonnes d'une table de base de données.
5.EntityManager :L'EntityManager est une classe centrale dans Doctrine qui gère le cycle de vie des entités. Il est responsable de la persistance, de la récupération et de la suppression des entités dans la base de données.
Il assure également la gestion des relations entre entités.

6.Repository :Les repositories sont des classes fournies par Doctrine qui permettent d'effectuer des requêtes sur les entités

***création d'un nouveau projet:
$ symfony new Auto_EcoleSF --version="6.3.*" --webapp (dans bash)
********************************************************************************
***Fichier .env il faut modifier por la bdd:
 DATABASE_URL="mysql://root@127.0.0.1:3306/autoEcole?serverVersion=10.04.24-MariaDB&charset=utf8mb4"
********************************************************************************
 ***symfony console doctrine:database:create
 ***création des entités
$symfony console make:user
-[User] yes
-Email :yes
-hashage:yes
*****création formulaire user**********
$symfony console make:register-form
-yes
-no
-yes
*****création authentification**********
$symfony console make:auth
-1
- appUserAuthentificator
-yes
-yes
-yes
-1
************AUTRE ENTITY
 symfony console make:entity
 2.Permis
 2.1type,string,255,no,
 2.2price,float ou int,no
 2.3description,text,255,no
 2.4image,string,255,no,

 symfony console make:entity
 3.Creneaux
 3.1date,datetime,no
 3.2disponibility,json,no

 *****pour les relation entre les tables, ex: pour la table creneau, on ajour une propieté ex id_permis, type:relation,manytomany... avec l'entity user, nullable oui ou nom...

 ****** symfony.exe console make:migration
 ******symfony.exe console doctrine:migrations:migrate
********************************************************************************
 .*******lancer le server dans un autre terminal vscode:symfony server:start
 ********composer require --dev symfony/maker-bundle
********************************************************************************
 4. Création de la page home

 ******symfony console make:controller home
 changer index.php.twig en home.php.tweg dans HomeController return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
********************************************************************************
**** pour afficher home(se trouve dans templates,dossier home), aller dans le navigatuer taper: http://127.0.0.1:8000/home

****** dans templates, fichier base.html, il faut ajputer le lien:{% block stylesheets %}
            <link rel="stylesheet" href="{{ asset('assets/styles/bootstrap.min.css') }}">
            href="{{ asset() }}"> ce code signifie qu'on vise la racine
        {% endblock %}

********************************************************************************
5. prendre un modèle de nav dans bootswach et le coller dans la base
6.gestion de rôle et disponibilité car ilya des valeurs par defaux.
**********************************************************************
7.gestion de role et disponibilité
 #[ORM\Column(type: "json")]
    private array $roles = ['ROLE_ELEVE']; // Rôle par défaut

    public function __construct()
    {
       $this->roles = $this->roles ?: ['ROLE_ELEVE'];
    }
    
    ublic function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = array_unique($roles);

        return $this;
    }

****************************************
8. LES FIXTURES
 -composer req --dev orm-fixtures (Installation du composant)
- composer req fakerphp/faker (création des données aléatoires).
Dans le dossier Fixtures préparer vos données.
- symfony console doctrine:fixtures:load (Chargement des données 
dans BD
//POUR EFFACER LA BDD
*symfony console doctrine:database:drop --force

public function load(ObjectManager $manager): void
{
    $faker = Factory::create();
    $passwordEncoder = $this->container->get('security.password_encoder');

   for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setPhone($faker->phoneNumber());
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $manager->persist($user);
            $users[] = $user;
        }
    }
    $manager->flush();
    $ symfony console doctrine:fixture:loa
}
**********************************************************
9 CREATION DU FORMULAIRE
9.1 composer require symfony/form
9.2 composer require symfony/validator
 
//dans UserFormType.php
(namespace App\Form;use App\Entity\User;use Symfony\Component\Form\AbstractType;use Symfony\Component\Form\FormBuilderInterface;use Symfony\Component\OptionsResolver\OptionsResolver;use Symfony\Component\Validator\Constraints as Assert;use Symfony\Component\Form\Extension\Core\Type\SubmitType;use Symfony\Component\Form\Extension\Core\Type\PasswordType;)

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom et le nom sont requis.']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le prénom et le nom doivent comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom et le nom ne peuvent pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('send', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
9.3 dans user.html.twig ajouter dans le bloc body:
//apl a la base
{% extends 'base.html.twig' %}

{% block title %}Formulaire!{% endblock %}

{% block body %}
    <div class="example-wrapper userForm">
        <h4>  Formulaire d'inscription! ✅</h4>
        //pour formulaire
        {{ form_start(user) }}
            <div class="mb-1">
               {{ form_row(user.firstname, {'label': "Firstname: ", 'attr': {
                   'class': 'form-control',
                   'placeholder': "Votre Prénom ..."
               }}) }}
           </div>
                   <button type="submit" class="btn btn-success">S'inscrire</button>
       {{ form_end(user) }}
    </div>
{% endblock %}
**************************************************************************************
//puisque nous avons déjà notre entité user, on doit juste créer UserController

10.USER CONTROLLER
<?php

namespace App\Controller; // est une façon de regrouper des classes, interfaces, fonctions et constantes sous un même "nom" pour éviter les conflits de noms 
// toutes les classes definies dans ce fichier ici appartiennent au namespace App\Controller

*use App\Entity\User;//importe la classe User du namespace App\Entity
*use App\Form\UserFormType;// importe la classe UserFormType du namespace App\Form. Vous utilisez cette classe pour définir la structure du formulaire d'inscription de l'utilisateur. 
*use Doctrine\ORM\EntityManagerInterface;//// importe l'interface EntityManagerInterface de Doctrine, qui est utilisée pour interagir avec la couche d'abstraction de la base de données ex:$manager->persist($user);
*use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;//Cela importe la classe AbstractController, avoir toutes les fonctionalité du conroller
*use Symfony\Component\HttpFoundation\Request;//importe la classe Request de Symfony, qui encapsule les informations sur la requête HTTP entrante.
*use Symfony\Component\HttpFoundation\Response;//mporte la classe Response de Symfony, qui est utilisée pour construire la réponse HTTP que le contrôleur renvoie au navigateur du client.
*use Symfony\Component\Routing\Annotation\Route;//mporte l'annotation Route de Symfony, qui est utilisée pour définir les routes des actions du contrôleur. 

class UserController extends AbstractController
{
    1. #[Route('/user', name: 'app_user')]
    public function index(Request $request, EntityManagerInterface $em): Response
    //objet  $request pour les request, instance pour interagir avec la bdd
    //UserPasswordHasherInterface. Cette interface est utilisée pour hasher les mots de passe des utilisateurs de manière sécurisée avant de le stocker dans la bdd.
    {
           //objet $user contenant les données de l'utilisateur fournis par le formulaire
        $user = new User();
           //création du formulaire avc la class type de formulaire UserFormType et l'associe à l'objet $user. Le formulaire est ensuite utilisé pour gérer les données d'entrée de l'utilisateur.
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
            //dans ce code est utilisée pour traiter la requête HTTP associée au formulaire
            //handleRequest($request) : Cette méthode est appelée sur l'objet formulaire ($form) et prend en paramètre un objet Request ($request). Elle est utilisée pour traiter la requête HTTP actuelle et mettre à jour l'état du formulaire 

        if ($form->isSubmitted() && $form->isValid()) {
               //si le formulaire est soumis et si  les données soumises par l'utilisateur respectent les contraintes de validation définies dans le formulaire.
           $Password = $form->get('password')->getData();
              //avec la methode la méthode getData(),on  récupère la valeur du champ de formulaire "password" à partir des données soumises
            $user->setPassword(
              $userPasswordHasher->hashPassword(
                  $user,
                 $Password
             ) 
                 //$user->setPassword(...): Cela définit le mot de passe hashé sur l'objet utilisateur.
                //on utilise le service $userPasswordHasher (implémentant UserPasswordHasherInterface) pour hasher le mot de passe avant de le définir sur l'objet $user

                //$userPasswordHasher->hashPassword($user, $Password): Cette méthode prend l'entité utilisateur ($user) et le mot de passe brut ($Password) en tant que paramètres, puis retourne le mot de passe hashé.
         );
         $entityManager->persist($user);
              //Cette ligne indique à Doctrine d'enregistrer l'entité utilisateur ($user) pour la persistance. 
            $entityManager->flush();
               //synchronise les changements avec la base de données, effectuant ainsi l'enregistrement effectif de l'utilisateur dans la base de données.
         return $this->redirectToRoute('app_login');
             //Si tout se déroule avec succès, l'utilisateur est redirigé vers la route 'app_login'. Cela signifie que l'enregistrement de l'utilisateur est terminé et l'utilisateur est invité à se connecter.
         }
        
            return $this->render('user/user.html.twig',[
             'form' => $form
            ]);
            // Si le formulaire n'a pas été soumis ou si les données ne sont pas valides
            // la méthode rendra le template 'user/user.html.twig' en passant le formulaire en tant que variable 'form' pour être affiché dans la vue associée
    }
    4. #[Route('/edituser/{id}', name: 'app_edit_user')]
     public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
       // ******User $user:En injectant directement l'entité User, Symfony va essayer de récupérer l'objet User correspondant à l'identifiant fourni dans l'URL /edituser/{id} à partir de la base de données.
       *******EntityManagerInterface $entityManager : L'interface EntityManagerInterface est injectée automatiquement par Symfony et vous permet d'interagir avec la base de données.
     {
         $form = $this->createForm(UserFormType::class, $user);
               //un formulaire Symfony de type UserFormType est créé et associé à l'objet User à modifier. Cela pré-remplit le formulaire avec les données actuelles de l'utilisateur.
         $form->handleRequest($request);
            //Cette méthode traite la requête HTTP et met à jour le formulaire avec les données soumises. Cela inclut la validation des données du formulaire.
     
         if ($form->isSubmitted() && $form->isValid()) {
                //vérifie si le formulaire a été soumis et si toutes les contraintes de validation définies dans le type de formulaire (UserFormType) ont été respectées.
           
            $entityManager->flush();
                //les modifications sont appliquées à l'objet User et enregistrées dans la base de données via la méthode flush de l'EntityManager
     
             return $this->redirectToRoute('app_userliste');
         }
     
         return $this->render('user/edituser.html.twig', [
             'form' => $form->createView(),
         ]);
           // Si le formulaire n'a pas été soumis ou s'il contient des erreurs de validation, la méthode rend un template Twig ('user/edituser.html.twig') en passant le formulaire sous forme de vue.
           // fonction render:La méthode render génère une réponse HTTP contenant le contenu du fichier de modèle interprété. Cette réponse peut inclure du HTML,css,scripts,
     }
     

    5.#[Route('/delete/{id}', name: 'app_user_delete')]
    <!-- 
    ***Cette annotation est utilisée pour indiquer à Symfony comment la demande HTTP vers une URL spécifique doit être gérée. cette action est accessible via l'URL /delete/{id} et est nommée app_user_delete

    ****App_user_delete(Le nom de la route unique est utilisé pour générer des URL,).

    ***Le paramètre {id} est une variable dynamique qui sera remplie avec la valeur fournie dans l'URL. -->

    <!-- 
    *****En utilisant cette annotation, Symfony saura comment acheminer une requête vers l'URL /delete/{id} vers la méthode appropriée du contrôleur. La méthode du contrôleur correspondante sera celle qui porte cette annotation. -->

       public function deleteUser(Request $request, UserRepository $user, EntityManagerInterface $entityManager,$id): Response
       {
              <!-- 
              ****Gestion de dependance:en sumfony, les dependances sont généralement des paramettre des methodes
              *****Lorsqu'une méthode est appelée,Symfony s'occupe automatiquement de créer les instances des dépendances et de les injecter dans la méthode.
   
              A****Request $request : pour récupérer des données provenant de la requête
                   1.Cela signifie que votre méthode accepte un objet Request en tant que paramètre. Vous pouvez utiliser cet objet pour accéder à différentes propriétés de la requête 
                   2.$request (variable request du type request)est utilisé lorsque vous avez besoin d'accéder à des informations spécifiques à la requête dans la methode deleteUser
           -->
               B.UserRepository $userRepository:vous indiquez à Symfony de fournir       automatiquement une instance de UserRepository lorsque la méthode est            appelée. 
                    //Dans Symfony, le concept de repository est souvent utilisé pour in  teragir avec la base de données
                   ***UserRepository : C'est une classe qui est responsable de l'accès aux données relatives à l'entité User. En général, il est associé à l'ORM Doctrine et fournit des méthodes pour effectuer des opérations CRUD
   
               C.EntityManagerInterface $entityManager:vous indiquez à Symfony de fournir automatiquement une instance de l'EntityManager lorsque la méthode est appelée.
   
                    ***EntityManagerInterface:
                        - est une interface de Doctrine qui représente le gestionnaire d'entités. Il définit un ensemble de méthodes standard pour interagir avec la base de données en utilisant ORM - Object-Relational Mapping.
                         -Il est responsable de la gestion du cycle de vie des entités, de la persistance des objets dans la base de données, de la récupération des objets depuis la base de données

                    ****** $id:
                      Utilisation de l'Identifiant pour Supprimer l'Utilisateur :

                            Une fois que vous avez l'identifiant dans la méthode, vous pouvez utiliser cet identifiant pour récupérer l'entité utilisateur correspondante à partir de la base de données (généralement en utilisant le UserRepository) et ensuite la supprimer à l'aide de l'EntityManager.
             
         if (!empty($id)){
              // Cette ligne vérifie si la variable $id n'est pas vide. Cela permet de s'assurer qu'un identifiant valide a été fourni.
            $userData = $user->findById($id);
              //trouve la liste d l'entité correspondant à cette id dans la bdd grace
               méthode findById de l'objet $user  et stocke le dans  $userData (un tableau des entités)
            if($userData){
                // si des entités utilisateur ont été trouvées pour l'identifiant donné. Si c'est le cas, le code à l'intérieur du bloc if sera exécuté.
                $userEntity = $userData[0];
                //on l'extrait la première entité utilisateur de la liste récupérée. Notez que cette méthode suppose que l'identifiant est unique 
               $supprimer = $entityManager->remove($userEntity);
                 //grace à  l'objet $entityManager, on marque cette entité pour suppression
               $entityManager->flush();
                  //on suprime l'entité de la bdd grace à flush
           }
               } else {
               echo "l'utilisateur n'existe pas";
               //Si aucune entité utilisateur n'est trouvée pour l'identifiant donné, cela signifie que l'utilisateur n'existe pas
                }
             } else {
                 echo "Identifiant invalide";
                 //Si l'identifiant est vide, ce message est affiché, indiquant qu'un identifiant invalide a été fourni.
               }
            return $this->redirectToRoute('app_userliste', [], Response::HTTP_SEE_OTHER);
            //redirige l'utilisateur vers une route nommée 'app_userliste' après la suppression réussie de l'utilisateur, en utilisant le statut de redirection HTTP SEE_OTHER.
        }

    return $this->redirectToRoute('app_userliste', [], Response::HTTP_SEE_OTHER);



}
***************************CONNEXION**********************************************

11 LoginController pour se connecter
11.1 php bin/console make:controller Login
//il me crée deux dossiers et fichiers:
-src/Controller/LoginController.php
-templates/login/login.html.twig

en suite dans secu yaml:
# config/packages/security.yaml
security:
    # ...

    firewalls:
        main:
            # ...
            form_login:
                login_path: app_login
                check_path: app_login
                dans main on efface et remplacer form_logon...

11.2 dans LoginController:   
11.2.1 composer require symfony/security-bundle  
11.2.2 formulaire Login
11.2.2.1.symfony console make:auth
-1
-AppUserAuthentificator
-yes 3fois
-1
=> dans UserController.php il faut: retur $this->redirectToRoute("app_login)
=>dans AppUserAuth.php il faut return new Response($this->urlGenerator->generate('appe_user')) et commenter throw new\\Exception('TODO: provide a valid redirect inside '.__FILE__);

*************************************************
12 PERMISCONTROLLER
   class PermisController extends AbstractController
{
    #[Route('/permis', name: 'app_permis')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    <!-- Les paramètres Request $request et EntityManagerInterface $entityManager sont injectés dans la méthode index du contrôleur Symfony et sont utilisés pour traiter la requête HTTP et interagir avec la base de données. -->
    //Request $request :Le paramètre $request est de type Request et contient les informations de la requête HTTP actuelle, y compris les données soumises via le formulaire.
    //EntityManagerInterface $entityManager :Le paramètre $entityManager est de type EntityManagerInterface, qui est l'interface de base pour interagir avec l'ORM (Object-Relational Mapping) de Doctrine.
    {  
        //instance de l'entité Permis pour stocker les données soumises via le formulaire.
        $permis = new Permis();
        //Crée un objet de formulaire en utilisant la classe PermisFormType lié à l'objet  $permis créé ci_haut 
        $form = $this->createForm(PermisFormType::class, $permis);
        //Traite la requête HTTP cad Elle extrait les données du formulaire à partir de la requête.Elle remplit l'objet modèle ( $permis) donc une lise a jour avec les données venu du formulaire 
        $form->handleRequest($request);
        //Vérifie si le formulaire a été soumis et s'il est valide en fonction des contraintes de validation définies dans la configuration du formulaire 
        if ($form->isSubmitted() && $form->isValid()) {
         //Récupère le fichier uploadé à partir du champ 'image' du formulaire.
            $uploadedFile = $form->get('image')->getData();
            //Génère un nom de fichier unique pour éviter les collisions 
            $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();

            // Déplacez le fichier vers le répertoire où vous souhaitez le stocker
            $uploadedFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            // Enregistrez le nom du fichier dans l'entité
            $permis->setImage($newFilename);

            $entityManager->persist($permis);//marqué pour persister
            $entityManager->flush();// mise ajour definitive à la bdd

            // Redirige l'utilisateur vers la page "/permis" après une soumission réussie.
            return $this->redirectToRoute('app_permis');
        }
            //Si le formulaire n'est pas soumis ou s'il n'est pas valide, la méthode renvoie une réponse HTTP contenant le rendu du template "permis/permis.html.twig" avec le formulaire
        return $this->render('permis/permis.html.twig', [
            'controller_name' => 'PermisController',
            'form' => $form->createView(),
        ]);

        
    }
    #[Route('/permisliste', name: 'app_permisliste')]
    public function permisliste(PermisRepository $permisRepository): Response
    {
        $permis = $permisRepository->findAll();

        return $this->render('permis/permisliste.html.twig', [
            'controller_name' => 'PermisController',
            'permis' => $permis,
        ]);
    }

      #[Route('/editpermis/{id}', name: 'app_edit_permis')]
    public function editPermis(Request $request, Permis $permis, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PermisFormType::class, $permis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $uploadedFile = $form->get('image')->getData();
                if ($uploadedFile) {
                    $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();

                    $uploadedFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                    $permis->setImage($newFilename);
                }

                $entityManager->flush();

                return $this->redirectToRoute('app_permisliste');
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur s\'est produite lors de la mise à jour du fichier.');
            }
        }

        return $this->render('permis/editpermis.html.twig', [
            'controller_name' => 'PermisController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/updatepermis/{id}', name: 'app_update_permis')]
    public function updatePermis(Request $request, Permis $permis): Response
    {
        $form = $this->createForm(PermisFormType::class, $permis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $uploadedFile = $form->get('image')->getData();
                if ($uploadedFile) {
                    $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();

                    $uploadedFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                    $permis->setImage($newFilename);
                }

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('app_permisliste');
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur s\'est produite lors de la mise à jour du fichier.');
            }
        }

        return $this->render('permis/updatepermis.html.twig', [
            'controller_name' => 'PermisController',
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/deletepermis/{id}', name: 'app_permis_delete')]
    public function deletePermis(Request $request, PermisRepository $permis, EntityManagerInterface $entityManager,$id): Response
    {
           $deletePermis = $permis->find($id);
    
        if($deletePermis){
            $entityManager->remove($deletePermis);
            $entityManager->flush();
        }
    
    
        return $this->redirectToRoute('app_permisliste', [], Response::HTTP_SEE_OTHER);
    }

}
***********************
13 FORMULAIRE POUR PERMIS
12.1php bin/console make:form PermisFormType Permis
dans permisType
<?php
namespace App\Form;

use App\Entity\Permis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PermisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('image', FileType::class, [ 
                'label' => 'image (image file)',
                'mapped' => false, 
                'required' => false,
                'constraints' => [new Assert\Image([
                    'maxSize' => '15M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                ])],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Permis::class,
        ]);
    }
}
POUR AFFICHER L'IMAGE
*******dans twig:{% if form.vars.value.image is not null %}
            <img src="{{ asset('images' ~ form.vars.value.image) }}" alt="Permis Image">
        {% endif %}

12 PERMISCONTROLLER 


    ********* dans service.yaml
    parameters:
        images_directory: 'images'   // images est le dossier où mes images sont stockées une fois le formulaire est soumis
        ************************* dans twig:
         {% if form.vars.value.image is not null %}
                <img src="{{ asset('images' ~ form.vars.value.image) }}" alt="Permis Image">
            {% endif %}
        ******************service.yaml:
        parameters:
              images_directory: 'images'
              *************formtype:
                 ->add('image', FileType::class, [ 
                'label' => 'image (image file)',
                'mapped' => false, 
                'required' => false,
                'constraints' => [new Assert\Image([
                    'maxSize' => '15M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                ])],
            ]);

*****************LES REDIRECTIONS CONNEXION*********************


*****************************************************************
13. FORMULAIRE CRENEAUX
13.1synfony console make:crud creneaux // générer automatiquement le contrôleur, les fichiers de vue, le formulaire, etc., pour effectuer les opérations CRUD sur l'entité sélectionnée. mais il faut deja avoir l'entité Creneaux
13.2 creneauxType:
<?php
// src/Form/CreneauxType.php

// src/Form/CreneauxType.php

namespace App\Form;

use App\Entity\Creneaux;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreneauxType extends AbstractType
{
    <!-- Cette méthode est utilisée pour définir les champs du formulaire. -->
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$builder : C'est une instance de FormBuilderInterface, qui est utilisée pour ajouter des champs au formulaire.
        //array $options :

         C'est un tableau d'options qui peuvent être utilisées pour personnaliser le comportement du formulaire.
        $builder
            ->add('date', null, [
            
            ])
            ->add('permis', EntityType::class, [
                //ajout du champ permis au formulaire creneauavec le type EntityType::class, ce qui signifie qu'il représente une relation vers une autre entité( Permis).
                'class' => Permis::class,
                //Spécifie la classe de l'entité associée, dans ce cas, Permis
                'label' => 'Type',
                //Définit l'étiquette du champ dans le formulaire.
                'choice_label' => 'type',
                // la façon dont les options du champ sont affichées.
               
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Moniteur:',
                'choice_label' => 'firstname',
                'choices' => $options["moniteur"],
                
               
            ]);
        
    }
  //Cette méthode configure les options du formulaire.

    public function configureOptions(OptionsResolver $resolver): void
    //$resolver : C'est une instance de OptionsResolver, utilisée pour définir les options du formulaire.
    {
        $resolver->setDefaults([
            //definit la valeur par defaut de certaine option du formulaire
            'data_class' => Creneaux::class,
            'moniteur' => null,
            'eleve' => null,
        ]);
    }
}
13.3 Dans le fichier form:
{{ form_start(form) }} //le début du formulaire HTML. Elle prend en compte les configurations du formulaire telles que la méthode (POST, GET), l'action (l'URL vers laquelle les données du formulaire seront soumises), et d'autres informations nécessaires. 
    {{ form_widget(form) }}  //En résumé, ce code génère un formulaire HTML complet à partir d'un objet form Symfony.
    <button class="btnFormCreneaux">{{ button_label|default('Save') }}</button> //le bouton qui sera affiché sur votre formulaire, et le texte du bouton dépend du libellé spécifié dans votre contrôleur
{{ form_end(form) }}

**************NEW CRENEAUX********************

*************LIST CRENEAU****************
13.4 list.html.twig
{% extends 'base.html.twig' %}

{% block title %}New Creneaux{% endblock %}

{% block body %}
 <div class="newGenerale">
    <h4>Formulaire des Creneaux</h4>

    {{ include('creneaux/_form.html.twig') }} //il affiche le formulaire apartir de form.html.twig

    <a href="{{ path('app_creneaux_index') }}"><span>&#128412;</span>List</a>
 <div class="newGenerale">
{% endblock %}
******************SHOW CRENEAU**************************************************
13.5 show.html.twig
 {% extends 'base.html.twig' %} // Cela indique que ce fichier de modèle étend le modèle de base défini dans 'base.html.twig'. 
 <table >
            <tbody>
                <tr>
                    <th>Date</th>
                    <!-- récupère la valeur de la propriété 'date' de l'objet 'creneaux'. -->
                    <td>{{ creneaux.date ? creneaux.date|date('Y-m-d H:i:s') : '' }}</td>
                </tr>
                <tr>
                    <th>Moniteur</th>
                    <!-- récupère la valeur de la propriété 'firstname' de l'objet 'user' associé au créneau. -->
                    <td>{{ creneaux.user.firstname }}</td>
                </tr>
                <tr>
                    <th>Permis Type</th>
                    <td>{{ creneaux.permis.type }}</td>
                </tr>
                <tr>
                    <th>IsAvailable</th>
                    xpression Twig qui utilise l'opérateur ternaire. Elle évalue la valeur de la propriété 'isAvailable' de l'objet 'creneaux'.

<!-- Si la valeur de 'isAvailable' est évaluée à true, cela affiche 'Yes'.
Si la valeur de 'isAvailable' est évaluée à false, cela affiche 'No'. -->
                    <td>{{ creneaux.isAvailable ? 'Yes' : 'No' }}</td>
                </tr>
            </tbody>
        </table> 
    <!-- est une instruction Twig qui permet d'inclure le contenu d'un autre fichier Twig, en l'occurrence, le fichier _delete_form.html.twig 
      {{ include('creneaux/_delete_form.html.twig') }} -->

************EDIT CRENEAU*******************
13.6 edit.html.twig

{{ form_start(form) }}
        {{ form_widget(form) }}
<!--  utilisé pour soumettre le formulaire.  -->
<!-- fficher un formulaire Symfony complet avec un bouton de soumission "Update". Une fois que l'utilisateur clique sur ce bouton, le formulaire sera soumis vers l'action du contrôleur correspondant -->
        <button type="submit" class="btnUpdate">Update</button>
        {{ form_end(form) }}

13.7_delet_form.html.twig
<!-- - une méthode post pour soumettre les données du formulire
-action="{{ path('app_creneaux_delete', {'id': creneaux.id}) }}" : C'est l'URL vers laquelle le formulaire sera soumis. l'URL est générée à l'aide de la fonction path(à partir du nom de la route et des paramètres requis) de Symfony pointé vers  la route app_creneaux_delete avec parametre id responsable de la suppression.
-Il prend également en compte un paramètre {id} dans cette route, auquel il assigne la valeur creneaux.id. -->
  <form method="post" action="{{ path('app_creneaux_delete', {'id': creneaux.id}) }}" 
  
  <!-- onsubmit est un attribut d'événement dans la balise <form> qui spécifie le code JavaScript à exécuter lorsque le formulaire est soumis.
confirm('Are you sure you want to delete this item?') affiche une boîte de dialogue de confirmation avec le message spécifié. Si l'utilisateur clique sur "OK", la fonction retourne true, permettant ainsi au formulaire d'être soumis.  -->
  onsubmit="return confirm('Are you sure you want to delete this item?');">
    <input type="hidden" name="_token" value="{{ csrf_token('delete' ~ creneaux.id) }}">
    <button class="btn">Delete</button>
</form>
************************************GIT **********************************
1. git add .
2. git commit -m "suite"
3.$ git push


// ...
DASHBORD
1. composer require easycorp/easyadmin-bundle
-->no
2. symfony console make:admin:dashboard
--enter*2
3.symfony console make:admin:crud
--[0] user
    yes yes
--[1] permis
   yes yes
--[2] creneaux
   yes yes

   <?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;//Dashboard du bundle EasyAdminBundle, qui est utilisée pour configurer le tableau de bord administrateur.
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;//pour configurer les éléments du menu administrateur.
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;// qui permet de générer des URL pour les différentes pages d'administration.
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;//fournit une base pour les contrôleurs du tableau de bord administrateur.
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Permis;
use App\Entity\Creneaux;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    { 
        <!-- On obtient une instance du service AdminUrlGenerator qui permet de générer des URLs spécifiques à EasyAdminBundle(redirige vers des pagee etc). -->
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        <!--  On génère une URL pour le contrôleur UserCrudController et on redirige l'utilisateur vers cette URL. Cela signifie   lorsqu'un utilisateur accède à "/admin", il sera redirigé vers la page de gestion des utilisateurs. -->
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        
    }
<!-- Cette méthode configure le tableau de bord administrateur. -->
    public function configureDashboard(): Dashboard
  
    {
        //Elle crée une nouvelle configuration de tableau de bord avec le titre "AutoEcole SF".
        return Dashboard::new()
            ->setTitle('AutoEcole SF');
    }

    public function configureMenuItems(): iterable
    //Cette méthode configure les éléments du menu du tableau de bord administrateur.
    { //Ajoute un lien vers le tableau de bord
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        //Ajoute un lien vers la gestion des utilisateurs
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
        //Ajoute un lien vers la gestion des permis
        yield MenuItem::linkToCrud('Permis', 'fas fa-list', Permis::class);
        //Ajoute un lien vers la gestion des Creneaux
        yield MenuItem::linkToCrud('Creneaux', 'fas fa-list', Creneaux::class);
    }

    //RESUME:En résumé, lorsque vous accédez à "/admin", la méthode index génère une URL pour rediriger l'utilisateur vers la page de gestion des utilisateurs. Le tableau de bord est configuré pour afficher "AutoEcole SF" comme titre, et le menu est configuré avec des liens vers le tableau de bord, la gestion des utilisateurs, des permis et des créneaux.
} 

****--[0] user:pour usrCrudController:
<?php
// Ce contrôleur est responsable de la gestion de l'entité User dans le cadre du tableau de bord administrateur généré par EasyAdminBundle.

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController

{
    // Cette méthode statique indique à EasyAdminBundle quelle entité est gérée par ce contrôleur. 
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
}




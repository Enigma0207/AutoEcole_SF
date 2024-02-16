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
    //Cette annotation Symfony indique que cette méthode doit être appelée lorsqu'une requête est faite à l'URL '/permisliste'. Le nom 'app_permisliste' est l'identifiant unique de cette route.
    #[Route('/permisliste', name: 'app_permisliste')]
    //Définit une méthode publique appelée permisliste qui prend comme paramètre une instance de PermisRepository et renvoie une instance de Response.

    //récupérer la liste de tous les objets "Permis" à partir du PermisRepository et de les passer à une vue Twig pour affichage.
    public function permisliste(PermisRepository $permisRepository): Response
    {
        //Appelle la méthode findAll du PermisRepository pour récupérer tous les enregistrements de la table des permis depuis la base de données et le stocker dans  $permis.
        $permis = $permisRepository->findAll();

        // reponse de la methode et ici $this->render  indique au contrôleur de rendre la vue Twig'permis/permisliste.html.twig'  responsable de l'affichage de la liste des objets "Permis".
        return $this->render('permis/permisliste.html.twig', [
            'controller_name' => 'PermisController',
            //C'est la liste des objets "Permis" récupérée à partir du PermisRepository
            'permis' => $permis,
            //C'est le tableau associatif passé à la vue Twig
        ]);
    }

      #[Route('/editpermis/{id}', name: 'app_edit_permis')]
    public function editPermis(Request $request, Permis $permis, EntityManagerInterface $entityManager): Response
    { //Création du formulaire : Un formulaire basé sur la classe PermisFormType est créé, associé à l'objet "Permis" actuel.
        $form = $this->createForm(PermisFormType::class, $permis);
        // Gère la requête HTTP pour mettre à jour le formulaire
        $form->handleRequest($request);
         // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Récupère le fichier téléchargé depuis le formulaire
                $uploadedFile = $form->get('image')->getData();
                // Si un fichier a été téléchargé, procède au traitement
                if ($uploadedFile) {
                    // Génère un nouveau nom de fichier unique basé sur le hachage MD5 et l'extension du fichier
                    $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
                // Déplace le fichier téléchargé vers le répertoire configuré pour les images
                    $uploadedFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                // Met à jour le champ "image" de l'objet Permis avec le nouveau nom de fichier
                    $permis->setImage($newFilename);
                }
                // Applique les modifications à l'objet Permis en base de données
                $entityManager->flush();
                // Redirige l'utilisateur vers la liste des permis après la modification
                return $this->redirectToRoute('app_permisliste');
            } catch (FileException $e) {
                // Gère les erreurs liées au traitement du fichier (par exemple, s'il y a un problème de déplacement du fichier)
                $this->addFlash('error', 'Une erreur s\'est produite lors de la mise à jour du fichier.');
            }
        }
         // Si le formulaire n'est pas soumis ou n'est pas valide, affiche le formulaire d'édition
        return $this->render('permis/editpermis.html.twig', [
            'controller_name' => 'PermisController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/updatepermis/{id}', name: 'app_update_permis')]
    public function updatePermis(Request $request, Permis $permis): Response
    {
        // Crée un formulaire basé sur le PermisFormType et le Permis actuel
        $form = $this->createForm(PermisFormType::class, $permis);
        //Gère la requête HTTP pour mettre à jour le formulaire
        $form->handleRequest($request);
        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Récupère le fichier téléchargé depuis le formulaire
                $uploadedFile = $form->get('image')->getData();
                // Si un fichier a été téléchargé, procède au traitement
                if ($uploadedFile) {
                    // Génère un nouveau nom de fichier unique basé sur le hachage MD5 et l'extension du fichier
                    $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
                // Déplace le fichier téléchargé vers le répertoire configuré pour les images
                    $uploadedFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                // Met à jour le champ "image" de l'objet Permis avec le nouveau nom de fichier
                    $permis->setImage($newFilename);
                }
            // Applique les modifications à l'objet Permis en base de données
                $this->getDoctrine()->getManager()->flush();
            // Redirige l'utilisateur vers la liste des permis après la modification
                return $this->redirectToRoute('app_permisliste');
            } catch (FileException $e) {
                // Gère les erreurs liées au traitement du fichier (par exemple, s'il y a un problème de déplacement du fichier)
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
        Utilisation du PermisRepository pour rechercher l'objet "Permis" à supprimer en fonction de son identifiant ($id).
        $deletePermis = $permis->find($id);
        // Vérifie si l'objet Permis a été trouvé
        if($deletePermis){
            // Supprime l'objet Permis
            $entityManager->remove($deletePermis);
            // Applique la suppression en base de données
            $entityManager->flush();
        }
        // Redirige l'utilisateur vers la liste des permis après la suppression
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
13.4 Dans CRENEAUCONTROLLER
  <?php

namespace App\Controller;

use App\Entity\Creneaux;
use App\Form\CreneauxType;
use App\Repository\CreneauxRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/creneaux')]
class CreneauxController extends AbstractController
{
    

    #[Route('/', name: 'app_creneaux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    <!-- //UserRepository $userRepository: C'est probablement un repository (classe gérant la récupération des entités depuis la base de données) lié à l'entité User. Repositories sont souvent utilisés pour effectuer des requêtes personnalisées pour récupérer des entités depuis la base de données. Dans ce cas, le repository est utilisé pour obtenir la liste des utilisateurs ayant des rôles spécifiques. -->
    {

    //Récupération des moniteurs et élèves : La méthode utilise le UserRepository pour récupérer deux listes d'utilisateurs : les moniteurs et les élèves pour creer le formulaire de creneaux 
        $moniteur = $userRepository->getUsersByRole('ROLE_MONITEUR');
        $eleve = $userRepository->getUsersByRole('ROLE_ELEVE');
        // dd($moniteur);

        //on crée un nouvel objet Creneaux 
        $creneaux = new Creneaux();
        //On crée le formulaire associé (CreneauxType::class). Les listes de moniteurs et d'élèves sont passées en option lors de la création du formulaire.
        $form = $this->createForm(CreneauxType::class, $creneaux, ["moniteur" => $moniteur, "eleve"=>$eleve]);
        //pour traiter la requête HTTP.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //si le formulaire est soumis et validé
            $entityManager->persist($creneaux);
            //l'objet $creneaux est marqué ou préparé pour la bdd
            $entityManager->flush();
            //effectue réellement cette opération, en insérant l'objet dans la base de données.

            return $this->redirectToRoute('app_creneaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creneaux/new.html.twig', [
            'creneaux' => $creneaux,
            'form' => $form,
        ]);
    }
*****************************
<!-- Cette annotation indique que cette méthode (index) est associée à la route /list. Le nom de la route est 'app_creneaux_index', et elle répond uniquement aux requêtes HTTP de type GET. -->
    #[Route('/list', name: 'app_creneaux_index', methods: ['GET'])]
    public function index(CreneauxRepository $creneauxRepository): Response
   //Cette méthode est appelée lorsque l'utilisateur accède à l'URL /list de l'application.
   En tant que paramètre, elle reçoit un objet $creneauxRepository de type CreneauxRepository. Symfony injecte automatiquement cette dépendance en utilisant son système d'injection de dépendances.
    { 
        //La méthode utilise le $creneauxRepository pour appeler la méthode findAll(). Cela récupère tous les créneaux disponibles dans la base de données(c'est une liste des creneaux qui sera stocker dans $creneauxes )

        $creneauxes = $creneauxRepository->findAll();
        
        return $this->render('creneaux/list.html.twig', [
            'creneauxes' => $creneauxRepository->findAll(),
            //La méthode render est utilisée pour afficher une vue(Un tableau associatif est passé à la vue, avec la clé 'creneauxes' contenant la liste des créneaux), ici 'creneaux/list.html.twig'.
 
        ]);
    }
*****************************
<!-- Cette annotation indique que cette méthode (show) est associée à une route dynamique qui accepte un paramètre {id} dans l'URL. Le nom de la route est 'app_creneaux_show', et elle répond uniquement aux requêtes HTTP de type GET. -->
    //Symfony utilise l'injection de dépendance automatique pour injecter l'objet Creneaux directement en fonction de l'identifiant {id} spécifié dans l'URL.

    #[Route('/{id}', name: 'app_creneaux_show', methods: ['GET'])]
    public function show(Creneaux $creneaux): Response
    //La méthode show prend en paramètre un objet $creneaux de type Creneaux. Symfony injecte automatiquement cet objet en fonction de la valeur de l'ID fournie dans l'URL. Cela signifie que Symfony va chercher dans la base de données le créneau correspondant à l'ID donné et l'injecter dans cette méthode.

    {
        return $this->render('creneaux/show.html.twig', [
            //L'objet Creneaux récupéré est passé à la vue en tant que variable 'creneaux'
            'creneaux' => $creneaux,
        ]);
    }
    
*******************************************************
<!-- Cette annotation indique que cette méthode (edit) est associée à la route /quelquechose/edit, où quelquechose est remplacé par une valeur d'ID spécifique. Le nom de la route est 'app_creneaux_edit', et elle répond aux requêtes HTTP de type GET et POST. -->
    #[Route('/{id}/edit', name: 'app_creneaux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Creneaux $creneaux, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire basé sur la classe CreneauxType et l'objet Creneaux
        $form = $this->createForm(CreneauxType::class, $creneaux);

         // La méthode handleRequest est utilisée pour gérer la soumission du formulaire et extraire les données du formulaire de la requête.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification si le formulaire est soumis et valide

            $entityManager->flush();
            // Mise à jour de l'objet Creneaux dans la base de données
            la méthode $entityManager->flush() est appelée pour mettre à jour l'objet Creneaux enregistré en base de données.

            return $this->redirectToRoute('app_creneaux_index', [], 
            // Redirection vers la liste des Creneaux après la mise à jour
            Response::HTTP_SEE_OTHER);
        }

        return $this->render('creneaux/edit.html.twig', [
            'creneaux' => $creneaux,
            'form' => $form,
        ]);
    }
<!-- Cette annotation indique que cette méthode (delete) est associée à la route /quelquechose, où quelquechose est remplacé par une valeur d'ID spécifique. Le nom de la route est 'app_creneaux_delete', et elle répond uniquement aux requêtes HTTP de type POST. -->
    #[Route('/{id}', name: 'app_creneaux_delete', methods: ['POST'])]
    //$request: C'est un objet représentant la requête HTTP, utilisé pour récupérer les données de la requête.
     $creneaux: C'est un objet de type Creneaux qui est automatiquement injecté par Symfony en fonction de l'ID fourni dans l'URL.
     $entityManager: C'est un objet responsable de la gestion des entités et de l'interaction avec la base de données.

    public function delete(Request $request, Creneaux $creneaux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$creneaux->getId(), $request->request->get('_token'))) {
            <!-- En résumé, cette ligne de code vérifie que le jeton CSRF soumis avec la requête POST correspond à celui généré lors de la création du formulaire d'origine. Si les deux jetons correspondent, cela signifie que la demande de suppression est légitime -->
            //isCsrfTokenValid() est une méthode fournie par Symfony qui permet de vérifier si un jeton CSRF est valide.

            //'delete'.$creneaux->getId()' : Cette partie crée une clé unique pour le jeton CSRF en concaténant le mot 'delete' avec l'ID du créneau ($creneaux->getId()). Cela permet d'assurer que le jeton CSRF est spécifique à cette action de suppression particulière
            //$request->request->get('_token') : Cette partie récupère la valeur du jeton CSRF soumis avec la requête POST. Le jeton CSRF est généralement inclus dans le formulaire HTML sous le nom _token.

            $entityManager->remove($creneaux);

            //Enregistre les changements (la suppression de l'entité Creneaux) dans la base de données.
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_creneaux_index', [], Response::HTTP_SEE_OTHER);
    }
<!-- Cette annotation indique que cette méthode (update) est associée à la route /quelquechose/update, où quelquechose est remplacé par une valeur d'ID spécifique. Le nom de la route est 'app_creneaux_update', et elle répond aux requêtes HTTP de type GET et POST.
$request: C'est un objet représentant la requête HTTP, utilisé pour récupérer les données de la requête.

$creneaux: C'est un objet de type Creneaux qui est automatiquement injecté par Symfony en fonction de l'ID fourni dans l'URL.

$entityManager: C'est un objet responsable de la gestion des entités et de l'interaction avec la base de données. -->
    #[Route('/{id}/update', name: 'app_creneaux_update', methods: ['POST'])]
    public function update(Request $request, Creneaux $creneaux, EntityManagerInterface $entityManager): Response
    {
        //Crée un formulaire basé sur le type CreneauxType et le lie à l'objet $creneaux. Cela pré-remplit le formulaire avec les données existantes du créneau.

        $form = $this->createForm(CreneauxType::class, $creneaux);
        //Traite la requête HTTP pour remplir le formulaire avec les données soumises.
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            //Vérifie si le formulaire a été soumis et s'il est valide.

            $entityManager->flush();
            //Si le formulaire est valide, les changements sont enregistrés dans la base de données avec $entityManager->flush();
    
            // Ajoutez une instruction dump pour déboguer
            dump('Redirection effectuée');
    
            return $this->redirectToRoute('app_creneaux_index', [], Response::HTTP_SEE_OTHER);
            //Redirige l'utilisateur vers la liste des créneaux après la mise à jour réussie.
        }
    
        return $this->render('creneaux/updateCreneaux.html.twig', [
            'creneaux' => $creneaux,
            'form' => $form,
        ]);
    }
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
*********************PANIER******************************
-CART CONTROLLER:
A.index:pour afficher le panier
//Définit l'URL à laquelle cette méthode du contrôleur répond (/mon-panier) et le nom de la route (cart).

#[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {
        //La méthode $cart->getDetails()  est appelée pour obtenir les détails du panier, y compris les créneaux, la quantité totale et le prix total.. Cette méthode a été définie dans la classe Cart.

        $cartCreneaux = $cart->getDetails();

        return $this->render('cart/cart.html.twig', [
            //'cart' : les créneaux dans le panier ($cartCreneaux['creneaux']).
            'cart' => $cartCreneaux['creneaux'],
           //la quantité totale des créneaux dans le panier 
            'totalQuantity' => $cartCreneaux['totals']['quantity'],
            //le prix total des créneaux dans le panier
            'totalPrice' =>$cartCreneaux['totals']['price']
        ]);
        Résumé:En résumé, cette méthode récupère les détails du panier à l'aide du service Cart et rend une vue Twig pour afficher ces détails, y compris les créneaux présents dans le panier, la quantité totale et le prix total.
    }
****************************************************
-SERVICE:
       1Les services sont classe ou des objets réutilisables(dans différentes parties de l'application) qui effectuent des opérations spécifiques, telles que la manipulation de la base de données, l'envoi d'e-mails, l'interaction avec des services tiers
       2. peuvent dépendre d'autres services ou de composants(ingection des dépendances)

       Dans notre cas:Ce service Symfony, appelé Cart, est conçu pour gérer un panier en session dans une application
14.CARTCONTROLLER
14.1 php bin/console make:controller CartController
//il me crée deux dossiers et fichiers:
-src/Controller/CartController.php
-templates/cart/cart.html.twig
*************************************************
  <?php
namespace App\Service;

use App\Repository\CreneauxRepository;
use Symfony\Component\HttpFoundation\RequestStack;

  //la classe Cart qui gère un panier en session dans une application Symfony.
 
class Cart 

{
    private $requestStack;
    private $repository;
    //ces deux proprietés sont(private), ce qui signifie qu'elles ne sont directement accessibles que depuis l'intérieur de la classe Cart. Cela suit le principe d'encapsulation, où les détails internes d'une classe sont cachés à l'extérieur de celle-ci, et l'accès à ces détails se fait via des méthodes publiques ou des accesseurs (getters et setters) définis dans la classe.

   //Constructeur : Le constructeur est appelé lorsqu'une instance de la classe Cart est créée. Il prend deux paramètres en injection de dépendances :  

    //$requestStack : C'est un service Symfony qui permet d'accéder à la requête actuelle et à la session associée.
     $repository : C'est une instance de CreneauxRepository,  Le repository est utilisé pour interagir avec la base de données Doctrine et récupérer des informations sur les créneaux..
Ces dépendances sont nécessaires pour interagir avec la session et la base de données.



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
        // manière de récupérer le contenu de la session associée à l'objet RequestStack dans Symfony,
        //1. $this->requestStack:on référence à la propriété $requestStack de l'objet courant ($this)contenat l'instance de la classe RequestStack.
        2.->getSession():La méthode getSession() de l'objet RequestStack renvoie l'objet Session associé à la requête actuelle
        3.->get('cart', []) : La méthode get('cart', []) est utilisée pour  récupère le tableau associatif stocké dans la session sous la clé 'cart'. Si cette clé n'existe pas, la valeur par défaut est un tableau vide []

        $cart = $this->requestStack->getSession()->get('cart', []);
        
        if (empty($cart[$id])) {
            //si l'element identifié n"xiste pas dans le panier,donner la valeur 1 à la quantité
            $cart[$id] = 1;
            //si cela existe deja dans le panier, ajouter à la qté existante
        } else {
            $cart[$id]++;
        }
        //Cette ligne met à jour la session en remplaçant l'ancien panier par le nouveau panier modifié. La méthode set est utilisée pour associer le tableau $cart à la clé 'cart' dans la session.
        $this->requestStack->getSession()->set('cart', $cart);

    }
    /**
     * Récupère le panier en session
     *
     * @return array
     */
     //1.$this->requestStack:permet d'acceder à la session
     2.getSession(): recupere la session
     3.->get('cart');:recupererle contenu du panier stocker dans la session
    public function get(): array
    {
        return $this->requestStack->getSession()->get('cart');
    }
    /**
     * Supprime entièrement le panier en session
     *
     * @return void
     */

     //1.$this->requestStack->getSession() : Récupère l'objet de session associé à la requête en cours. 
       2.->remove('cart') : Cela utilise la méthode remove('cart') pour supprimer complètement la clé 'cart' de la session.

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

     //1.$cart = $this->requestStack->getSession()->get('cart', []);:Récupère le panier actuel depuis la session Symfony. Si le panier n'existe pas, un tableau vide est utilisé par défaut. 
     //2.unset($cart[$id]): utilise la fonction unset pour supprimer l'élément du panier associé à l'ID spécifié. 
     //3. $this->requestStack->getSession()->set('cart', $cart) :pour obtenir l'objet de session associé à la requête actuelle. Ensuite, la méthode set est utilisée pour mettre à jour la variable de session 'cart' avec le tableau du panier modifié.

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

     //1.decreaseItem:Elle gère la diminution de la quantité d'un produit plutôt que la suppression totale
     //2.$cart = $this->requestStack->getSession()->get('cart', []);:Récupère le panier actuel depuis la session Symfony. Si le panier n'existe pas, un tableau vide est utilisé par défaut. 
     3.Si la quantité du produit est inférieure à 2, l'élément lié a l'id est complètement supprimé.
     4.sinon décremente le
     5.$this->requestStack->getSession()->set('cart', $cart):on obtient l'objet de session associé à la requête actuelle. Ensuite, la méthode set est utilisée pour mettre à jour la variable de session 'cart' avec le tableau du panier modifié.

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
        //Un tableau vide est initialisé pour stocker les détails du panier. Il contient deux clés : 'creneaux' pour stocker les créneaux du panier, et 'totals' pour stocker la quantité totale et le prix total des créneaux.
        $cartCreneaux = [
            'creneaux' => [],
            'totals' => [
                'quantity' => 0,
                'price' => 0,
            ],
        ];
     //// Récupération du panier depuis la session Symfony avec la method get
        $cart = $this->requestStack->getSession()->get('cart', []);
        if ($cart) {
            //Une boucle foreach parcourt chaque élément du panier, où chaque élément correspond à une paire clé-valeur (ID du créneau et quantité).
            foreach ($cart as $id => $quantity) {
                //Pour chaque ID du panier, la méthode $this->repository->find($id)rechercher le créneau associé dans le repository

                $currentCreneau = $this->repository->find($id);

                //Si le créneau est trouvé, ses détails (objet $currentCreneau) sont ajoutés au tableau 'creneaux' avec la quantité correspondante.
                if ($currentCreneau) {
                    $cartCreneaux['creneaux'][] = [
                        'creneau' => $currentCreneau,
                        'quantity' => $quantity
                    ];
                    //Les totaux 'quantity' et 'price' du panier sont mis à jour en fonction de la quantité et du prix du créneau actuel.

                    $cartCreneaux['totals']['quantity'] += $quantity;
                    $cartCreneaux['totals']['price'] += $quantity * $currentCreneau->getPermis()->getPrice();
                }
            }
        }
        return $cartCreneaux;
    }
}
************************************************
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




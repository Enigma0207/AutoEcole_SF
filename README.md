***création d'un nouveau projet:
$ symfony new AutoEcole --version="6.3.*" --webapp (dans bash)
********************************************************************************
***Fichier .env il faut modifier por la bdd:
 DATABASE_URL="mysql://root@127.0.0.1:3306/autoEcole?serverVersion=10.04.24-MariaDB&charset=utf8mb4"
********************************************************************************
 ***symfony console doctrine:database:create
 ***création des entités
 symfony console make:entity
 1.User
 1.1firtsname,string,255,no,
 1.2lastname...
 1.3phone,string,no
 1.4email,string,255,no,
 1.5password,string,255,no,
 1.5role,json,no (il sera modifier dans l'entity à la main)

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

10.UserController
<?php

namespace App\Controller;

*use App\Entity\User;//importe la classe User du namespace App\Entity
*use App\Form\UserFormType;// importe la classe UserFormType du namespace App\Form. Vous utilisez cette classe pour définir la structure du formulaire d'inscription de l'utilisateur. 
*use Doctrine\ORM\EntityManagerInterface;//// importe l'interface EntityManagerInterface de Doctrine, qui est utilisée pour interagir avec la couche d'abstraction de la base de données ex:$manager->persist($user);
*use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;//Cela importe la classe AbstractController, avoir toutes les fonctionalité du conroller
*use Symfony\Component\HttpFoundation\Request;//importe la classe Request de Symfony, qui encapsule les informations sur la requête HTTP entrante.
*use Symfony\Component\HttpFoundation\Response;//mporte la classe Response de Symfony, qui est utilisée pour construire la réponse HTTP que le contrôleur renvoie au navigateur du client.
*use Symfony\Component\Routing\Annotation\Route;//mporte l'annotation Route de Symfony, qui est utilisée pour définir les routes des actions du contrôleur. 

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request, EntityManagerInterface $em): Response
    //objet  $request pour les request, instance em=pour interagir avec la bdd
    {
        //objet $user contenant les données de l'utilisateur fournis par le formulaire
        $user = new User();
        //création du formulaire avc la class type de formulaire UserFormType et l'associe à l'objet $user. Le formulaire est ensuite utilisé pour gérer les données d'entrée de l'utilisateur.
        $form = $this->createForm(UserFormType::class, $user);
        // raite la soumission du formulaire. 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // sauvegarder
            $em->persist($user);
            // synchroniser à la bdd
            $em->flush();

            // Rediriger vers la page de connexion après l'inscription
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/user.html.twig', [
            'controller_name' => 'UserController',
            'user' => $form->createView(),
        ]);
    }
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
12 AUTRE FORMULAIRE POUR PERMIS
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

****** dans permiscontroller
     #[Route('/permisliste', name: 'app_permisliste')]
    public function permisliste(PermisRepository $permisRepository): Response
    {
        $permis = $permisRepository->findAll();

        return $this->render('permis/permisliste.html.twig', [
            'controller_name' => 'PermisController',
            'permis' => $permis,
        ]);
    }
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
            *************controller:
            if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
            $newFilename = md5(uniqid()) . '.' . $uploadedFile->guessExtension();

            // Déplacez le fichier vers le répertoire où vous souhaitez le stocker
            $uploadedFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            // Enregistrez le nom du fichier dans l'entité
            $permis->setImage($newFilename);

            $entityManager->persist($permis);
            $entityManager->flush();

            // Rediriger ou effectuer d'autres actions après la soumission réussie
            return $this->redirectToRoute('app_permis');
        }

        return $this->render('permis/permis.html.twig', [
            'controller_name' => 'PermisController',
            'form' => $form->createView(),
        ]);
    }
*****************LES REDIRECTIONS CONNEXION*********************


*****************************************************************
13. FORMULAIRE CRENEAUX
13.1 symfony console make:controller creneaux
13.2 php bin/console make:form CreneauxFormType
-Creneaux
********************************************************************

 'attr' => [
                    'class' => 'creneauxChamps',
                ],

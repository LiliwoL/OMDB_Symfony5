<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpFoundation\Request;

use App\Entity\Movie;
use App\Form\ShareMovieMailType;
use App\Repository\MovieRepository;
use App\Service\Slugifier;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

/**
 * @Route("/movie", name="movie_")
 */
class MovieController extends AbstractController
{
    // Propriété de la classe MovieController
    // Données normalement récupérées dans une BD ou via un appel API
    private $_data = array (
        0 => 
        array (
          'id' => 1,
          'title' => 'Asterix and the Gauls (Astérix le Gaulois)',
          'synopsis' => '',
          'genre' => 'Action|Adventure|Animation|Children|Comedy',
          'poster' => 'http://dummyimage.com/142x144.png/ff4444/ffffff',
        ),
        1 => 
        array (
          'id' => 2,
          'title' => 'God\'s Little Acre',
          'synopsis' => '',
          'genre' => 'Comedy|Drama|Romance',
          'poster' => 'http://dummyimage.com/101x129.png/ff4444/ffffff',
        ),
        2 => 
        array (
          'id' => 3,
          'title' => 'Mr. Blandings Builds His Dream House',
          'synopsis' => '部落格',
          'genre' => 'Comedy',
          'poster' => 'http://dummyimage.com/236x242.jpg/ff4444/ffffff',
        ),
        3 => 
        array (
          'id' => 4,
          'title' => 'Hatchet',
          'synopsis' => 'NULL',
          'genre' => 'Comedy|Horror',
          'poster' => 'http://dummyimage.com/151x138.png/dddddd/000000',
        ),
        4 => 
        array (
          'id' => 5,
          'title' => 'For Whom the Bell Tolls',
          'synopsis' => '',
          'genre' => 'Adventure|Drama|Romance|War',
          'poster' => 'http://dummyimage.com/239x112.jpg/ff4444/ffffff',
        ),
        5 => 
        array (
          'id' => 6,
          'title' => 'Christmas in Connecticut',
          'synopsis' => 'בְּרֵאשִׁית, בָּרָא אֱלֹהִים, אֵת הַשָּׁמַיִם, וְאֵת הָאָרֶץ',
          'genre' => 'Comedy|Romance',
          'poster' => 'http://dummyimage.com/120x118.bmp/5fa2dd/ffffff',
        ),
        6 => 
        array (
          'id' => 7,
          'title' => 'Last Run, The',
          'synopsis' => '',
          'genre' => 'Comedy|Drama',
          'poster' => 'http://dummyimage.com/218x231.bmp/cc0000/ffffff',
        ),
        7 => 
        array (
          'id' => 8,
          'title' => 'Girl Who Leapt Through Time, The (Toki o kakeru shôjo)',
          'synopsis' => '',
          'genre' => 'Animation|Comedy|Drama|Romance|Sci-Fi',
          'poster' => 'http://dummyimage.com/132x182.bmp/cc0000/ffffff',
        )
    );

      /*
        //On pourrait charger le repository directement  l création de ce controler
        
        private $_repository;

        public function __construct(MovieRepository $movieRepository){
          $this->_repository = $movieRepository;
        }

        // Plus besoin alors d'injecter la dépendance!
      */    


    /**
     * @Route(
     *    "/add",
     *    name="add"
     * )
     */
    public function add(EntityManagerInterface $em): Response
    {
        // Nouvelle instance de l'entité Movie
        $movie = new Movie();

        // Ajout des donnés via les setters
        $movie->setTitle("Shakespeare in love");
        $movie->setSynopsis("Histoire de Shakespeare");
        $movie->setCensure("");
        $movie->setGenre("Romantique");
        $movie->setPoster("https://images-na.ssl-images-amazon.com/images/I/51zZ9rh8nyL._AC_SY445_.jpg");

        // Utilisation du EntityManager pour préparer les requêtes d'insertions
        $em->persist($movie);

        // Exécution réeelle de la requête d'insertion
        $em->flush();
        
        // Redirection vers la page de liste de TOUS les films
        return $this->redirectToRoute(
          'movie_list'
        );
    }


    // Route qui ajoute un film via un formulaire
    /**
     * @Route(
     *    "/addForm",
     *    name="addForm"
     * )
     */
    public function addForm(Request $request, EntityManagerInterface $em, Slugifier $slugifier) : Response{

        // Instance d'entité
        $movie = new Movie();


        // Création d'un formulaire à usage unique dans CE controller
        $formulaireAjoutFilm = $this->createFormBuilder($movie)
          ->add('title', TextType::class)
          ->add('synopsis', TextareaType::class)
          ->add('genre', TextType::class)
          ->add('censure', TextType::class)
          ->add('poster', UrlType::class,
            [
              'attr' => [
                'placeholder' => 'http://...'
              ]
            ]
          )
          ->add('save', SubmitType::class, [
            'label' => 'Enregistrer ce film'
          ])->getForm();


        // Prise en compte de la requete POST
        // Dump de la requete
        //dump($request);

        $formulaireAjoutFilm->handleRequest($request);

        // Test du formulaire
        if ($formulaireAjoutFilm->isSubmitted() && $formulaireAjoutFilm->isValid())
        {
          // Compléter le contenu de l'instance $movie
          $movie = $formulaireAjoutFilm->getData();

          // $movie est donc une instance de Entity Movie avec les infos saisies dans le form

          // Avant l'insertion en base on va ajouter le champ Slug
          $slug = $slugifier->slugify($movie->getTitle());
          $movie->setSlug($slug);

          // Appel a l'entity manager pour insertion en base
          $em->persist($movie);
          $em->flush();

          // Renvoie vers la liste des films
          $response = $this->redirectToRoute(
            'movie_list'
          );
        }else{
          // Renvoie la vue avec le formulaire
          $response = $this->render (
            'movie/addForm.html.twig',
            [
              'formulaireAjoutFilm' => $formulaireAjoutFilm->createView()
            ]
          );
          
        }


        return $response;
    }

    /**
     * Liste de tous les films en base
     * 
     * @TODO: test
     * 
     * @Route(
     *    "/list",
     *    name="list"
     * )
     */
    public function list(): Response
    {

      // Chercher le repository de Movie
      // et findAll
      $movies = $this->getDoctrine()
        ->getRepository(Movie::class)
        ->findAll();

      // Au cas où rien n'est trouvé
      if (empty($movies)) {
        throw $this->createNotFoundException(
          "Aucun film trouvé"
        );
      }

      // On va renvoyer l'intégralité du tableau $_data
      return $this->render(
        'movie/list.html.twig',
        [
            'movieList' => $movies
        ]
      );
  }


    /**
     * @TODO: Aller chercher les données en base
     * 
     * @Route(
     *      "/OLD_list",
     *      name="OLDlist"
     * )
     * 
     */
    public function OLDlist(): Response
    {
        // On va renvoyer l'intégralité du tableau $_data
        return $this->render(
            'movie/list.html.twig',
            [
                'movieList' => $this->_data
            ]
        );
    }


    /**
     * @TODO: Aller chercher les données en base
     * 
     * @Route(
     *      "/listJson",
     *      name="listJson"
     * )
     * 
     */
    public function listJson(): Response
    {
        // Renvoi mais cette fois ci en JSON
        return new JsonResponse($this->_data);
    }

    /**
     * @Route(
     *      "/get/{idMovie}",
     *      name="getMovie",
     * 
     *      requirements={
     *          "idMovie"="\d+"
     *      }
     * )
     */
    public function getMovie(int $idMovie, MovieRepository $movieRepository): Response
    {
        // On a récupéré l'idMovie
        $movie = $movieRepository
          ->find($idMovie);

        // Au cas où rien n'est trouvé
        if (!$movie) {
          throw $this->createNotFoundException(
            "Aucun film trouvé pour cet id " . $idMovie
          );
        }

        // ########### Ajout du formulaire de partage par mail
        $formulaireMail = $this->createForm(          
          // Nom du FormType
          ShareMovieMailType::class,

          //Data à passer au formulaire
          null,
          [
            // Action du formulaire
            'action' => $this->generateUrl(
              'Share Movie', // Nom de la route
              // Paramètres à envoyer à la route
              [
                'id' => $idMovie
              ]
            )
          ]
        )->createView();

        return $this->render(
            'movie/get.html.twig',
            [
                'movie' => $movie,
                // Formulaire de partage
                'formulaireMail' => $formulaireMail
            ]
        );
    }

    /**
     * @Route(
     *      "/OLDget/{idMovie}",
     *      name="OLDgetMovie",
     * 
     *      requirements={
     *          "idMovie"="\d+"
     *      }
     * )
     */
    public function OLDgetMovie(int $idMovie): Response
    {
        // On a récupéré l'idMovie
        // On lui retranche 1 pour gérer le souci des index
        $movie = $this->_data[ $idMovie -1 ];


        return $this->render(
            'movie/get.html.twig',
            [
                'movie' => $movie
            ]
        );
    }




    // A SUPPRIMER
    /**
     * @Route("/movie", name="movie")
     */
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }
}

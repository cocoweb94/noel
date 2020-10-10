<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\ContactType;
use App\Entity\Product;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request): Response
    {
        return $this->render('index.html.twig', []);
    }

    /**
     * @Route("/boutique", name="boutique")
     */
    public function boutique(Request $request): Response
    {
        if(!isset($_COOKIE["commande"]))
            $_COOKIE["commande"] = "{}";

        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }

        $repository = $this->getDoctrine()->getRepository(Product::class);
        //$products = $repository->findAll();

        $query = $repository->createQueryBuilder('p')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->setFirstResult(($page - 1) * getenv('LIMIT'))
            ->setMaxResults(getenv('LIMIT'))
            //->orderBy('p.price', 'ASC')
            ->getQuery();
        //return new Paginator($query);

        $products = $query->getResult();

        $queryCount = $repository->createQueryBuilder('p')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->getQuery();

        $countProducts = $queryCount->getResult();
        $countPage = count($countProducts) / getenv('LIMIT');

        return $this->render('boutique.html.twig', [
            'products' => $products,
            'nbpage' => ceil($countPage),
            'page' => $page,
            'jsonCommande' => $_COOKIE["commande"],
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reqPost = $form->getData();
            var_dump($reqPost);die;
            /*$mail = new Mail($reqPost["email"], $reqPost);
            $sendMail = $mail->sendContactMail();
            $mailer->send($sendMail);*/
        }
        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addpanier", name="addpanier")
     */
    public function addpanier(Request $request)
    {
        header('Access-Control-Allow-Origin: noel.diaconat-grenoble.org');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Max-Age: 1000');

        $req = json_decode(urldecode($request->getContent()), true);

        $repository = $this->getDoctrine()->getRepository(Product::class);

        if($request->isXmlHttpRequest()) {

            $query = $repository->createQueryBuilder('p')
                ->where('p.stock > :stock')
                ->setParameter('stock', '0')
                ->andWhere('p.id IN (:ints)')
                ->setParameter('ints', array_keys($req),\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                ->getQuery();

            $products = $query->getResult();

            foreach($products as $product){

            }

            return new JsonResponse($products, 200, array('Access-Control-Allow-Origin'=> 'noel.diaconat-grenoble.org'));
        } else {
            return new Response("Erreur : ce n'est pas une requete Ajax", 400);
        }

    }
}
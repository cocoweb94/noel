<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\ContactType;
use App\Form\Type\CommandeType;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Lotterie;
use App\Tools\Mail;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request): Response
    {
        //----------------- GET PANIER ----------------------
        if(array_key_exists("commande", $_COOKIE) && count(get_object_vars(json_decode($_COOKIE["commande"]))) > 0) {
            $tabCookie = get_object_vars(json_decode($_COOKIE["commande"]));
            $repository = $this->getDoctrine()->getRepository(Product::class);
            $query = $repository->createQueryBuilder('p')
                ->where('p.stock > :stock')
                ->setParameter('stock', '0')
                ->andWhere('p.id IN (:ints)')
                ->setParameter('ints', array_keys($tabCookie),\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                ->getQuery();

            $panierProducts = $query->getResult();
        }else{
            $panierProducts = null;
            $tabCookie = null;
        }

        return $this->render('index.html.twig', [
            'cookiepanier' => $tabCookie,
            'panier' => (count($panierProducts) > 0 ? $panierProducts : null),
        ]);
    }

    /**
     * @Route("/gourmandises", name="gourmandises")
     */
    public function gourmandises(Request $request): Response
    {
        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }

        if($request->query->get('commande') == "valide"){
            unset($_COOKIE["commande"]);
        }

        $repository = $this->getDoctrine()->getRepository(Product::class);

        //----------------- GET LIST PRODUCT PAGINATE  ----------------------

        $query = $repository->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->andWhere('c.id IN (:ints)')
            ->setParameter('ints', [4, 1], \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
            ->setFirstResult(($page - 1) * getenv('LIMIT'))
            ->setMaxResults(getenv('LIMIT'))
            //->orderBy('p.price', 'ASC')
            ->getQuery();
        //return new Paginator($query);

        $products = $query->getResult();

        //----------------- GET NB PAGE ----------------------
        $queryCount = $repository->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->andWhere('c.id IN (:ints)')
            ->setParameter('ints', [4, 1], \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
            ->getQuery();

        $countProducts = $queryCount->getResult();
        $countPage = count($countProducts) / getenv('LIMIT');

        //----------------- GET PANIER ----------------------
        if(array_key_exists("commande", $_COOKIE) && count(get_object_vars(json_decode($_COOKIE["commande"]))) > 0) {
            $tabCookie = get_object_vars(json_decode($_COOKIE["commande"]));
            $query = $repository->createQueryBuilder('p')
                ->where('p.stock > :stock')
                ->setParameter('stock', '0')
                ->andWhere('p.id IN (:ints)')
                ->setParameter('ints', array_keys($tabCookie), \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                ->getQuery();

            $panierProducts = $query->getResult();
        }else{
            $tabCookie = null;
            $panierProducts = null;
        }

        return $this->render('gourmandises.html.twig', [
            'products' => $products,
            'nbpage' => ceil($countPage),
            'page' => $page,
            'cookiepanier' => $tabCookie,
            'panier' => (count($panierProducts) > 0 ? $panierProducts : null),
            'paniervide' => ($request->query->get('panier') == "vide" ? true : false),
            'confcommande' => ($request->query->get('commande') == "valide" ? true : false),
        ]);
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function articles(Request $request): Response
    {
        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }

        $repository = $this->getDoctrine()->getRepository(Product::class);

        //----------------- GET LIST PRODUCT PAGINATE  ----------------------

        $query = $repository->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->andWhere('c.id IN (:ints)')
            ->setParameter('ints', [4, 2], \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
            ->setFirstResult(($page - 1) * getenv('LIMIT'))
            ->setMaxResults(getenv('LIMIT'))
            //->orderBy('p.price', 'ASC')
            ->getQuery();
        //return new Paginator($query);

        $products = $query->getResult();

        //----------------- GET NB PAGE ----------------------
        $queryCount = $repository->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->andWhere('c.id IN (:ints)')
            ->setParameter('ints', [4, 2], \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
            ->getQuery();

        $countProducts = $queryCount->getResult();
        $countPage = count($countProducts) / getenv('LIMIT');

        //----------------- GET PANIER ----------------------
        if(array_key_exists("commande", $_COOKIE) && count(get_object_vars(json_decode($_COOKIE["commande"]))) > 0) {
            $tabCookie = get_object_vars(json_decode($_COOKIE["commande"]));
            $query = $repository->createQueryBuilder('p')
                ->where('p.stock > :stock')
                ->setParameter('stock', '0')
                ->andWhere('p.id IN (:ints)')
                ->setParameter('ints', array_keys($tabCookie), \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                ->getQuery();

            $panierProducts = $query->getResult();
        }else{
            $tabCookie = null;
            $panierProducts = null;
        }

        return $this->render('articles.html.twig', [
            'products' => $products,
            'nbpage' => ceil($countPage),
            'page' => $page,
            'cookiepanier' => $tabCookie,
            'panier' => (count($panierProducts) > 0 ? $panierProducts : null),
        ]);
    }

    /**
     * @Route("/loterie", name="loterie")
     */
    public function loterie(Request $request): Response
    {

        $category = $request->query->get('category');

        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }

        $repository = $this->getDoctrine()->getRepository(Product::class);

        //----------------- GET LIST PRODUCT PAGINATE  ----------------------

        $query = $repository->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->andWhere('c.id IN (:ints)')
            ->setParameter('ints', [4], \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
            ->setFirstResult(($page - 1) * getenv('LIMIT'))
            ->setMaxResults(getenv('LIMIT'))
            //->orderBy('p.price', 'ASC')
            ->getQuery();
        //return new Paginator($query);

        $products = $query->getResult();

        //----------------- GET NB PAGE ----------------------
        $queryCount = $repository->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->andWhere('c.id IN (:ints)')
            ->setParameter('ints', [4], \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
            ->getQuery();

        $countProducts = $queryCount->getResult();
        $countPage = count($countProducts) / getenv('LIMIT');

        //----------------- GET PANIER ----------------------
        if(array_key_exists("commande", $_COOKIE) && count(get_object_vars(json_decode($_COOKIE["commande"]))) > 0) {
            $tabCookie = get_object_vars(json_decode($_COOKIE["commande"]));
            $query = $repository->createQueryBuilder('p')
                ->where('p.stock > :stock')
                ->setParameter('stock', '0')
                ->andWhere('p.id IN (:ints)')
                ->setParameter('ints', array_keys($tabCookie), \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                ->getQuery();

            $panierProducts = $query->getResult();
        }else{
            $tabCookie = null;
            $panierProducts = null;
        }

        return $this->render('loterie.html.twig', [
            'products' => $products,
            'nbpage' => ceil($countPage),
            'page' => $page,
            'cookiepanier' => $tabCookie,
            'panier' => (count($panierProducts) > 0 ? $panierProducts : null),
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        //----------------- GET PANIER ----------------------
        if(array_key_exists("commande", $_COOKIE) && count(get_object_vars(json_decode($_COOKIE["commande"]))) > 0) {
            $tabCookie = get_object_vars(json_decode($_COOKIE["commande"]));
            $repository = $this->getDoctrine()->getRepository(Product::class);
            $query = $repository->createQueryBuilder('p')
                ->where('p.stock > :stock')
                ->setParameter('stock', '0')
                ->andWhere('p.id IN (:ints)')
                ->setParameter('ints', array_keys($tabCookie),\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                ->getQuery();

            $panierProducts = $query->getResult();
        }else{
            $panierProducts = null;
            $tabCookie = null;
        }

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reqPost = $form->getData();

            $message = (new \Swift_Message('Demande infos March� de noel : '. $reqPost['nom'] . ' '. $reqPost['prenom']))
                ->setFrom($reqPost['email'])
                ->setTo("fcoelho92@hotmail.com")
                ->setBody(
                    "<p>" . $reqPost['sujet'] . "</p>" . "<p>" . $reqPost['message'] . "</p>",
                    'text/html'
                )
            ;

            $mailer->send($message);

            return $this->redirectToRoute("contact",array("mail" => "envoyer"),302);
        }
        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
            'cookiepanier' => $tabCookie,
            'panier' => (count($panierProducts) > 0 ? $panierProducts : null),
            'confenvoi' => ($request->query->get('mail') == "envoyer" ? true : false),
        ]);
    }

    /**
     * @Route("/getpanier", name="getpanier")
     */
    public function getpanier(Request $request)
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
            $htmlPanier = "";
            foreach($products as $product){
                $htmlPanier .= '<div class="clear"></div><br>';
                $htmlPanier .= '<li class="list_img"><img src="images/'.$product->getPhoto().'" alt="'.$product->getName().'" width="60"/></li>';
                $htmlPanier .= '<li class="list_desc">';
                $htmlPanier .= '<p>'.$product->getName().'</p>';
                $htmlPanier .= '<p>'.$req[$product->getId()].' x '.$product->getPrice().' &euro;</p>';
                $htmlPanier .= '</li>';
                $htmlPanier .= '<li class="closepanier" data-id="'.$product->getId().'"><a href="#" title="Supprimer"><img src="images/close_edit.png" alt="Supprimer"/></a></li>';
            }

            return new Response($htmlPanier, 200, array('Access-Control-Allow-Origin'=> 'noel.diaconat-grenoble.org'));
        } else {
            return new Response("Erreur : ce n'est pas une requete Ajax", 400);
        }

    }

    /**
     * @Route("/commande", name="commande")
     */
    public function commande(Request $request, \Swift_Mailer $mailer)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        //----------------- GET PANIER ----------------------
        if(array_key_exists("commande", $_COOKIE) && count(get_object_vars(json_decode($_COOKIE["commande"]))) > 0) {
            $tabCookie = get_object_vars(json_decode($_COOKIE["commande"]));
            $query = $repository->createQueryBuilder('p')
                ->where('p.stock > :stock')
                ->setParameter('stock', '0')
                ->andWhere('p.id IN (:ints)')
                ->setParameter('ints', array_keys($tabCookie), \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
                ->getQuery();

            $panierProducts = $query->getResult();
            $tabpanierProducts = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }else{
            return $this->redirectToRoute("gourmandises",array("panier" => "vide"),302);
        }

        $form = $this->createForm(CommandeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reqPost = $form->getData();
            $order = new Order();
            $order->setName($reqPost['nom']);
            $order->setLastName($reqPost['prenom']);
            $order->setEmail($reqPost['email']);
            $order->setLivraison(new \DateTime($reqPost['livraison']));
            $order->setTel($reqPost['tel']);

            $entityManager = $this->getDoctrine()->getManager();
            $price = 0;
            $tabNumTicket = [];

            foreach($panierProducts as $product){
                $quantity = $tabCookie[$product->getId()];

                $orderp = new OrderProduct();
                $orderp->setOrder($order);
                $orderp->setProduct($product);
                $orderp->setAmount($quantity);
                $entityManager->persist($orderp);
                // set stock product
                $product->setStock($product->getStock() - $quantity);
                // cacule price commande
                $price += $product->getPrice() * $tabCookie[$product->getId()];

                // create loterie ticket if in commade
                $tabLoterie = [18 => 1, 19 => 5, 20 => 10, 21 => 20];
                if(array_key_exists($product->getId(), $tabLoterie)){
                    for ($i = 1; $i <= $tabLoterie[$product->getId()] * $tabCookie[$product->getId()]; $i++) {
                        $ticket = new Lotterie();
                        $ticket->setName($reqPost['nom']);
                        $ticket->setLastName($reqPost['prenom']);
                        $ticket->setEmail($reqPost['email']);
                        $ticket->setTirage(new \DateTime($reqPost['livraison']));

                        $ticketExist = true;
                        while($ticketExist != NULL){
                            $numTicket = rand(0000,9999);

                            $repository = $this->getDoctrine()->getRepository(Lotterie::class);
                            $ticketExist = $repository->findOneBy(['ticket' => $numTicket]);
                        }

                        $tabNumTicket[] = $numTicket;
                        $ticket->setTicket($numTicket);
                        $entityManager->persist($ticket);

                    }
                }

            }

            $order->setPrice($price);
            $entityManager->persist($order);

            $entityManager->flush();

            $date = date_format(new \DateTime('now'), 'd/m/Y H:i');
            $message = (new \Swift_Message('Confirmation de commande March� de no�l du '. $date))
                ->setFrom('contact@diaconat-grenoble.org')
                ->setTo($reqPost['email'])
                ->setBody(
                    $this->renderView(
                        'emails/reservation.html.twig',
                        [
                            'price' => [$tabpanierProducts, $tabCookie, $tabNumTicket],
                            'nom' => $reqPost['nom'],
                            'prenom' => $reqPost['prenom'],
                            'orderId' => $order->getId(),
                            'livraison' => new \DateTime($reqPost['livraison']),
                        ]
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);

            return $this->redirectToRoute("gourmandises",array("commande" => "valide"),302);
        }

        return $this->render('commande.html.twig', [
            'form' => $form->createView(),
            'cookiepanier' => (isset($tabCookie) ? $tabCookie : null),
            'panier' => (isset($panierProducts) && count($panierProducts) > 0 ? $panierProducts : null),
        ]);
    }
}
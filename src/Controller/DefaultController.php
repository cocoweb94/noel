<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
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
        var_dump(count($countProducts));die;

        return $this->render('boutique.html.twig', [
            'products' => $products,
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
}
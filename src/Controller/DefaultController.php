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
        $repository = $this->getDoctrine()->getRepository(Product::class);
        //$products = $repository->findAll();

        $query = $repository->createQueryBuilder('p')
            ->where('p.stock > :stock')
            ->setParameter('stock', '0')
            ->setFirstResult((2 - 1) * 8)
            ->setMaxResults(8)
            //->orderBy('p.price', 'ASC')
            ->getQuery();
        //return new Paginator($query);

        $products = $query->getResult();
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
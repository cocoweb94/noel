<?php
// src/Controller/OrderController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Tools\Price;
use App\Tools\Mail;
use App\Tools\Paypal;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\Type\ContactType;
use App\Form\Type\ReservationType;


class OrderController extends AbstractController
{

    private $mailer;

    /*public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }*/

    /**
     * @Route("/createOrder", name="createOrder", methods={"POST"})
     */
    public function createOrder(Request $request)
    {
        header('Access-Control-Allow-Origin: easycabservices.com');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Max-Age: 1000');

        $req = json_decode(urldecode($request->getContent()), true);
        if($_SESSION["price"]["idtarifs"] == "i" || $_SESSION["price"]["idtarifs"] == "a" || $_SESSION["price"]["idtarifs"] == "g" || $_SESSION["price"]["idtarifs"] == "m") {
            $objPrice = new Price($_SESSION["price"]['idservices'], $_SESSION["price"]['idtarifs'], $_SESSION["price"]['idformule']);
            $price = $objPrice->findPrice();

            if(!isset($price) && $price['prix'] == $req['p']){
                return new Response(
                    json_encode($req['p']),
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            } else {
                $paypal = new Paypal();
                $res = $paypal->order($req['p']);
                return new JsonResponse($res, 200, array('Access-Control-Allow-Origin' => 'easycabsercives.com'));
            }
        }else{
            return new Response(
                json_encode($_SESSION["price"]["idtarifs"]),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }
    }

    /**
     * @Route("/captureOrder/{id}", name="captureOrder", methods={"POST"})
     */
    public function captureOrder($id, Request $request):Response
    {
        header('Access-Control-Allow-Origin: easycabservices.com');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Max-Age: 1000');

        $paypal = new Paypal();
        $res = $paypal->capture($id);

        if($res){
            $tabRes = get_object_vars($res);
            $payer = get_object_vars($tabRes['payer']);
            $name = get_object_vars($payer['name']);
            $email = $payer['email_address'];
            $payerId = $payer['payer_id'];
            $req = json_decode(urldecode($request->getContent()), true);
            $mail = new Mail("fcoelho92@hotmail.com", $_SESSION["price"]);
            $sendMail = $mail->sendReservationMail($name['surname'], $name['given_name'], $payerId, $req['distance'], $req['temps']);
            $this->mailer->send($sendMail);
            unset($_SESSION["price"]);

        }else{
            return new Response(
                json_encode($res),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        return new Response(
            json_encode($res),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
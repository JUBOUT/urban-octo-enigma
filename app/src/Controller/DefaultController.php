<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Form\PhoneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class DefaultController extends AbstractController
{
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem("Home", $this->get("router")->generate("index"));
        
        $phone = new Phone();

        $form = $this->createForm(PhoneType::class, $phone);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phone = $form->getData();
            // $httpClient = HttpClient::create();

            // a tester directement avec curl
            // $response = $httpClient->request(
            //     'POST',
            //     'http://163.172.67.144:8042/api/v1/validate',
            //     [
            //         'auth_basic' => ['api', 'azpihviyazfb'],
            //         'headers' => ['Content-Type' => 'application/json'],
            //         'json' => ['phoneNumber' => $phone->getPhoneNumber(), 'countryCode' => $phone->getCountry()->getCode()],
            //     ]
            // );

            
            // if($response->getStatusCode()  == 200) {
            //     $content = json_encode($response->getContent());
            //     $content['international'] = "test";
            //     $phone->setPhoneInternational($content['international']);
            //     $entityManager = $this->getDoctrine()->getManager();
            //     $entityManager->persist($phone);
            //     $entityManager->flush();
            //     return $this->redirectToRoute('success');
            // }

            $payload = ['phoneNumber' => $phone->getPhoneNumber(), 'countryCode' => $phone->getCountry()->getCode()];
            $s = curl_init();
            $options = [
                CURLOPT_URL => '163.172.67.144:8042/api/v1/validate',
                CURLOPT_HEADER => true,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload),
                    'Authorization: Basic '. base64_encode("api:azpihviyazfb")
                ),
                CURLOPT_ENCODING => "application/json",
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
            ];
            
            curl_setopt_array($s, $options);
            $content = curl_exec($s);
            $info = curl_getinfo($s);
            
            if($info['http_code'] == 200){
                $content = json_encode($content);
                $array = ['international' => 'test'];
                $phone->setPhoneNumberInternational($array['international']);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($phone);
                $entityManager->flush();
                curl_close($s);
                return $this->redirectToRoute('success');
            }else{
                return $this->redirectToRoute('failed');

            }

        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/success", name="success")
     */
    public function success(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem("Success", $this->get("router")->generate("success"));

        return $this->render('default/success.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }


    /**
    * @Route("/failed", name="failed")
    */
    public function failed(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem("failed", $this->get("router")->generate("failed"));

        return $this->render('default/failed.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }

    /**
     * @Route("/list", name="list")
     */
    public function list(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem("List", $this->get("router")->generate("list"));

        $entityManager = $this->getDoctrine()->getManager();
        $phoneHeader = $entityManager->getClassMetadata(Phone::class);
        $phoneData = $this->getDoctrine()->getRepository(Phone::class)->findAll();
        
        return $this->render('default/list.html.twig', [
            'phone_headers' => $phoneHeader->getReflectionProperties(),
            'phones' => $phoneData
        ]);
    }
    
}

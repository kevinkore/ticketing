<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Ticket;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    //Home user

    /**
     * @Route("/" , name="home")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function home()
    {
        $user= $this->getUser();
        return $this->render("pages/home.html.twig",[
            'user'=> $user   
        ]); 
    }


    //Home administateur

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminHome(): Response
    {
        $user= $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $tickets = $repository->findAll();
        return $this->render('pages/adminHome.html.twig',[
            'user'=> $user,
            'tickets' => $tickets
        ]);
    }
    /**
     * @Route("/user/modify", name="modify_information")
     * 
     * @IsGranted("ROLE_USER")
     * @return Response
     * 
     */
    public function modify( Request $request, EntityManagerInterface $em)
    {
        
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('userInformation/user.html.twig', [
            'Form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }
}

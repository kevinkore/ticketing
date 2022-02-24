<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Ticket;
use DateTimeImmutable;
use App\Entity\Message;
use App\Form\TicketType;
use App\Form\MessageType;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TicketController extends AbstractController
{
    //creation du ticket

    /**
     * @Route("/ticket", name="ticket")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     * 
     */
    public function createTicket(Request $request)
    {
        $ticket= new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
        $user = $this->getUser() ;

        if ($form->isSubmitted() && $form->isValid()) {
            
            $ticket->setAuthor($this->getUser())
                   ->setOpenDate(new DateTimeImmutable())
                   ->setTicketState(true)
                   ->setClosingDate(new DateTimeImmutable());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();
           
            $this->addFlash('success', 'votre ticket a été ouvert avec succès');
            return $this->redirectToRoute('history');
        }

        return $this->render('ticket/ticket.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    //Details du ticket

    /**
     * 
     * @Route("/ticket/details/{id}", name="ticket_details") 
     * @IsGranted("ROLE_USER")
     *@return Response 
     */
    public function ticketDetails(Request $request,TicketRepository $ticketRepository , int $id)
    {
        $message= new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        $ticket=$ticketRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $message->setAuthor($this->getUser())
                    ->setDate(new DateTimeImmutable())
                    ->setTicketMessage($ticket);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
           
            $this->addFlash('success', 'Your email address has been verified.');
            return $this->redirectToRoute('ticket_details', array('id' => $ticket->getId()));
        }
        
        if (!$ticket) {
            throw $this->createNotFoundException(
                'No ticket found for id '.$id
            );
        }
        return $this->render('ticket/details.html.twig', [
            'form'=>$form->createView(),
            'ticket'=> $ticket
        ]);
    }


    //Historique de ticket

    /** 
     * @Route("/history" , name="history")
     * 
     * 
     * @return Response
    */
    public function ticketHistory()
    {  
        $user = $this->getUser() ; 
        return $this->render("ticket/history.html.twig",[
            'tickets'=> $user->getTickets()  
        ]);
    }


    //ticket en attente

    /**
     * @Route("/admin/pending", name="pending")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     * 
     */
    public function pendingTicket()
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $tickets = $repository->findAll();
        return $this->render("admin/pending.html.twig",[
            'tickets'=> $tickets,
        ]);
    }

    //fermer ticket

    /**
     * @Route("/ticket/closing/{id}", name="closing")
     * 
     * @return Response
     * 
     */
    public function closing(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ticket = $entityManager->getRepository(Ticket::class)->find($id);

        if (!$ticket) {
            throw $this->createNotFoundException(
                'No ticket found for id '.$id
            );
        }

        $ticket->setTicketState(false)
               ->setClosingDate(new DateTimeImmutable());
        $entityManager->flush();

        return $this->redirectToRoute('ticket_details', [
            'id' => $ticket->getId()
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Ticket;
use DateTimeImmutable;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class MessageController extends AbstractController
{
    /**
     * @Route("/admin/message/{id}", name="message")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function adminMessage(Request $request, TicketRepository $ticketRepository , int $id)
    {
         
        $adminMessage= new Message();
        $form = $this->createForm(MessageType::class, $adminMessage);
        $form->handleRequest($request);
        $findAdminMessage = $ticketRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $adminMessage->setAuthor($this->getUser())
                    ->setDate(new DateTimeImmutable())
                    ->setTicketMessage($findAdminMessage);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adminMessage);
            $entityManager->flush();
           

            
        }

        return $this->render('admin/message.html.twig', [
            'form' => $form->createView(),
            'ticket' => $findAdminMessage
        ]);
    }
    
    //fermer ticket (admin)

    /**
     * @Route("/admin/closing/{id}", name="admin_closing")
     * @IsGranted("ROLE_ADMIN")
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

        return $this->redirectToRoute('message', [
            'id' => $ticket->getId()
        ]);
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Ticket;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encodeur;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encodeur = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        //nous gerons les roles

        $adminRole = new Role();
        $adminRole -> setTitle("ROLE_ADMIN");
        $manager->persist($adminRole);

        $adminUser = new User();

        $hash = $this->encodeur->encodePassword( $adminUser,'password');

        $adminUser->setFirstname("kore")
                  ->setLastname("kevin")
                  ->setEmail("korenahounoukevin@gmail.com")
                  ->setHash($hash)
                  ->setTelephone('0779557658')
                  ->setSociety("Veone")
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);

         //Nous gerons nos messages

         $message= new Message();
         $message->setTopic("jai plus de sourcis")
                 ->setDate(new DateTimeImmutable());
         $manager->persist($message);

        //Nous gerons les tickets

        $ticket= new Ticket();
        $ticket->setObject("jai plus de sourcis")
               ->setSubject("bonjour")
               ->setOpenDate(new DateTimeImmutable())
               ->setClosingDate(new DateTimeImmutable())
               ->setTicketState(true)
               ->addMessage($message);
        $manager->persist($ticket);

        //Nous gerons nos utilisateurs

        $user = new User();

        $hash = $this->encodeur->encodePassword( $user,'password');

        $user->setFirstname("kore")
             ->setLastname("kevin")
             ->setEmail("korenahounoukevin@gmail.com")
             ->setHash($hash)
             ->setTelephone('0779557658')
             ->setSociety("Veone")
             ->addMessage($message)
             ->addTicket($ticket);
        $manager->persist($user);

        $manager->flush();
    }
}
<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Form\CommandeType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController{

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route("/admin/commande/new", name:"commande_new")]
    #[Route("/admin/commande/update/{id}", name:"commande_update")]
    public function new(Request $request, Commande $commande = null):Response{

        //si /admin/commande/new => $commande = null
        //si /admin/commande/update/{id} => $commande = $em->getRepository(Commande::class)->find($id); donc $commande = { }
        if($commande === null){
            $now = new DateTime();
            //$now->add(new \DateInterval("PT1H"));
            //$now->format("Y-m-d H:i");

            $tomorrow = new DateTime("+ 1 days");
            //$tomorrow->add(new \DateInterval("PT1H"));
            //$tomorrow->format("Y-m-d H:i");

            $commande = new Commande;
            /*$commande->setDateHeureDepart($now)
                     ->setDateHeureFin($tomorrow);*/
        }

        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $dt_debut = $form->get("dateHeureDepart")->getData();
            $dt_fin = $form->get("dateHeureFin")->getData();
            $interval = $dt_debut->diff($dt_fin);
            $nbJours = $interval->format("%d");
            $nbJours = $interval->days;

            if($nbJours < 1){
                $this->addFlash("message", "une réservation doit durée 24h au minimum");
                //$this->addFlash("message", "une réservation doit durée 24h au minimum");
                //return $this->redirectToRoute("commande_new");
            }

            $listevehiculeLoue = $this->em->getRepository(Commande::class)->listVehiculeLoue($dt_debut ,$dt_fin );
            $vehicule = $form->get("vehicule")->getData();

            if(in_array($vehicule->getId() , $listevehiculeLoue)){

                $listevehiculeDisponible = $this->em->getRepository(Vehicule::class)->findByVehiculeDisponibles($listevehiculeLoue );
                $this->addFlash("message" , "le véhicule demandé est déjà réservé");
                $this->addFlash("vehicules" , ["disponibles" => $listevehiculeDisponible] );
            }

            //dd($listevehiculeLoue , $listevehiculeDisponible); 

            if(!in_array( $vehicule->getId() , $listevehiculeLoue) && $nbJours >= 1){
                $prixJournalier = $vehicule->getPrixJournalier();

                $commande->setPrixTotal($nbJours * $prixJournalier);
                $this->em->persist($commande);
                $this->em->flush();
                return $this->redirectToRoute("commande_list");
            }
        }

        //récuperer dt_debut

        return $this->render("commande/new.html.twig", [
            "form" => $form->createView(),
            "id" => $commande->getId()
        ]);
    }

    #[Route("/admin/commande/list", name:"commande_list")]
    public function list():Response{
        $commandes = $this->em->getRepository(Commande::class)->findAll();

        return $this->render("commande/list.html.twig", compact("commandes"));
    }

    /*#[Route("/admin/commande/update/{id}", name:"commande_update")]
    public function update($id){
        
    }*/

    /* #[Route("/admin/commande/suppr", name:"commande_suppr")]
    public function delete(){
        $commandeASupprimer = $this->em->getRepository(Commande::class)->find($id);

        if($commandeASupprimer !== null){
            $this->em->remove($commandeASupprimer);
            $this->em->flush();
        }
        return $this->redirectToRoute("commande_list");
    }*/

    //Param converter(Autre méthod)
    #[Route("/admin/commande/suppr/{id}", name:"commande_suppr")]
    public function delete(Commande $commandeASupprimer){

        if($commandeASupprimer !== null){
            $this->em->remove($commandeASupprimer);
            $this->em->flush();
        }
        return $this->redirectToRoute("commande_list");
    }
}
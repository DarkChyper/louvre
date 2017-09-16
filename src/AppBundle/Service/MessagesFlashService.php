<?php


namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Service permattant de mettre en session des messages flash Ã  destination de l'utilisateur
 */
class MessagesFlashService
{
    protected $session;
    protected $success;
    protected $warning;
    protected $error;
    protected $info;

    /**
     * Contructor
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->success = "success";
        $this->warning = "warning";
        $this->error   = "error";
        $this->info    = "info";
    }

    /**
     * @param String $message
     */
    public function messageSuccess($message){
        $this->addMessage($message,$this->success);
    }

    /**
     * @param String $message
     */
    public function messageWarning($message){
        $this->addMessage($message,$this->warning);
    }

    /**
     * @param String $message
     */
    public function messageError($message){
        $this->addMessage($message,$this->error);
    }

    /**
     * @param String $message
     */
    public function messageInfo($message){
        $this->addMessage($message,$this->info);
    }

    /**
     * Add $message in session FlashBag system with $label
     *
     * @param String $message
     * @param String $label
     */
    private function addMessage($message, $label) {
        $this->session->getFlashBag()->add($label, $message);
    }


}



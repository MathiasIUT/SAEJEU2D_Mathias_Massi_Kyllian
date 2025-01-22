<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_messagerie extends View {
    public function afficherMessages($messages) {
        $this->render('messagerie/liste', ['messages' => $messages]);
    }
    
    public function afficherFormulaire($data = []) {
        $this->render('messagerie/nouveau', $data);
    }
    
    public function afficherMessage($message) {
        $this->render('messagerie/message', ['message' => $message]);
    }
}
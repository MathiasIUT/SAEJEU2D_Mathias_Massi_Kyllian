<?php
class View {
    private $template;
    private $viewsPath;
    
    public function __construct($template = 'template') {
        $this->template = $template;
        $this->viewsPath = ROOT_PATH . '/views/';
    }
    
    public function render($view, $data = []) {
        // Extraction des données pour les rendre disponibles dans la vue
        extract($data);
        
        // Vérification de l'existence du fichier de vue
        $viewFile = $this->viewsPath . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("Vue non trouvée: " . $view);
        }
        
        // Capture du contenu de la vue
        ob_start();
        try {
            include($viewFile);
            $content = ob_get_clean();
        } catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }
        
        // Vérification de l'existence du template
        $templateFile = $this->viewsPath . $this->template . '.php';
        if (!file_exists($templateFile)) {
            throw new Exception("Template non trouvé: " . $this->template);
        }
        
        // Inclusion du template avec le contenu
        include($templateFile);
    }
    
    protected function escape($value) {
        if (is_array($value)) {
            return array_map([$this, 'escape'], $value);
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
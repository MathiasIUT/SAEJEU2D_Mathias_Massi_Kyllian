package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import javafx.scene.input.MouseButton;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.modele.*;
import universite_paris8.iut.mcontay.saejeu2d.vue.*;
import java.io.IOException;
import java.net.URL;
import java.util.HashSet;
import java.util.ResourceBundle;
import java.util.Set;

/* La classe Controleur initialise l'environnement de jeu, les vues associées aux entités et objets, et configure la boucle de jeu pour les mises à jour périodiques.
 Elle gère les interactions utilisateur via les événements clavier et souris, permettant le mouvement du joueur et l'attaque.
  La méthode initialize configure les éléments du jeu, tandis que mouvement et keyPressed traitent les pressions de touches pour les déplacements.
  les interactions avec les objets proches. La méthode gererClicSouris gère les clics de souris pour les attaques.
   Update met à jour l'état du jeu en fonction des touches pressées.
 Les inventaires et dialogues sont également gérés via des méthodes dédiées.*/

public class Controleur implements Initializable {

    @FXML
    private Pane pane;

    private Environnement environnement;
    private Inventaire inventaire;
    private InventaireVue inventaireVue;
    private TerrainVue vue;
    private Joueur joueur;
    private JoueurVue joueurVue;
    private Monstre monstre;
    private MonstreVue monstreVue;
    private Skateur skateur;
    private SkateurVue skateurVue;

    private Bouclier bouclier;
    private BouclierVue bouclierVue;

    private Epee epee;
    private EpeeVue epeeVue;

    private VieVue vieVue;

    private Set<KeyCode> keysPressed = new HashSet<>();
    private GameLoop gameLoop;


    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        environnement = new Environnement();
        try {
            vue = new TerrainVue(pane, environnement.getTerrain());
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
        vue.affichageVue();

        joueur = environnement.getJoueur();
        monstre = environnement.getMonstre();
        skateur = environnement.getSkateur();

        epee = environnement.getEpee();
        bouclier = environnement.getBouclier();

        joueurVue = new JoueurVue(pane, environnement);
        monstreVue = new MonstreVue(pane, environnement.getMonstre());
        skateurVue = new SkateurVue(pane, skateur);
        epeeVue = new EpeeVue(pane, epee);
        bouclierVue = new BouclierVue(pane, bouclier);

        inventaire = joueur.getInventaire();
        inventaireVue = new InventaireVue(pane, inventaire, joueur, epee, environnement);

        vieVue = new VieVue(pane, joueur);

        pane.setFocusTraversable(true);
        pane.requestFocus();

        pane.addEventFilter(MouseEvent.MOUSE_CLICKED, this::gererClicSouris);

        // Initialiser la cam avec la scène puis le joueur
        //double sceneWidth = 1280; // largeur de  scène
        //double sceneHeight = 960; // la hauteur de  scène

        // Vérifiez que les propriétés de largeur et de hauteur du terrain sont bien initialisées
        if (environnement.getTerrain().widthProperty().get() == 0 || environnement.getTerrain().heightProperty().get() == 0) {
            throw new IllegalStateException("Les propriétés de terrain doivent être initialisées correctement");
        }



        gameLoop = new GameLoop(environnement, this::update);
        gameLoop.initGameLoop();
    }

    public Joueur getJoueur() {
        return joueur;
    }

    public void setJoueurEtSkateur(Joueur joueur, Skateur skateur) {
        this.joueur = joueur;
        this.skateur = skateur;
    }

    public void mouvement(KeyEvent e) {
        KeyCode keyCode = e.getCode();
        if (e.getEventType() == KeyEvent.KEY_PRESSED) {
            keyPressed(keyCode);
        } else if (e.getEventType() == KeyEvent.KEY_RELEASED) {
            keyReleased(keyCode);
        }
    }

    public void keyPressed(KeyCode keyCode) {
        if (joueur.estMortProperty().get()) {
            return;
        }

        keysPressed.add(keyCode);
        if (keyCode == KeyCode.E) {
            for (Objet objet : environnement.getListeObjets()) {
                if (joueur.estProcheObjet(objet)) {
                    joueur.getInventaire().ajouterObjet(objet);

                    // Trouver et supprimer dynamiquement la vue associée
                    pane.getChildren().removeIf(node -> {
                        if (node instanceof ImageView) {
                            ImageView imageView = (ImageView) node;
                            return imageView.getImage() == objet.getImage();
                        }
                        return false;
                    });

                    environnement.getListeObjets().remove(objet);
                    System.out.println("Objet récupéré: " + objet.getNom());
                    break;
                }
            }
            inventaireVue.afficherInventaire();
        } else if (keyCode == KeyCode.H) {
            joueur.initierDialogue(skateur, pane);
        }
    }

    public void keyReleased(KeyCode keyCode) {
        keysPressed.remove(keyCode);
        joueur.deplacementStop();
    }

    private void update() {
        boolean deplace = false;
        if (keysPressed.contains(KeyCode.Z)) {
            joueur.deplacementHaut();
            deplace = true;
        }
        if (keysPressed.contains(KeyCode.Q)) {
            joueur.deplacementGauche();
            deplace = true;
        }
        if (keysPressed.contains(KeyCode.S)) {
            joueur.deplacementBas();
            deplace = true;
        }
        if (keysPressed.contains(KeyCode.D)) {
            joueur.deplacementDroite();
            deplace = true;
        }

        // Mettre à jour la position de la caméra à chaque mise à jour
    }

    private void gererClicSouris(MouseEvent event) {
        if (event.getButton() == MouseButton.SECONDARY && !joueur.estMortProperty().get()) {
            joueurVue.VueAttaque(joueur.getDirection());
        }
    }
}

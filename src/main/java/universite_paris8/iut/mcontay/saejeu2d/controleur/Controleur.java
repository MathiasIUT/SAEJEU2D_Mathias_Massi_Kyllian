package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.scene.input.KeyEvent;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.modele.GameLoop;
import universite_paris8.iut.mcontay.saejeu2d.modele.Personnage;
import universite_paris8.iut.mcontay.saejeu2d.vue.JoueurVue;
import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.TilePane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.net.URL;
import java.util.ResourceBundle;
import java.util.HashSet;
import java.util.Set;

public class Controleur implements Initializable {



    @FXML
    private Pane pane;
    @FXML
    private TilePane tilePane;
    @FXML
    private Pane ptsDeVie;


    private Terrain terrain;
    private TerrainVue vue;
    private Joueur joueur;
    private JoueurVue joueurVue;
    private Personnage personnage ;

    private Set<KeyCode> keysPressed = new HashSet<>();
    private GameLoop gameLoop;

    public Joueur getJoueur() {
        return this.joueur;
    }
    public Personnage getPersonnage() {
        return  this.personnage ;
    }

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        terrain = new Terrain();
        joueur = new Joueur(terrain, 256, 256, "Joueur1", "100", 1);
        personnage = new Personnage("pnj1", "100", "33", "50",1, 100, 100);
        joueurVue = new JoueurVue(pane, joueur);
        this.vue = new TerrainVue(tilePane, terrain);
        this.vue.affichageVue();
        this.gameLoop = new GameLoop(joueur);
        gameLoop.initGameLoop();
    }

    public void mouvement(KeyEvent e) {
        KeyCode keyCode = e.getCode();
        if (e.getEventType() == KeyEvent.KEY_PRESSED) {
            keyPressed(keyCode);
        } else if (e.getEventType() == KeyEvent.KEY_RELEASED) {
            keyReleased(keyCode);
        }
    }

    private void update() {

        if (keysPressed.contains(KeyCode.Z)) {
            joueur.deplacementHaut();
            joueurVue.changerImage(KeyCode.Z);
        }
        if (keysPressed.contains(KeyCode.Q)) {
            joueur.deplacementGauche();
            joueurVue.changerImage(KeyCode.Q);
        }
        if (keysPressed.contains(KeyCode.S)) {
            joueur.deplacementBas();
            joueurVue.changerImage(KeyCode.S);
        }
        if (keysPressed.contains(KeyCode.D)) {
            joueur.deplacementDroite();
            joueurVue.changerImage(KeyCode.D);
        }

    }

    public void keyPressed(KeyCode keyCode) {
        keysPressed.add(keyCode);
        update();
    }

    public void keyReleased(KeyCode keyCode) {
        keysPressed.remove(keyCode);
        update();
        joueur.deplacementStop();
    }

}

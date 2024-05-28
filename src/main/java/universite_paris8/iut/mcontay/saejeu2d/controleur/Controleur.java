package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.scene.input.KeyEvent;
import javafx.scene.layout.Pane;
//import universite_paris8.iut.mcontay.saejeu2d.modele.GameLoop;
//import universite_paris8.iut.mcontay.saejeu2d.modele.Gameloop;
import universite_paris8.iut.mcontay.saejeu2d.modele.GameLoop;
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
    private Terrain terrain;
    @FXML
    private Pane pane;
    @FXML
    private TilePane tilePane;
    private TerrainVue vue;
    private Joueur joueur;
    private JoueurVue joueurVue;
    private Set<KeyCode> keysPressed = new HashSet<>();
    private GameLoop gameLoop;


    public Joueur getJoueur() {
        return this.joueur;
    }

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        terrain = new Terrain();
        joueur = new Joueur(terrain, 256, 256);
        joueurVue = new JoueurVue(pane, joueur);
        this.vue = new TerrainVue(tilePane, terrain);
        this.vue.affichageVue();
        this.gameLoop = new GameLoop(joueur);
        gameLoop.initGameLoop();


    }

    public void mouvement(KeyEvent e) {

            KeyCode keyCode = e.getCode();
            if (e.getEventType() == KeyEvent.KEY_PRESSED) {
                if (e.getCode() == KeyCode.Z) {
                    joueur.deplacementHaut();
                } else if (e.getCode() == KeyCode.Q) {
                    joueur.deplacementGauche();
                } else if (e.getCode() == KeyCode.S) {
                    joueur.deplacementBas();
                } else if (e.getCode() == KeyCode.D) {
                    joueur.deplacementDroite();
 //               keysPressed.add(keyCode);
                } else {
                }
            } else if (e.getEventType() == KeyEvent.KEY_RELEASED) {
                joueur.deplacementStop();
//                keysPressed.remove(keyCode);
            }

            joueur.changerDirection();


    }
}

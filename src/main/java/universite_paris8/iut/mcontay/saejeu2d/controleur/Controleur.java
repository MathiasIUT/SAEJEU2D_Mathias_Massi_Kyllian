package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import javafx.scene.layout.Pane;
import javafx.scene.layout.TilePane;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.modele.GameLoop;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;
import universite_paris8.iut.mcontay.saejeu2d.vue.JoueurVue;
import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;

import java.io.IOException;
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
    private GameLoop gameLoop ;

    // Ensemble pour garder une trace des touches enfoncées


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
        this.gameLoop = new GameLoop(joueur, joueurVue, keysPressed);
        gameLoop.initGameLoop();


    }

    public void mouvement(KeyEvent e) {
        KeyCode keyCode = e.getCode();
        if (e.getEventType() == KeyEvent.KEY_PRESSED) {
            keysPressed.add(keyCode);
        } else if (e.getEventType() == KeyEvent.KEY_RELEASED) {
            keysPressed.remove(keyCode);
        }
        if (e.getCode() != KeyCode.Z && e.getCode() != KeyCode.Q &&  e.getCode() != KeyCode.S &&  e.getCode() != KeyCode.D ){
            System.out.println("Appuie sur Z pour le haut, S pour le bas, Q pour la gauche, D pour la droite");
        }
        // calculer la nouvelle direction
        // joueur.changerDirection(nouvelledsirection)

    }


}

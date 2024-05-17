package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyEvent;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.vue.JoueurVue;
import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.layout.TilePane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;

import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import javafx.scene.input.KeyCode;

public class Controleur implements Initializable {

    private static final double MOVE_DISTANCE = 5;
    @FXML
    private universite_paris8.iut.mcontay.saejeu2d.modele.Terrain terrain;
    @FXML
    private Pane pane;
    @FXML
    private TilePane TilePane;
    private TerrainVue vue;
    private Joueur joueur;

    private JoueurVue joueurVue;


    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        terrain = new Terrain();
        joueur = new Joueur(terrain, 0, 0);
        joueurVue = new JoueurVue(pane,joueur);
        this.vue = new TerrainVue(TilePane, terrain);
        this.vue.affichageVue();

    }

    public void mouvement(KeyEvent e) {
        if (e.getCode() == KeyCode.Z) {
            joueur.seDeplaceHaut();
        } else if (e.getCode() == KeyCode.Q) {
            joueur.seDeplaceGauche();
        } else if (e.getCode() == KeyCode.S) {
            joueur.seDeplaceBas();
        } else if (e.getCode() == KeyCode.D) {
            joueur.seDeplaceDroite();
        }
    }

}



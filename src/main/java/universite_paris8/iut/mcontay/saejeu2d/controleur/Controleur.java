package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.scene.input.KeyEvent;
import javafx.scene.layout.Pane;
import javafx.scene.layout.StackPane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.layout.TilePane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;

import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;

public class Controleur implements Initializable {

    private static final double MOVE_DISTANCE = 5;
    @FXML
    private universite_paris8.iut.mcontay.saejeu2d.modele.Terrain Terrain; //TODO un attribut commmence par une minuscule

    @FXML
    private Pane pane;

    @FXML
    private TilePane TilePane;

    private TerrainVue vue;
    private static Joueur joueur;


    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        Terrain = new Terrain();
        this.vue = new TerrainVue(TilePane, Terrain);
        this.vue.affichageVue();


        // Initialisation du sprite

        joueur = new Joueur(Terrain, 0, 0);


        //TODO déplacer dans une classe JoueurVue
        Image spriteImage = null;
        try {
            spriteImage = new Image(Lanceur.class.getResource("joueurDeboutFace.png").openStream());
        } catch (IOException e) {
            throw new RuntimeException(e);
        }

        ImageView spriteView = new ImageView(spriteImage);
        spriteView.setFitHeight(35);
        spriteView.setFitWidth(35);
        spriteView.translateXProperty().bind(joueur.positionXProperty());
        spriteView.translateYProperty().bind(joueur.positionYProperty());



        System.out.println(pane.getChildren());
        pane.getChildren().add(spriteView);

        // Ecouteurs d'événements pour détecter les touches pressées

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



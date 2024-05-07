package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.scene.input.KeyEvent;
import javafx.scene.layout.StackPane;
import javafx.scene.paint.Color;
import javafx.scene.shape.Circle;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Entite;
import universite_paris8.iut.mcontay.saejeu2d.modele.Personnage;
import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.layout.TilePane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;

import universite_paris8.iut.mcontay.saejeu2d.modele.Sprite;
import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.TilePane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.net.URL;
import java.util.ResourceBundle;
import java.util.Stack;

public class Controleur implements Initializable {

    private static final double MOVE_DISTANCE = 2;
    @FXML
    private universite_paris8.iut.mcontay.saejeu2d.modele.Terrain Terrain;

    @FXML
    private StackPane stackpane;

    @FXML
    private TilePane TilePane;

    private TerrainVue vue;
    private static Sprite joueur;


    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        Terrain = new Terrain(360, 240);
        this.vue = new TerrainVue(360, 240, TilePane, Terrain);
        this.vue.affichageVue();


        // Initialisation du sprite

        joueur = new Sprite(0, 0);
        Image spriteImage = null;
        try {
            spriteImage = new Image(Lanceur.class.getResource("joueurDeboutFace.png").openStream());
        } catch (IOException e) {
            throw new RuntimeException(e);
        }

        ImageView spriteView = new ImageView(spriteImage);
        spriteView.setFitHeight(15);
        spriteView.setFitWidth(15);
        spriteView.setPreserveRatio(true);

        System.out.println(stackpane.getChildren());
        stackpane.getChildren().add(spriteView);

        // Ecouteurs d'événements pour détecter les touches pressées
        while (true) {

            //handleKey();
        }
    }

    public void handleKey(KeyEvent e) {
        if (e.getCode() == KeyCode.Z) {
            joueur.setPositionY(joueur.getPositionY() - MOVE_DISTANCE);
        } else if (e.getCode() == KeyCode.Q) {
            joueur.setPositionX(joueur.getPositionX() - MOVE_DISTANCE);
        } else if (e.getCode() == KeyCode.S) {
            joueur.setPositionY(joueur.getPositionY() + MOVE_DISTANCE);
        } else if (e.getCode() == KeyCode.D) {
            joueur.setPositionX(joueur.getPositionX() + MOVE_DISTANCE);
        }
    }

}



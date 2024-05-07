package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.scene.paint.Color;
import javafx.scene.shape.Circle;
import universite_paris8.iut.mcontay.saejeu2d.modele.Entite;
import universite_paris8.iut.mcontay.saejeu2d.modele.Personnage;
import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.layout.TilePane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

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

public class Controleur implements Initializable {

    @FXML
    private universite_paris8.iut.mcontay.saejeu2d.modele.Terrain Terrain;
    @FXML
    private TilePane panneauJeu;

    private TerrainVue vue;


    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        Terrain = new Terrain(360, 240);
        this.vue = new TerrainVue(360, 240, panneauJeu, Terrain);
        this.vue.affichageVue();


//        // Initialisation du sprite
//
//        Sprite joueur = new Sprite(0, 0);
//        Image spriteImage = new Image("");
//        spriteView = new ImageView(spriteImage);
//        spriteView.setFitWidth(SPRITE_SIZE);
//        spriteView.setFitHeight(SPRITE_SIZE);
//
//        // Liaison de la position du sprite à la position de l'ImageView
//        spriteView.translateXProperty().bind(sprite.positionXProperty());
//        spriteView.translateYProperty().bind(sprite.positionYProperty());
//
//        panneauJeu.getChildren().add(spriteView);
//
//        // Ecouteurs d'événements pour détecter les touches pressées
//        panneauJeu.getScene().setOnKeyPressed(event -> {
//            if (event.getCode() == KeyCode.Z) {
//                sprite.setPositionY(sprite.getPositionY() - MOVE_DISTANCE);
//            } else if (event.getCode() == KeyCode.Q) {
//                sprite.setPositionX(sprite.getPositionX() - MOVE_DISTANCE);
//            } else if (event.getCode() == KeyCode.S) {
//                sprite.setPositionY(sprite.getPositionY() + MOVE_DISTANCE);
//            } else if (event.getCode() == KeyCode.D) {
//                sprite.setPositionX(sprite.getPositionX() + MOVE_DISTANCE);
//            }
//        });
//    }
    }

}
//    private void creerSprite(Entite e) {
//        //System.out.println("ajouter sprite");
//        Circle r;
//        if( e instanceof Personnage){
//            r= new Circle(3);
//            r.setFill(Color.RED);
//        }
//        else{
//            r= new Circle(2);
//            r.setFill(Color.WHITE);
//        }
//        r.setId(e.getId());
//        r.translateXProperty().bind(a.returnXX());
//        r.translateYProperty().bind(a.returnYY());
//        r.setOnMouseClicked(e-> System.out.println("clic sur acteur"+ e.getSource()));
//        panneauJeu.getChildren().add(r);
//    }


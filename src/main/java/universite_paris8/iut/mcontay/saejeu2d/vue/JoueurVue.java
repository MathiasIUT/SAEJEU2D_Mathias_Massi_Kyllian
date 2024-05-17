package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.io.IOException;


public class JoueurVue {
    private Image spriteImage;
    public JoueurVue(Pane pane,Joueur joueur) {


        try {
            spriteImage = new Image(Lanceur.class.getResource("joueurAvanceFace.png").openStream());
        } catch (IOException e) {
            throw new RuntimeException(e);
        }

        ImageView spriteView = new ImageView(spriteImage);
        spriteView.setFitHeight(32);
        spriteView.setFitWidth(32);
        spriteView.translateXProperty().bind(joueur.positionXProperty());
        spriteView.translateYProperty().bind(joueur.positionYProperty());



        pane.getChildren().add(spriteView);
        System.out.println(pane.getChildren());
    }



}


package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.application.Platform;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.io.IOException;

public class JoueurVue {

    private ImageView spriteView;
    private Joueur joueur;
    private Image spriteImage;
    private Image[] images;

    public JoueurVue(Pane pane, Joueur joueur) {
        this.joueur = new Joueur(new Terrain(), joueur.getPositionX(), joueur.getPositionY());

        images = new Image[4];
        try {
            images[0] = new Image(Lanceur.class.getResource("joueurAvanceFace.png").openStream());
            images[1] = new Image(Lanceur.class.getResource("joueurAvanceDos.png").openStream());
            images[2] = new Image(Lanceur.class.getResource("joueurAvanceGauche.png").openStream());
            images[3] = new Image(Lanceur.class.getResource("joueurAvanceDroite.png").openStream());
        } catch (IOException e) {
            throw new RuntimeException(e);
        }

        spriteView = new ImageView(images[0]);
        spriteView.setFitHeight(32);
        spriteView.setFitWidth(32);
        spriteView.translateXProperty().bind(joueur.positionXProperty());
        spriteView.translateYProperty().bind(joueur.positionYProperty());
        pane.getChildren().add(spriteView);
    }

    public void VuePositionJoueur(double x, double y, KeyCode keyCode) {

        joueur.setPositionX(x);
        joueur.setPositionY(y);

        if (keyCode != null) {
            switch (keyCode) {
                case Z:
                    spriteView.setImage(images[1]);
                    break;
                case S:
                    spriteView.setImage(images[0]);
                    break;
                case Q:
                    spriteView.setImage(images[2]);
                    break;
                case D:
                    spriteView.setImage(images[3]);
                    break;
            }
        }
    }
}
package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.Pane;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;

import java.io.IOException;

public class JoueurVue {

    private ImageView spriteView;
    private ImageView vieView;
    private Joueur joueur;
    private Image[] imagesMarcheBas;
    private Image[] imagesMarcheHaut;
    private Image[] imagesMarcheGauche;
    private Image[] imagesMarcheDroite;
    private Image[] imageDeboutFace;



    private int indexFrame = 0;
    private int frameCounter = 0;
    private int frameDelay = 5; // Délai entre les changements d'images

    public JoueurVue(Pane pane, Joueur joueur) {
        this.joueur = joueur;

        chargerImages();

        spriteView = new ImageView(imagesMarcheBas[0]);
        spriteView.setFitHeight(48);
        spriteView.setFitWidth(48);
        spriteView.translateXProperty().bind(joueur.positionXProperty());
        spriteView.translateYProperty().bind(joueur.positionYProperty());
        pane.getChildren().add(spriteView);

        joueur.directionProperty().addListener((observable, oldValue, newValue) -> {
            changerImage(KeyCode.getKeyCode(newValue.toString()));
        });
    }

    private void chargerImages() {
        try {
            imagesMarcheBas = new Image[]{
                    new Image(Lanceur.class.getResource("joueurAvanceFace.png").openStream()),
                    new Image(Lanceur.class.getResource("joueurAvanceFace2.png").openStream())
            };
            imagesMarcheHaut = new Image[]{
                    new Image(Lanceur.class.getResource("joueurDeboutDos.png").openStream()),
                    new Image(Lanceur.class.getResource("joueurAvanceDos.png").openStream())
            };
            imagesMarcheGauche = new Image[]{
                    new Image(Lanceur.class.getResource("joueurDeboutGauche.png").openStream()),
                    new Image(Lanceur.class.getResource("joueurAvanceGauche.png").openStream())
            };
            imagesMarcheDroite = new Image[]{
                    new Image(Lanceur.class.getResource("joueurAvanceDroite.png").openStream()),
                    new Image(Lanceur.class.getResource("joueurDeboutDroite.png").openStream())
            };
            imageDeboutFace = new Image[]{
                    new Image(Lanceur.class.getResource("joueurDeboutFace.png").openStream())
            };
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
    }

    public void changerImage(KeyCode keyCode) {
        frameCounter++;
        if (frameCounter >= frameDelay) {
            frameCounter = 0;
            indexFrame = (indexFrame + 1) % 2;
        }

        if (keyCode != null) {
            switch (keyCode) {
                case Z:
                    spriteView.setImage(imagesMarcheHaut[indexFrame]);
                    break;
                case S:
                    spriteView.setImage(imagesMarcheBas[indexFrame]);
                    break;
                case Q:
                    spriteView.setImage(imagesMarcheGauche[indexFrame]);
                    break;
                case D:
                    spriteView.setImage(imagesMarcheDroite[indexFrame]);
                    break;
            }
        }
    }
    public void reinitialiserAnimation() {
        frameCounter = 0;
        indexFrame = 0;
        spriteView.setImage(imageDeboutFace[0]);
    }


}
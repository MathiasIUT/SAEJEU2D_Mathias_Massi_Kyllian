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
        spriteView.setFitHeight(16);
        spriteView.setFitWidth(16);
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
                    new Image(Lanceur.class.getResource("joueur/joueurDeboutFace.png").openStream()),
                    new Image(Lanceur.class.getResource("joueur/joueurAvanceFace2.png").openStream()),
                    new Image(Lanceur.class.getResource("joueur/joueurAvanceFace.png").openStream())
            };
            imagesMarcheHaut = new Image[]{
                    new Image(Lanceur.class.getResource("joueur/joueurDeboutDos.png").openStream()),
                    new Image(Lanceur.class.getResource("joueur/joueurAvanceDos.png").openStream()),
                    new Image(Lanceur.class.getResource("joueur/joueurAvanceDos2.png").openStream())
            };
            imagesMarcheGauche = new Image[]{
                    new Image(Lanceur.class.getResource("joueur/joueurDeboutGauche.png").openStream()),
                    new Image(Lanceur.class.getResource("joueur/joueurAvanceGauche.png").openStream()),
                    new Image(Lanceur.class.getResource("joueur/joueurAvanceGauche2.png").openStream())
            };
            imagesMarcheDroite = new Image[]{
                    new Image(Lanceur.class.getResource("joueur/joueurDeboutDroit.png").openStream()),
                    new Image(Lanceur.class.getResource("joueur/joueurAvanceDroite.png").openStream()),
                    new Image(Lanceur.class.getResource("joueur/joueurAvanceDroite2.png").openStream())
            };
//            imagesAttaque = new Image[]{
//                    new Image(Lanceur.class.getResource("joueurAttaqueHaut.png").openStream()),
//                    new Image(Lanceur.class.getResource("joueurAttaqueBas.png").openStream()),
//                    new Image(Lanceur.class.getResource("joueurAttaqueDroite.png").openStream()),
//                    new Image(Lanceur.class.getResource("joueurAttaqueGauche.png").openStream())
//            };
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
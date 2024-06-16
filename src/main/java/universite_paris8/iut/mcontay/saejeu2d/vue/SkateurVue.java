package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Skateur;

import java.io.IOException;

public class SkateurVue {
    private Skateur skateur;
    private ImageView spriteView;
    private Image[] imagesRatPnj;

    public SkateurVue(Pane pane, Skateur skateur) {
        this.skateur = skateur;
        chargerImages();
        spriteView.setFitHeight(16);
        spriteView.setFitWidth(16);
        spriteView.translateXProperty().bind(skateur.positionXProperty());
        spriteView.translateYProperty().bind(skateur.positionYProperty());
        pane.getChildren().add(spriteView);

        skateur.directionProperty().addListener((observable, oldValue, newValue) -> changerImageSelonDirection());
    }

    private void chargerImages() {
        try {
            imagesRatPnj = new Image[]{
                    new Image(Lanceur.class.getResource("ratSkateInverse.png").openStream()),
                    new Image(Lanceur.class.getResource("ratSkate.png").openStream())
            };
            spriteView = new ImageView(imagesRatPnj[0]);
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
    }

    public void changerImageSelonDirection() {
        if (skateur.getDirection() == 2) {
            spriteView.setImage(imagesRatPnj[1]);
        } else if (skateur.getDirection() == 4) {
            spriteView.setImage(imagesRatPnj[0]);
        }
    }
}

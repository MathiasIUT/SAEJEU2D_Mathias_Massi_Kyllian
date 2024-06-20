package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Skater;
import java.io.IOException;
/* La classe SkateurVue gère l'affichage et la mise à jour graphique d'un personnage skateur en chargeant ses images et en changeant l'image affichée
 en fonction de la direction du skateur, tout en liant sa position à l'interface utilisateur. */

public class SkaterVue {
    private Skater skater;
    private ImageView spriteView;
    private Image[] imagesRatPnj;

    public SkaterVue(Pane pane, Skater skater) {
        this.skater = skater;
        chargerImages();
        spriteView.setFitHeight(24);
        spriteView.setFitWidth(24);
        spriteView.translateXProperty().bind(skater.positionXProperty());
        spriteView.translateYProperty().bind(skater.positionYProperty());
        pane.getChildren().add(spriteView);

        skater.directionProperty().addListener((observable, oldValue, newValue) -> changerImageSelonDirection());
    }

    private void chargerImages() {
        try {
            imagesRatPnj = new Image[]{
                    new Image(Lanceur.class.getResource("pnj/ratSkateInverse.png").openStream()),
                    new Image(Lanceur.class.getResource("pnj/ratSkate.png").openStream())
            };
            spriteView = new ImageView(imagesRatPnj[0]);
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
    }

    public void changerImageSelonDirection() {
        if (skater.getDirection() == 2) {
            spriteView.setImage(imagesRatPnj[1]);
        } else if (skater.getDirection() == 4) {
            spriteView.setImage(imagesRatPnj[0]);
        }
    }
}

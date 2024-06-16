package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Monstre;
import java.io.IOException;

/* La classe MonstreVue gère l'affichage graphique d'un monstre en chargeant son image et en liant sa position à l'interface utilisateur,
    tout en supprimant l'affichage lorsque le monstre meurt.*/

public class MonstreVue {

    private Monstre monstre;
    private ImageView spriteView;
    private Image[] imageMonstre;

    public MonstreVue(Pane pane, Monstre monstre) {
        this.monstre = monstre;
        chargerImages();
        spriteView.setFitHeight(16);
        spriteView.setFitWidth(16);
        spriteView.translateXProperty().bind(monstre.positionXProperty());
        spriteView.translateYProperty().bind(monstre.positionYProperty());
        pane.getChildren().add(spriteView);

        monstre.estMortProperty().addListener((observable, oldValue, newValue) -> {
            if (newValue) {
                supprimerVue();
            }
        });
    }

    private void chargerImages() {
        try {
            imageMonstre = new Image[]{
                    new Image(Lanceur.class.getResource("imagesEnnemi/SkeletteDroite.png").openStream()),
            };
            spriteView = new ImageView(imageMonstre[0]);
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
    }

    public void supprimerVue() {
        Pane parent = (Pane) spriteView.getParent();
        if (parent != null) {
            parent.getChildren().remove(spriteView);
        }
    }
}

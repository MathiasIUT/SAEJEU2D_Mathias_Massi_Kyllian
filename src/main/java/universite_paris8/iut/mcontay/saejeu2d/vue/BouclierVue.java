package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Bouclier;

/*  La classe BouclierVue gère l'affichage d'un bouclier en créant une image sur un panneau (Pane) aux coordonnées du bouclier,
 et permet de supprimer cette image de l'interface utilisateur.*/

public class BouclierVue {
    private Pane pane;
    private Bouclier bouclier;
    private ImageView imageView;

    public BouclierVue(Pane pane, Bouclier bouclier) {
        this.pane = pane;
        this.bouclier = bouclier;
        afficherBouclier();
    }

    private void afficherBouclier() {
        imageView = new ImageView(bouclier.getImage());
        imageView.setX(bouclier.getPositionX());
        imageView.setY(bouclier.getPositionY());
        imageView.setFitHeight(16);
        imageView.setFitWidth(16);
        pane.getChildren().add(imageView);
    }

    public Bouclier getBouclier() {
        return bouclier;
    }

    public void supprimer() {
        pane.getChildren().remove(imageView);
    }
}

package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Epee;

/* La classe EpeeVue gère l'affichage graphique d'une épée en créant une représentation visuelle sur un panneau (Pane)
    aux coordonnées spécifiées et permet sa suppression de l'interface utilisateur.*/
public class EpeeVue {
    private Pane pane;
    private Epee epee;
    private ImageView imageView;

    public EpeeVue(Pane pane, Epee epee) {
        this.pane = pane;
        this.epee = epee;
        afficherEpee();
    }

    private void afficherEpee() {
        imageView = new ImageView(epee.getImage());
        imageView.setX(epee.getPositionX());
        imageView.setY(epee.getPositionY());
        imageView.setFitHeight(16);
        imageView.setFitWidth(16);
        pane.getChildren().add(imageView);
    }

    public void supprimer() {
        pane.getChildren().remove(imageView);
    }
}

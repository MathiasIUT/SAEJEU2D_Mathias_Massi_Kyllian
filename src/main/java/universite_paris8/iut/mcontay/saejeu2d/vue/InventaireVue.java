package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Inventaire;
import universite_paris8.iut.mcontay.saejeu2d.modele.Objet;

public class InventaireVue {
    private Inventaire inventaire;
    private Pane pane;

    public InventaireVue(Pane pane, Inventaire inventaire) {
        this.pane = pane;
        this.inventaire = inventaire;
    }

    public void afficherInventaire() {
        Objet epee = new Objet("Épée", "Une épée tranchante", "/universite_paris8/iut/mcontay/saejeu2d/Épée.png");
        Objet bouclier = new Objet("Bouclier", "Un bouclier robuste", "/universite_paris8/iut/mcontay/saejeu2d/Bouclier.png");

        inventaire.ajouterObjet(epee);
        inventaire.ajouterObjet(bouclier);

        afficherObjet(epee, 50, 50);  // Position de l'épée
        afficherObjet(bouclier, 80, 50);  // Position du bouclier
    }

    private void afficherObjet(Objet objet, double x, double y) {
        ImageView imageView = new ImageView(objet.getImage());
        imageView.setFitHeight(32);
        imageView.setFitWidth(32);
        imageView.setLayoutX(x);
        imageView.setLayoutY(y);
        pane.getChildren().add(imageView);
    }

}

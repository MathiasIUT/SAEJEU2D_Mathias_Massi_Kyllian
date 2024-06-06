package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;
import javafx.scene.text.Font;
import javafx.scene.text.Text;
import universite_paris8.iut.mcontay.saejeu2d.modele.Inventaire;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Objet;
import universite_paris8.iut.mcontay.saejeu2d.modele.Vie;

public class InventaireVue {
    private Vie vie;
    private Inventaire inventaire;
    private Pane pane;
    private Joueur joueur;

    public InventaireVue(Pane pane, Inventaire inventaire, Joueur joueur) {
        this.pane = pane;
        this.inventaire = inventaire;
        this.joueur = joueur;
    }

    public void afficherInventaire() {
        Objet epee = new Objet("Épée", "Une épée tranchante", "/universite_paris8/iut/mcontay/saejeu2d/Épée.png");
        Objet bouclier = new Objet("Bouclier", "Un bouclier robuste", "/universite_paris8/iut/mcontay/saejeu2d/Bouclier.png");

        inventaire.ajouterObjet(epee);
        inventaire.ajouterObjet(bouclier);

        afficherObjet(epee, 50, 50);
        afficherObjet(bouclier, 80, 50);

    }

    private void afficherObjet(Objet objet, double x, double y) {
        ImageView imageView = new ImageView(objet.getImage());
        imageView.setFitHeight(32);
        imageView.setFitWidth(32);
        imageView.setLayoutX(x);
        imageView.setLayoutY(y);
        pane.getChildren().add(imageView);
        imageView.setOnMouseClicked(event -> utiliserObjet(objet));
    }

    public void utiliserObjet(Objet objet) {
        System.out.println("Objet utilisé: " + objet.getNom());
        if (objet.getDegats() > 0) {
            vie.infligerDegats(objet.getDegats());
        }

    }

//    private void afficherEcranMort() {
//        Rectangle ecranNoir = new Rectangle(pane.getWidth(), pane.getHeight(), Color.BLACK);
//        ecranNoir.setOpacity(0.7);
//        ecranNoir.widthProperty().bind(pane.widthProperty());
//        ecranNoir.heightProperty().bind(pane.heightProperty());
//        pane.getChildren().add(ecranNoir);
//        Text texteMort = new Text("You are Dead");
//        texteMort.setFill(Color.RED);
//        texteMort.setFont(Font.font("Verdana", 50));
//        texteMort.xProperty().bind(pane.widthProperty().subtract(texteMort.getLayoutBounds().getWidth()).divide(3));
//        texteMort.yProperty().bind(pane.heightProperty().subtract(texteMort.getLayoutBounds().getHeight()).divide(3).add(texteMort.getFont().getSize()));
//        pane.getChildren().add(texteMort);
//    }
}


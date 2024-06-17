package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.layout.Pane;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;

public class VieVue {
    private Pane pane;
    private Joueur joueur;
    private Rectangle fond;
    private Rectangle barre;

    public VieVue(Pane pane, Joueur joueur) {
        this.pane = pane;
        this.joueur = joueur;

        fond = new Rectangle(150, 10);
        fond.setFill(Color.RED);

        barre = new Rectangle(0, 10);
        barre.setFill(Color.GREEN);

        pane.getChildren().addAll(fond, barre);

        joueur.ptsDeVieProperty().addListener((observable, oldValue, newValue) -> {
            updateBarreVie();
        });

        updateBarreVie();
    }

    public void updateBarreVie() {
        int vieActuelle = joueur.getPtsDeVie();
        double largeurTotale = 150.0;
        double largeurBarreVie = (double) vieActuelle / (double) joueur.getMaxPtsDeVie() * largeurTotale;

        barre.setWidth(largeurBarreVie);
    }
}

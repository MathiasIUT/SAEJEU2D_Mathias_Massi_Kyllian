package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;

public class Bouclier extends Objet {
    private int barreVieSupplementaire;
    private Rectangle fond;
    private Rectangle barre;

    public Bouclier(String nom, String description, Terrain terrain, int degats, String imagePath, double x, double y, int barreVieSupplementaire) {
        super(nom, description, terrain, degats, imagePath, x, y);
        this.barreVieSupplementaire = barreVieSupplementaire;
    }

    public int getBarreVieSupplementaire() {
        return barreVieSupplementaire;
    }
}

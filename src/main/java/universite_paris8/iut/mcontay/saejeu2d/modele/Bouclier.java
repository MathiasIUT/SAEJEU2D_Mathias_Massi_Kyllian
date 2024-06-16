package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.scene.shape.Rectangle;

/* La classe Bouclier hérite de Objet et représente un bouclier avec des points de vie supplémentaires et un nombre limité d'utilisations.
Elle initialise ces attributs dans le constructeur et fournit des méthodes pour obtenir et décrémenter les utilisations restantes. */

public class Bouclier extends Objet {
    private int barreVieSupplementaire;
    private Rectangle fond;
    private Rectangle barre;
    private int utilisationsRestantes;

    public Bouclier(String nom, String description, Terrain terrain, int degats, String imagePath, double x, double y, int barreVieSupplementaire) {
        super(nom, description, terrain, degats, imagePath, x, y);
        this.barreVieSupplementaire = barreVieSupplementaire;
        this.utilisationsRestantes = 5;  // Utiliser le bouclier que 5 fois
    }

    public int getUtilisationsRestantes() {
        return utilisationsRestantes;
    }

    public void decrementerUtilisations() {
        if (utilisationsRestantes > 0) {
            utilisationsRestantes--;
        }
    }

    public int getBarreVieSupplementaire() {
        return barreVieSupplementaire;
    }
}

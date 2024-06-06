package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Joueur extends Entite {

    private IntegerProperty vie;
    private IntegerProperty vieMax;
    private Inventaire inventaire;

    public Joueur(Terrain terrain, String nom, int id, double initialX, double initialY, int vie) {
        super(terrain,nom, id ,initialX, initialY);
        this.vie = new SimpleIntegerProperty(vie);
        this.vieMax = new SimpleIntegerProperty(vie);
        setInventaire(inventaire);
      }
    public IntegerProperty vieMaxProperty() {
        return vieMax;
    }

    public int getVieMax() {
        return vieMax.get();
    }
    public void setVie(int vie) {
        this.vie.set(vie);
    }

    public IntegerProperty vieProperty() {
        return vie;
    }
    public int getVie() {
        return vie.get();
    }

    public void perdreVie(int degats) {
        int nouvelleVie = vie.get() - degats;
        if (nouvelleVie <= 0) {
            nouvelleVie = 0;
        }
        vie.set(nouvelleVie);
    }
    public Inventaire getInventaire() {
        return inventaire;
    }

    public void setInventaire(Inventaire inventaire) {
        this.inventaire = inventaire;
    }
}

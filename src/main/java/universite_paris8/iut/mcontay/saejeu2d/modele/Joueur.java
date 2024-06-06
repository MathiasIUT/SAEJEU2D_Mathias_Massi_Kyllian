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
      public int getVieMax() {return vieMax.get();}
    public void setVie(int vie) {this.vie.set(Math.max(vie, 0));}
    public IntegerProperty vieProperty() {
        return vie;
    }
    public int getVie() {return vie.get();}
    public void setInventaire(Inventaire inventaire) {
        this.inventaire = inventaire;
    }
}

package universite_paris8.iut.mcontay.saejeu2d.modele;

public class Personnage extends Entite {
    private String ptsAttaque;
    private String ptsDefense;

    public Personnage(Terrain terrain, String nom, String ptsAttaque, String ptsDefense, int id, double initialX, double initialY) {
        super(terrain, nom, id, initialX, initialY);
        this.ptsAttaque = ptsAttaque;
        this.ptsDefense = ptsDefense;
    }

    public String getPtsAttaque() {
        return ptsAttaque;
    }

    public String getPtsDefense() {
        return ptsDefense;
    }
}

package universite_paris8.iut.mcontay.saejeu2d.modele;

public class Personnage extends Entite{

    private String ptsAttaque ;
    private String ptsDefense ;
    public Personnage(String nom, String ptsDeVie, String ptsAttaque, String ptsDefense,int id) {
        super(nom, ptsDeVie, id);
        this.ptsAttaque = ptsAttaque ;
        this.ptsDefense = ptsDefense ;
    }

    public String getPtsAttaque() {
        return ptsAttaque;
    }

    public String getPtsDefense() {
        return ptsDefense;
    }
}

package universite_paris8.iut.mcontay.saejeu2d.modele;

public abstract class Entite {

    private String nom ;
    private String ptsDeVie ;
    private int id ;
    public Entite (String nom, String ptsDeVie, int id){
        this.nom = nom ;
        this.ptsDeVie = ptsDeVie ;
        this.id = id ;
    }

    public String getNom() {
        return nom;
    }

    public int getId() {
        return id;
    }

    public String getPtsDeVie() {
        return ptsDeVie;
    }
}
